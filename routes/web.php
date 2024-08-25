<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::group(['middleware' => ['permission:view users']], function () {
            Route::get('/', 'index')->name('user');
            Route::get('/show/{user}', 'show')->name('user.show');

            Route::group(['middleware' => ['permission:create users']], function () {
                Route::get('/create', 'create')->name('user.create');
                Route::post('/', 'store')->name('user.store');
            });

            Route::group(['middleware' => ['permission:edit users']], function () {
                Route::get('/edit/{user}', 'edit')->name('user.edit');
                Route::put('/{user}', 'update')->name('user.update');
            });

            Route::delete('/{user}', 'delete')->name('user.delete')->middleware('permission:delete users');
        });
    });

    Route::controller(LogController::class)->prefix('logs')->group(function () {
        Route::get('/activity-logs', 'index')->name('activity-logs')->middleware('permission:view activity logs');
    });

    Route::controller(BankController::class)->prefix('banks')->group(function () {
        Route::group(['middleware' => ['permission:view banks']], function () {
            Route::get('/', 'index')->name('bank');

            Route::post('/', 'store')->name('bank.store')->middleware('permission:create banks');
            Route::put('/{bank}', 'update')->name('bank.update')->middleware('permission:edit banks');
            Route::delete('/{bank}', 'delete')->name('bank.delete')->middleware('permission:delete banks');
        });
    });

    Route::controller(AttendanceController::class)->prefix('attendances')->group(function () {
        Route::group(['middleware' => ['permission:view attendances']], function () {
            Route::get('/', 'index')->name('attendance');

            Route::group(['middleware' => ['permission:create attendances']], function () {
                Route::get('/create', 'create')->name('attendance.create');
                Route::post('/', 'store')->name('attendance.store');
            });

            Route::group(['middleware' => ['permission:edit attendances']], function () {
                Route::get('/edit/{attendance}', 'edit')->name('attendance.edit');
                Route::put('/{attendance}', 'update')->name('attendance.update');
            });

            Route::delete('/{attendance}', 'delete')->name('attendance.delete')->middleware('permission:delete attendances');
        });
    });
});

