<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\RegistrationController as EmployeeRegistrationController;
use App\Http\Controllers\Employee\WorkshopController as EmployeeWorkshopController;
use Illuminate\Support\Facades\Route;
Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('workshops', AdminWorkshopController::class);
    });

    Route::middleware('role:employee')->prefix('employee')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
            ->name('employee.dashboard');
        Route::get('/workshops', [EmployeeWorkshopController::class, 'index'])
            ->name('employee.workshops.index');
        Route::post('/workshops/{workshop}/registrations', [EmployeeRegistrationController::class, 'store'])
            ->name('employee.workshops.registrations.store');
    });
});

require __DIR__ . '/settings.php';
