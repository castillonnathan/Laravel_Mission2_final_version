<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = [
        'nom', 'type', 'adresse', 'ville', 'code_postal', 'pays', 'notes', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

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
     * Stock courant d'un minerai sur ce site.
     */
    public function stockDe(int $mineraiId): float
    {
        return (float) ($this->stocks()
            ->where('minerai_id', $mineraiId)
            ->value('quantite') ?? 0);
    }
}
