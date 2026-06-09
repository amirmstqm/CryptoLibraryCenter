<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

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
