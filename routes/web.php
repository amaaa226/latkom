<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'indexRooms'])->name('rooms.index');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/reservations/history', [AuthController::class, 'historyReservation'])->name('reservations.history');
    Route::get('/reservations/create/{room_id}', [AuthController::class, 'createReservation'])->name('reservations.create');
    Route::post('/reservations', [AuthController::class, 'storeReservation'])->name('reservations.store');
    Route::get('/reservations/confirmation/{id}', [AuthController::class, 'confirmationReservation'])->name('reservations.confirmation');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/rooms', [AuthController::class, 'adminIndex'])->name('admin.index');
    Route::post('/rooms', [AuthController::class, 'storeRoom'])->name('admin.rooms.store');
    Route::put('/rooms/{room}', [AuthController::class, 'updateRoom'])->name('admin.rooms.update');
    Route::delete('/rooms/{room}', [AuthController::class, 'destroyRoom'])->name('admin.rooms.destroy');

    Route::get('/reservations', [AuthController::class, 'adminReservations'])->name('admin.reservations.index');
    Route::patch('/reservations/{id}/confirm', [AuthController::class, 'confirmPayment'])->name('admin.reservations.confirm');
});
