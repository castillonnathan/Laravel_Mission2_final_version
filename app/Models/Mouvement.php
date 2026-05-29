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

    protected $fillable = [
        'numero', 'type', 'minerai_id', 'quantite',
        'site_source_id', 'site_destination_id',
        'motif', 'date_mouvement', 'user_id',
    ];

    protected $casts = [
        'quantite'       => 'decimal:3',
        'date_mouvement' => 'datetime',
    ];

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

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
