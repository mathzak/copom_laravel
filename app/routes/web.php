<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\System\RolesController;
use App\Http\Controllers\System\UnitsController;
use App\Http\Controllers\System\UsersController;
use Illuminate\Support\Facades\App;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('profile.')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('edit');
            Route::patch('/profile', 'update')->name('update');
            Route::delete('/profile', 'destroy')->name('destroy');
        });
    });

    Route::get('/apps', [AppsController::class, 'index'])->name('apps');

    Route::prefix('apps')->name('apps.')->group(function () {
        Route::middleware('verified', 'password.confirm')->group(function () {
            Route::controller(RolesController::class)->name('roles.')->group(function () {
                Route::get('/roles', 'index')->name('index')
                    ->defaults('label', 'Roles')
                    ->defaults('description', "Manage user access roles.")
                    ->defaults('icon', 'heroicon-c-identification');
                Route::get('/roles/create', 'create')->name('create');
                Route::post('/roles/create', 'store')->name('store');
                Route::get('/roles/edit/{id}', 'edit')->name('edit');
                Route::patch('/roles/edit/{id}', 'update')->name('update');
                Route::delete('/roles/destroy', 'destroy')->name('destroy');
                Route::delete('/roles/forcedestroy', 'forceDestroy')->name('forceDestroy');
                Route::post('/roles/restore', 'restore')->name('restore');
            });

            Route::controller(UnitsController::class)->name('units.')->group(function () {
                Route::get('/units', 'index')->name('index')
                    ->defaults('label', 'Units')
                    ->defaults('description', "Manage units registered in the system.")
                    ->defaults('icon', 'heroicon-o-building-office-2');
                Route::get('/units/create', 'create')->name('create');
                Route::post('/units/create', 'store')->name('store');
                Route::get('/units/edit/{id}', 'edit')->name('edit');
                Route::patch('/units/edit/{id}', 'update')->name('update');
                Route::delete('/units/destroy', 'destroy')->name('destroy');
                Route::delete('/units/forcedestroy', 'forceDestroy')->name('forceDestroy');
                Route::post('/units/restore', 'restore')->name('restore');
            });

            Route::controller(UsersController::class)->name('users.')->group(function () {
                Route::get('/users', 'index')->name('index')
                    ->defaults('label', 'Users')
                    ->defaults('description', "Manage users and their information registered in the system.")
                    ->defaults('icon', 'heroicon-o-user-circle');
                Route::get('/users/create', 'create')->name('create');
                Route::post('/users/create', 'store')->name('store');
                Route::get('/users/edit/{id}', 'edit')->name('edit');
                Route::patch('/users/edit/{id}', 'update')->name('update');
                Route::delete('/users/destroy', 'destroy')->name('destroy');
                Route::delete('/users/forcedestroy', 'forceDestroy')->name('forceDestroy');
                Route::post('/users/restore', 'restore')->name('restore');
            });
        });
    });

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::middleware('verified', 'password.confirm')->group(function () {
            Route::controller(ReportsController::class)->name('reports.')->group(function () {
                Route::get('/reports', 'index')->name('index');
                Route::get('/reports/create', 'create')->name('create');
                Route::post('/reports/create', 'store')->name('store');
                Route::get('/reports/edit/{user}', 'edit')->name('edit');
                Route::patch('/reports/edit/{user}', 'update')->name('update');
                Route::delete('/reports/destroy', 'destroy')->name('destroy');
                Route::delete('/reports/forcedestroy', 'forceDestroy')->name('forceDestroy');
                Route::post('/reports/restore', 'restore')->name('restore');
            });
        });
    });

    Route::get('/help', [HelpController::class, 'index'])->name('help');
});

require __DIR__ . '/auth.php';
