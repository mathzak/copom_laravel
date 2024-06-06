<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/apps', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('apps');

Route::get('/reports', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('reports');

Route::get('/help', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('help');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/messages', [ProfileController::class, 'edit'])->name('messages.index');

    Route::get('/schedule', [ProfileController::class, 'edit'])->name('schedule.index');

    Route::get('/requirements', [ProfileController::class, 'edit'])->name('requirements.index');
});

require __DIR__ . '/auth.php';
