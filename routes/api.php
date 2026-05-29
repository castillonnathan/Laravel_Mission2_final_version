<?php

use App\Http\Controllers\Api\MaterielApiController;
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

// Route publique de connexion
Route::post('login', [AuthApiController::class, 'login']);

// Routes protégées par token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::get('me', [AuthApiController::class, 'me']);

    Route::apiResource('materiels', MaterielApiController::class);
});
