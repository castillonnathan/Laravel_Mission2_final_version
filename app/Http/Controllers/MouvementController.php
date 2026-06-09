<?php

namespace App\Http\Controllers;

use App\Models\Minerai;
use App\Models\Mouvement;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MouvementController extends Controller
{
    // Liste avec filtres

    public function index(Request $request)
    {
        $query = Mouvement::with(['minerai', 'siteSource', 'siteDestination', 'user'])
            ->latest('date_mouvement');

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }
        if ($mineraiId = $request->query('minerai_id')) {
            $query->where('minerai_id', $mineraiId);
        }
        if ($siteId = $request->query('site_id')) {
            $query->where(function ($q) use ($siteId) {
                $q->where('site_source_id', $siteId)
                  ->orWhere('site_destination_id', $siteId);
            });
        }
        // Filtre statut transfert
        if ($statut = $request->query('statut_transfert')) {
            $query->where('type', Mouvement::TYPE_TRANSFERT)
                  ->where('statut_transfert', $statut);
        }

        $mouvements = $query->paginate(25)->withQueryString();
        $minerais   = Minerai::orderBy('nom')->get();
        $sites      = Site::orderBy('nom')->get();

        return view('mouvements.index', compact('mouvements', 'minerais', 'sites'));
    }

    // Formulaire de création

    public function create(Request $request)
    {
        $type     = $request->query('type', Mouvement::TYPE_ENTREE);
        $minerais = Minerai::where('actif', true)->orderBy('nom')->get();
        $sites    = Site::where('actif', true)->orderBy('nom')->get();

        // Pour chaque site de type mine : liste des IDs de minerais autorisés.
        // Pour les autres types : tableau vide (= pas de restriction côté JS).
        $sitesMineraisAutorises = $sites->mapWithKeys(fn ($s) => [
            $s->id => $s->estUneMine()
                ? $s->mineraisAutorises->pluck('id')
                : [],          // dépôt/client → [] = aucune restriction
        ]);

        // Stocks actuels par site
        $stocksParSite = $sites->mapWithKeys(fn ($s) => [
            $s->id => $s->stocks->pluck('quantite', 'minerai_id'),
        ]);

        // est-ce une mine ?
        $sitesSontMine = $sites->mapWithKeys(fn ($s) => [
            $s->id => $s->estUneMine(),
        ]);

        return view('mouvements.create', compact(
            'type', 'minerais', 'sites',
            'sitesMineraisAutorises', 'stocksParSite', 'sitesSontMine'
        ));
    }

    // Enregistrement

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'                => ['required', Rule::in(array_keys(Mouvement::TYPES))],
            'minerai_id'          => 'required|exists:minerais,id',
            'quantite'            => 'required|numeric',
            'site_source_id'      => 'nullable|exists:sites,id',
            'site_destination_id' => 'nullable|exists:sites,id',
            'motif'               => 'nullable|string',
            'date_mouvement'      => 'nullable|date',
        ]);

        $mineraiId = (int) $data['minerai_id'];
        $errors    = [];

        if (! empty($data['site_source_id'])) {
            $siteSource = Site::with('mineraisAutorises')->find($data['site_source_id']);

            if (! $siteSource->accepteMinerai($mineraiId)) {
                $mineraiNom = Minerai::find($mineraiId)?->nom ?? 'ce minerai';
                $errors['site_source_id'] =
                    "Le minerai « {$mineraiNom} » n'est pas exploitable sur la mine « {$siteSource->nom} ». "
                    . "Ce mouvement est refusé.";
            }
        }

        if (! empty($data['site_destination_id'])) {
            $siteDest = Site::with('mineraisAutorises')->find($data['site_destination_id']);

            if (! $siteDest->accepteMinerai($mineraiId)) {
                $mineraiNom = Minerai::find($mineraiId)?->nom ?? 'ce minerai';
                $errors['site_destination_id'] =
                    "Le minerai « {$mineraiNom} » n'est pas exploitable sur la mine « {$siteDest->nom} ». "
                    . "Ce mouvement est refusé.";
            }
        }

        if (! empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $mouvement = Mouvement::create($data);

        return redirect()
            ->route('mouvements.show', $mouvement)
            ->with('success', "Mouvement {$mouvement->numero} enregistré."
                . ($mouvement->type === Mouvement::TYPE_TRANSFERT
                    ? ' Statut : Transfert en cours.'
                    : ''));
    }


    public function show(Mouvement $mouvement)
    {
        $mouvement->load(['minerai', 'siteSource', 'siteDestination', 'user']);
        return view('mouvements.show', compact('mouvement'));
    }

    // Clôturer un transfert (admin ou technicien)

    public function cloturer(Mouvement $mouvement)
    {
        if ($mouvement->type !== Mouvement::TYPE_TRANSFERT) {
            return back()->with('error', 'Ce mouvement n\'est pas un transfert.');
        }

        if ($mouvement->statut_transfert === Mouvement::STATUT_TERMINE) {
            return back()->with('error', 'Ce transfert est déjà terminé.');
        }

        $mouvement->cloturer();

        return redirect()
            ->route('mouvements.show', $mouvement)
            ->with('success', "Transfert {$mouvement->numero} marqué comme terminé.");
    }

    // Suppression (admin uniquement)

    public function destroy(Mouvement $mouvement)
    {
        if (! auth()->user()->isAdmin()) {
            return back()->with('error', 'Seul un administrateur peut supprimer un mouvement.');
        }

        $numero = $mouvement->numero;
        $mouvement->delete();

        return redirect()
            ->route('mouvements.index')
            ->with('success', "Mouvement {$numero} supprimé.");
    }
}
