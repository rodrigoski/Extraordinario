<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==== Rutas de ADMINISTRADOR ====
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('appointments', AppointmentController::class);

        Route::get('/appointments/{appointment}/pdf', [AppointmentController::class, 'downloadPdf'])->name('appointments.pdf');
    });

    // ==== Rutas de STAFF ====
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'staff'])->name('dashboard');

        Route::resource('clients', ClientController::class)->only(['index', 'show']);
        Route::resource('appointments', AppointmentController::class)->only(['index', 'show', 'edit', 'update']);
        Route::resource('services', ServiceController::class)->only(['index', 'show']);

        Route::get('/appointments/{appointment}/pdf', [AppointmentController::class, 'downloadPdf'])->name('appointments.pdf');
    });

    // ==== Rutas de CLIENTE ====
    Route::middleware(['role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'cliente'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/appointments', [AppointmentController::class, 'myAppointments'])->name('appointments');
        Route::get('/appointments/create', [AppointmentController::class, 'createForClient'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'storeForClient'])->name('appointments.store');
        Route::get('/historial', [AppointmentController::class, 'clientHistory'])->name('historial');
        Route::get('/services', [ServiceController::class, 'listForClient'])->name('services');

        Route::get('/appointments/{appointment}/pdf', [AppointmentController::class, 'downloadPdf'])->name('appointments.pdf');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
