<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMinerai extends Model
{
    protected $table = 'stocks_minerai';

    protected $fillable = ['site_id', 'minerai_id', 'quantite'];

    protected $casts = [
        'quantite' => 'decimal:3',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function minerai(): BelongsTo
    {
        return $this->belongsTo(Minerai::class);
    }
}
