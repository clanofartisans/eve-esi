<?php

use Clanofartisans\EveEsi\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
    Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callbackk');
    Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
