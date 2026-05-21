<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - CryptoLibraryCenter
|--------------------------------------------------------------------------
*/

// -----------------------------------------------
// Authentication Routes
// -----------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// -----------------------------------------------
// Landing Page (public — redirect to /home if authenticated)
// -----------------------------------------------
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('libraries');
    }
    return app(\App\Http\Controllers\PageController::class)->landing();
})->name('landing');

// -----------------------------------------------
// Page Routes (Protected - Auth Required)
// -----------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/libraries', [PageController::class, 'libraries'])->name('libraries');
    Route::get('/libraries/details', [PageController::class, 'details'])->name('details');
    Route::get('/about', [PageController::class, 'about'])->name('about');
});

// -----------------------------------------------
// API Routes — served from MySQL (used by JS)
// -----------------------------------------------
Route::prefix('api')->middleware('auth')->group(function () {
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
