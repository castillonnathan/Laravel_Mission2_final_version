<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $table = 'Materiel';
    protected $primaryKey = 'id_materiel';
    public $timestamps = false; // ← ajout

    protected $fillable = [
        'nom',
        'description',
        'quantite_stock',
        'seuil_alerte',
    ];

    protected $casts = [
        'quantite_stock' => 'integer',
        'seuil_alerte'   => 'integer',
    ];

    public function getEnAlerteAttribute(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte;
    }

    public function scopeEnAlerte($query)
    {
        return $query->whereColumn('quantite_stock', '<=', 'seuil_alerte');
    }

    public function scopeDisponible($query)
    {
        return $query->where('quantite_stock', '>', 0);
    }
}
