<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'pt_BR'])) {
        abort(400);
    }
    
    App::setLocale($locale);

    return redirect()->back();
})->name('dashboard');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::name('profile.')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('edit');
            Route::patch('/profile', 'update')->name('update');
            Route::delete('/profile', 'destroy')->name('destroy');
        });
    });

    Route::prefix('apps')->name('apps.')->group(function () {
        Route::middleware('verified', 'password.confirm')->group(function () {
            Route::controller(UsersController::class)->name('users.')->group(function () {
                Route::get('/users', 'index')->name('index');
                Route::get('/users/create', 'create')->name('create');
                Route::post('/users/create', 'store')->name('store');
                Route::get('/users/edit/{user}', 'edit')->name('edit');
                Route::patch('/users/edit/{user}', 'update')->name('update');
                Route::delete('/users/destroy', 'destroy')->name('destroy');
                Route::delete('/users/forcedestroy', 'forceDestroy')->name('forceDestroy');
                Route::post('/users/restore', 'restore')->name('restore');
            });
        });
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::middleware('verified', 'password.confirm')->group(function () {
            Route::controller(UsersController::class)->name('users.')->group(function () {
                Route::get('/users', 'index')->name('index');
                Route::get('/users/create', 'create')->name('create');
                Route::post('/users/create', 'store')->name('store');
                Route::get('/users/edit/{user}', 'edit')->name('edit');
                Route::patch('/users/edit/{user}', 'update')->name('update');
                Route::delete('/users/destroy', 'destroy')->name('destroy');
                Route::delete('/users/forcedestroy', 'forceDestroy')->name('forceDestroy');
                Route::post('/users/restore', 'restore')->name('restore');
            });
        });
    });

    Route::get('/help', function () {
        return view('help');
    })->name('help');
});

require __DIR__.'/auth.php';
