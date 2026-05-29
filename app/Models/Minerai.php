<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Minerai extends Model
{
    protected $table = 'minerais';

    protected $fillable = [
        'code', 'nom', 'unite', 'description', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(StockMinerai::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class);
    }
}
