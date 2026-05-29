<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin',      'label' => 'Administrateur'],
            ['name' => 'technicien', 'label' => 'Technicien'],
            ['name' => 'client',     'label' => 'Client'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // Assigner admin au premier utilisateur
        User::first()?->assignRole('admin');
    }
}
