<?php

use App\Http\Controllers\DepartmentController;
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
        Route::get('/', 'index')->name('department');
        Route::post('/', 'store')->name('department.store');
        Route::put('/{department}', 'update')->name('department.update');
        Route::delete('/{department}', 'delete')->name('department.delete');
    });

    Route::controller(RoleController::class)->prefix('roles')->group(function () {
        Route::get('/', 'index')->name('role');
        Route::get('/show/{role}', 'show')->name('role.show');
        Route::get('/create', 'create')->name('role.create');
        Route::post('/', 'store')->name('role.store');
        Route::get('/edit/{role}', 'edit')->name('role.edit');
        Route::put('/{role}', 'update')->name('role.update');
        Route::delete('/{role}', 'delete')->name('role.delete');
    });
});

