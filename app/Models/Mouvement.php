<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mouvement extends Model
{
    public const TYPE_ENTREE     = 'entree';
    public const TYPE_SORTIE     = 'sortie';
    public const TYPE_TRANSFERT  = 'transfert';
    public const TYPE_AJUSTEMENT = 'ajustement';

    public const TYPES = [
        self::TYPE_ENTREE     => 'Entrée',
        self::TYPE_SORTIE     => 'Sortie',
        self::TYPE_TRANSFERT  => 'Transfert',
        self::TYPE_AJUSTEMENT => 'Ajustement',
    ];

    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TERMINE  = 'termine';

    protected $fillable = [
        'numero', 'type', 'minerai_id', 'quantite',
        'site_source_id', 'site_destination_id',
        'motif', 'date_mouvement', 'statut_transfert', 'date_fin', 'user_id',
    ];

    protected $casts = [
        'quantite'       => 'decimal:3',
        'date_mouvement' => 'datetime',
        'date_fin'       => 'datetime',
    ];

    //statut_transfert automatique à la création

    protected static function booted(): void
    {
        static::creating(function (Mouvement $m) {
            if ($m->type === self::TYPE_TRANSFERT) {
                $m->statut_transfert = self::STATUT_EN_COURS;
            }
        });
    }

    // Relations

    public function minerai(): BelongsTo
    {
        return $this->belongsTo(Minerai::class);
    }

    public function siteSource(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_source_id');
    }

    public function siteDestination(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_destination_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /** Label lisible du type */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /** Vrai si c'est un transfert en cours */
    public function getTransfertEnCoursAttribute(): bool
    {
        return $this->type === self::TYPE_TRANSFERT
            && $this->statut_transfert === self::STATUT_EN_COURS;
    }

    /** Vrai si c'est un transfert terminé */
    public function getTransfertTermineAttribute(): bool
    {
        return $this->type === self::TYPE_TRANSFERT
            && $this->statut_transfert === self::STATUT_TERMINE;
    }

    /**
     * Durée lisible du transfert.
     * – Terminé  : date_mouvement → date_fin
     * – En cours : date_mouvement → now()
     * – Autre type : null
     */
    public function getDureeAttribute(): ?string
    {
        if ($this->type !== self::TYPE_TRANSFERT || ! $this->date_mouvement) {
            return null;
        }

        $fin     = $this->date_fin ?? now();
        $seconds = (int) $this->date_mouvement->diffInSeconds($fin);

        if ($seconds < 60) {
            return "{$seconds} s";
        }

        $hours   = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return sprintf('%d h %02d min', $hours, $minutes);
        }

        return sprintf('%d min %02d s', $minutes, 0);
    }

    /** Clôture le transfert (passe à 'termine' et enregistre date_fin). */
    public function cloturer(): bool
    {
        if ($this->type !== self::TYPE_TRANSFERT) {
            return false;
        }

        return $this->update([
            'statut_transfert' => self::STATUT_TERMINE,
            'date_fin'         => now(),
        ]);
    }
}
