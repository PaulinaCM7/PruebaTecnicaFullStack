<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\Admin\MediaAdminController;
use App\Http\Controllers\Api\Admin\AdminReservationController;
use App\Http\Controllers\Api\Admin\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::get('/media', [MediaController::class, 'index']);
    Route::get('/media/{id}', [MediaController::class, 'show']);
    Route::get('/media-tipos', [MediaController::class, 'tipos']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/history', [ReservationController::class, 'history']);
});

Route::middleware(['web', 'auth:sanctum', \App\Http\Middleware\AdminOnly::class])
    ->prefix('admin')
    ->group(function () {
        Route::apiResource('media', MediaAdminController::class)->parameters([
            'media' => 'media',
        ]);
        Route::apiResource('usuarios', \App\Http\Controllers\Api\Admin\UserController::class);
        Route::post('media/upload', [MediaAdminController::class, 'uploadImage']);
        Route::get('reservations', [AdminReservationController::class, 'index']);
        Route::get('perfil/{id}', [UserProfileController::class, 'show']);
});
