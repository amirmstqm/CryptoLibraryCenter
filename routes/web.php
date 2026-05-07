<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SyncController;

/*
|--------------------------------------------------------------------------
| Web Routes - CryptoLibraryCenter
|--------------------------------------------------------------------------
*/

// -----------------------------------------------
// Page Routes
// -----------------------------------------------
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/libraries', [PageController::class, 'libraries'])->name('libraries');
Route::get('/libraries/details', [PageController::class, 'details'])->name('details');
Route::get('/about', [PageController::class, 'about'])->name('about');

// -----------------------------------------------
// API Routes — served from MySQL (used by JS)
// -----------------------------------------------
Route::prefix('api')->group(function () {
    // GET /api/libraries — all visible libraries
    // Supports: ?search=, ?language=, ?pqc=, ?pqc_supported=
    Route::get('/libraries', [SyncController::class, 'libraries'])->name('api.libraries');

    // GET /api/libraries/{id} — single library detail by Firebase ID
    Route::get('/libraries/{firebaseId}', [SyncController::class, 'libraryDetail'])->name('api.library.detail');
});

// -----------------------------------------------
// Sync Routes
// -----------------------------------------------

// POST /sync/firebase — webhook for instant sync (Firebase → MySQL)
Route::post('/sync/firebase', [SyncController::class, 'webhook'])
    ->name('sync.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// GET /sync/status — check sync health
Route::get('/sync/status', [SyncController::class, 'status'])->name('sync.status');
