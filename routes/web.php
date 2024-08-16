<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(DepartmentController::class)->prefix('departments')->group(function () {
        Route::group(['middleware' => ['permission:view departments']], function () {
            Route::get('/', 'index')->name('department');

            Route::post('/', 'store')->name('department.store')->middleware('permission:create departments');
            Route::put('/{department}', 'update')->name('department.update')->middleware('permission:edit departments');
            Route::delete('/{department}', 'delete')->name('department.delete')->middleware('permission:delete departments');
        });
    });

    Route::controller(RoleController::class)->prefix('roles')->group(function () {
        Route::group(['middleware' => ['permission:view roles']], function () {
            Route::get('/', 'index')->name('role');
            Route::get('/show/{role}', 'show')->name('role.show');
            
            Route::group(['middleware' => ['permission:create roles']], function () {
                Route::get('/create', 'create')->name('role.create');
                Route::post('/', 'store')->name('role.store');
            });

            Route::group(['middleware' => ['permission:edit roles']], function () {
                Route::get('/edit/{role}', 'edit')->name('role.edit');
                Route::put('/{role}', 'update')->name('role.update');
            });

            Route::delete('/{role}', 'delete')->name('role.delete')->middleware('permission:delete roles');
        });
    });

    Route::controller(LogController::class)->prefix('logs')->group(function () {
        Route::get('/activity-logs', 'index')->name('activity-logs')->middleware('permission:view activity logs');
    });
});

