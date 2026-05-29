<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('label')->get();
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|unique:roles|alpha_dash',
            'label' => 'required',
        ]);

        Role::create($request->only('name', 'label'));

        return back()->with('success', 'Rôle créé.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'Le rôle administrateur est protégé.');
        }

        $role->delete();

        return back()->with('success', 'Rôle supprimé.');
    }

    /**
     * Ajoute un rôle à l'utilisateur SANS supprimer les autres.
     */
    public function assignToUser(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        $newRole = Role::where('name', $request->role)->firstOrFail();

        // Vérifie si l'utilisateur possède déjà ce rôle
        if ($user->roles()->where('roles.id', $newRole->id)->exists()) {
            return back()->with('error',
                "{$user->name} possède déjà le rôle « {$newRole->label} »."
            );
        }

        $user->roles()->attach($newRole->id);

        return back()->with('success',
            "Rôle « {$newRole->label} » ajouté à {$user->name}."
        );
    }

    /**
     * Retire un rôle précis à l'utilisateur (les autres restent).
     */
    public function removeFromUser(User $user, Role $role)
    {
        // Protection : on ne retire pas le dernier admin du système.
        if ($role->name === 'admin') {
            $adminRole = Role::where('name', 'admin')->first();
            $adminCount = $adminRole ? $adminRole->users()->count() : 0;

            if ($adminCount <= 1) {
                return back()->with('error',
                    "Impossible : {$user->name} est le dernier administrateur du système."
                );
            }

            // Sécurité supplémentaire : un admin ne peut pas se retirer son propre rôle admin
            if ($user->id === auth()->id()) {
                return back()->with('error',
                    "Vous ne pouvez pas retirer votre propre rôle administrateur."
                );
            }
        }

        $user->roles()->detach($role->id);

        return back()->with('success',
            "Rôle « {$role->label} » retiré à {$user->name}."
        );
    }
}
