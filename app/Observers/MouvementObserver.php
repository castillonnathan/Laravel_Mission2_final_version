<?php

namespace App\Observers;

use App\Models\Mouvement;
use App\Models\StockMinerai;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MouvementObserver
{
    /**
     * Avant la création : validations métier + génération du numéro.
     */
    public function creating(Mouvement $mouvement): void
    {
        // 1. Validation des sites selon le type
        $this->validateSitesPourType($mouvement);

        // 2. Validation des stocks (pas de stock négatif sur sortie/transfert)
        $this->validateStockDisponible($mouvement);

        // 3. Génération du numéro si non fourni
        if (empty($mouvement->numero)) {
            $mouvement->numero = $this->genererNumero();
        }

        // 4. Date par défaut
        if (empty($mouvement->date_mouvement)) {
            $mouvement->date_mouvement = now();
        }

        // 5. User par défaut
        if (empty($mouvement->user_id) && auth()->check()) {
            $mouvement->user_id = auth()->id();
        }
    }

    /**
     * Après création : mise à jour des stocks.
     */
    public function created(Mouvement $mouvement): void
    {
        $this->appliquerSurStocks($mouvement, sens: +1);
    }

    /**
     * Interdire toute modification d'un mouvement validé.
     * (immuabilité comptable — on crée un nouveau mouvement d'ajustement pour corriger)
     */
    public function updating(Mouvement $mouvement): void
    {
        $champsModifies  = array_keys($mouvement->getDirty());
        $champsAutorises = ['statut_transfert', 'date_fin'];

        if (empty(array_diff($champsModifies, $champsAutorises))) {
            return;
        }
        throw ValidationException::withMessages([
            'mouvement' => "Un mouvement ne peut pas être modifié après sa création. Créez un mouvement d'ajustement pour corriger.",
        ]);
    }

    /**
     * Avant suppression : inverser l'effet sur les stocks.
     */
    public function deleting(Mouvement $mouvement): void
    {
        $this->appliquerSurStocks($mouvement, sens: -1);
    }

    /**
     * Vérifie que les sites source/destination sont cohérents avec le type.
     */
    protected function validateSitesPourType(Mouvement $mouvement): void
    {
        $type = $mouvement->type;
        $hasSource = !empty($mouvement->site_source_id);
        $hasDest   = !empty($mouvement->site_destination_id);

        $erreurs = [];

        if ($type === Mouvement::TYPE_ENTREE) {
            if ($hasSource) {
                $erreurs[] = "Une entrée ne doit pas avoir de site source.";
            }
            if (!$hasDest) {
                $erreurs[] = "Une entrée doit avoir un site de destination.";
            }
        } elseif ($type === Mouvement::TYPE_SORTIE) {
            if (!$hasSource) {
                $erreurs[] = "Une sortie doit avoir un site source.";
            }
            if ($hasDest) {
                $erreurs[] = "Une sortie ne doit pas avoir de site de destination.";
            }
        } elseif ($type === Mouvement::TYPE_TRANSFERT) {
            if (!$hasSource || !$hasDest) {
                $erreurs[] = "Un transfert doit avoir un site source ET un site de destination.";
            } elseif ($mouvement->site_source_id === $mouvement->site_destination_id) {
                $erreurs[] = "Le site source et le site de destination doivent être différents.";
            }
        } elseif ($type === Mouvement::TYPE_AJUSTEMENT) {
            if ($hasSource) {
                $erreurs[] = "Un ajustement ne doit pas avoir de site source.";
            }
            if (!$hasDest) {
                $erreurs[] = "Un ajustement doit avoir un site (renseigné comme destination).";
            }
        }

        // Quantité strictement positive sauf ajustement
        if ($type !== Mouvement::TYPE_AJUSTEMENT && $mouvement->quantite <= 0) {
            $erreurs[] = "La quantité doit être strictement positive (sauf pour un ajustement).";
        }
        if ($type === Mouvement::TYPE_AJUSTEMENT && $mouvement->quantite == 0) {
            $erreurs[] = "Un ajustement ne peut pas avoir une quantité nulle.";
        }

        if ($erreurs) {
            throw ValidationException::withMessages(['mouvement' => $erreurs]);
        }
    }

    /**
     * Refuse les mouvements qui rendraient un stock négatif.
     */
    protected function validateStockDisponible(Mouvement $mouvement): void
    {
        // Vérifie la dispo sur le site source pour sortie et transfert
        if (in_array($mouvement->type, [Mouvement::TYPE_SORTIE, Mouvement::TYPE_TRANSFERT], true)) {
            $stock = StockMinerai::where('site_id', $mouvement->site_source_id)
                ->where('minerai_id', $mouvement->minerai_id)
                ->value('quantite') ?? 0;

            if ((float) $stock < (float) $mouvement->quantite) {
                throw ValidationException::withMessages([
                    'quantite' => "Stock insuffisant sur le site source : disponible {$stock}, demandé {$mouvement->quantite}.",
                ]);
            }
        }

        // Vérifie qu'un ajustement négatif ne rend pas le stock < 0
        if ($mouvement->type === Mouvement::TYPE_AJUSTEMENT && $mouvement->quantite < 0) {
            $stock = StockMinerai::where('site_id', $mouvement->site_destination_id)
                ->where('minerai_id', $mouvement->minerai_id)
                ->value('quantite') ?? 0;

            if ((float) $stock + (float) $mouvement->quantite < 0) {
                throw ValidationException::withMessages([
                    'quantite' => "Ajustement négatif impossible : stock actuel {$stock}, ajustement {$mouvement->quantite}.",
                ]);
            }
        }
    }

    /**
     * Applique l'effet du mouvement sur la table stocks_minerai.
     * $sens = +1 pour appliquer, -1 pour annuler (cas de suppression).
     */
    protected function appliquerSurStocks(Mouvement $mouvement, int $sens): void
    {
        DB::transaction(function () use ($mouvement, $sens) {
            $delta = (float) $mouvement->quantite * $sens;
            $mineraiId = $mouvement->minerai_id;

            switch ($mouvement->type) {
                case Mouvement::TYPE_ENTREE:
                    $this->ajusterStock($mouvement->site_destination_id, $mineraiId, +$delta);
                    break;

                case Mouvement::TYPE_SORTIE:
                    $this->ajusterStock($mouvement->site_source_id, $mineraiId, -$delta);
                    break;

                case Mouvement::TYPE_TRANSFERT:
                    $this->ajusterStock($mouvement->site_source_id, $mineraiId, -$delta);
                    $this->ajusterStock($mouvement->site_destination_id, $mineraiId, +$delta);
                    break;

                case Mouvement::TYPE_AJUSTEMENT:
                    // La quantité peut être négative pour un ajustement
                    $this->ajusterStock($mouvement->site_destination_id, $mineraiId, +$delta);
                    break;
            }
        });
    }

    /**
     * Ajoute (ou soustrait) une quantité au stock du couple (site, minerai).
     * Crée la ligne si elle n'existe pas encore.
     */
    protected function ajusterStock(int $siteId, int $mineraiId, float $delta): void
    {
        $stock = StockMinerai::firstOrCreate(
            ['site_id' => $siteId, 'minerai_id' => $mineraiId],
            ['quantite' => 0]
        );

        $stock->quantite = (float) $stock->quantite + $delta;
        $stock->save();
    }

    /**
     * Génère un numéro unique de la forme MVT-2026-00001.
     */
    protected function genererNumero(): string
    {
        $annee = Carbon::now()->format('Y');
        $prefix = "MVT-{$annee}-";

        // Cherche le plus grand numéro de l'année en cours
        $dernier = Mouvement::where('numero', 'like', "{$prefix}%")
            ->orderByDesc('numero')
            ->value('numero');

        if ($dernier) {
            $derniereSequence = (int) substr($dernier, strlen($prefix));
            $sequence = $derniereSequence + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }
}
