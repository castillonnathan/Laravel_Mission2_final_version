<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterielController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\MineraiController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\StockController;

Route::get('/', function () {
    return view('Acceuil');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::post('users/{user}/roles', [RoleController::class, 'assignToUser'])->name('users.roles.assign');
    Route::delete('users/{user}/roles/{role}', [RoleController::class, 'removeFromUser'])->name('users.roles.remove');
});

Route::middleware(['auth', 'role:admin, technicien'])->group(function () {

    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');

    // Mouvements : consultation + création pour tous (admin & technicien)
    // Clôture d'un transfert : admin & technicien
    // Suppression : protégée dans le controller (admin uniquement)
    Route::resource('mouvements', MouvementController::class)
        ->except(['edit', 'update']);

    Route::patch('mouvements/{mouvement}/cloturer', [MouvementController::class, 'cloturer'])
        ->name('mouvements.cloturer');

    // Sites & Minerais : ADMIN UNIQUEMENT
    Route::middleware('role:admin')->group(function () {
        Route::resource('sites', SiteController::class)->except(['show']);
        Route::resource('minerais', MineraiController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
