<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - CryptoLibraryCenter
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [PageController::class, 'landing'])->name('landing');

// Page Routes (public)
Route::get('/libraries', [PageController::class, 'libraries'])->name('libraries');
Route::get('/libraries/details', [PageController::class, 'details'])->name('details');
Route::get('/about', [PageController::class, 'about'])->name('about');

// ── Auth Routes ───────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Admin Routes ──────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard',           [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/add',                 [AdminController::class, 'add'])->name('add');
    Route::post('/add',                [AdminController::class, 'store'])->name('store');
    Route::get('/browse',              [AdminController::class, 'browse'])->name('browse');
    Route::get('/edit/{id}',           [AdminController::class, 'edit'])->name('edit');
    Route::put('/edit/{id}',           [AdminController::class, 'update'])->name('update');
    Route::delete('/delete/{id}',      [AdminController::class, 'destroy'])->name('destroy');
});
