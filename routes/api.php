<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importation des contrôleurs
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\HostsController;

/*
|--------------------------------------------------------------------------
| 1. Test Sanctum → retourne l’utilisateur connecté
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTIFICATION (routes publiques)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',   [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 3. Déconnexion (protégé)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| 4. PODCASTS — PUBLIC (lecture)
|--------------------------------------------------------------------------
*/
Route::get('/podcasts', [PodcastController::class, 'index']);
Route::get('/podcasts/{id}', [PodcastController::class, 'show']);

/*
|--------------------------------------------------------------------------
| 5. EPISODES — PUBLIC (lecture)
|--------------------------------------------------------------------------
*/
Route::get('/episodes', [EpisodeController::class, 'index']);
Route::get('/episodes/{id}', [EpisodeController::class, 'show']);

/*
|--------------------------------------------------------------------------
| 6. PODCASTS & EPISODES — PROTÉGÉ (écriture)
|    Rôles autorisés : admin, host
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin,host'])->group(function () {

    // PODCASTS CRUD
    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::put('/podcasts/{id}', [PodcastController::class, 'update']);
    Route::delete('/podcasts/{id}', [PodcastController::class, 'destroy']);

    // EPISODES CRUD
    Route::post('/episodes', [EpisodeController::class, 'store']);
    Route::put('/episodes/{id}', [EpisodeController::class, 'update']);
    Route::delete('/episodes/{id}', [EpisodeController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| 7. HOSTS — PUBLIC + PROTÉGÉ
|--------------------------------------------------------------------------
*/

// PUBLIC
Route::get('/hosts', [HostsController::class, 'index']);
Route::get('/hosts/{id}', [HostsController::class, 'show']);

// PROTÉGÉ
Route::middleware(['auth:sanctum', 'role:admin,host'])->group(function () {
    Route::post('/hosts', [HostsController::class, 'store']);
    Route::put('/hosts/{id}', [HostsController::class, 'update']);
    Route::delete('/hosts/{id}', [HostsController::class, 'destroy']);
});
