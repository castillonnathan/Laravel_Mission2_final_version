<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    public const TYPE_MINE       = 'mine';
    public const TYPE_DEPOT      = 'depot';
    public const TYPE_CLIENT     = 'client_site';
    public const TYPE_AUTRE      = 'autre';

    protected $fillable = [
        'nom', 'type', 'adresse', 'ville', 'code_postal', 'pays', 'notes', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Relations

    public function stocks(): HasMany
    {
        return $this->hasMany(StockMinerai::class);
    }

    public function mouvementsSortants(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'site_source_id');
    }

    public function mouvementsEntrants(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'site_destination_id');
    }

    /**
     * Minerais exploitables déclarés sur ce site.
     * N'est pertinent que pour les sites de type "mine".
     */
    public function mineraisAutorises(): BelongsToMany
    {
        return $this->belongsToMany(Minerai::class, 'site_minerai')
                    ->withTimestamps();
    }

    public function estUneMine(): bool
    {
        return $this->type === self::TYPE_MINE;
    }

    /** Stock courant d'un minerai sur ce site. */
    public function stockDe(int $mineraiId): float
    {
        return (float) ($this->stocks()
            ->where('minerai_id', $mineraiId)
            ->value('quantite') ?? 0);
    }

    /**
     * Vérifie si un minerai est autorisé sur ce site.
     *
     * Règle métier :
     *   - Site de type "mine"  → autorisé seulement si le minerai figure dans
     *                            la liste des minerais exploitables.
     *   - Tout autre type      → toujours autorisé (dépôt, client, autre).
     */
    public function accepteMinerai(int $mineraiId): bool
    {
        if (! $this->estUneMine()) {
            return true; // dépôt/client → jamais bloqué
        }

        return $this->mineraisAutorises()->where('minerai_id', $mineraiId)->exists();
    }
}
