<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'label'];

    public function users(): BelongsToMany
    {
        // On précise explicitement la table pivot pour éviter toute ambiguïté.
        return $this->belongsToMany(User::class, 'role_user');
    }
}
