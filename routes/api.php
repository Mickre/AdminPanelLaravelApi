<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ImageController;

use App\Http\Controllers\App\ContentController;

Route::prefix('app')->group(function () {
    Route::get('blog', [ContentController::class, 'index']);
    Route::get('blog/{name}', [ContentController::class, 'show']);
    Route::get('blog/s/{search}', [ContentController::class, 'search']);
});

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LogoutController::class, 'logout']);
    /*
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.reset');
    */
    Route::get('user', [UserController::class, 'show']);
    Route::patch('user', [UserController::class, 'update']);

    Route::apiResource('blog', BlogController::class);
    Route::get('blog/s/{search}', [BlogController::class, 'search']);

    Route::get('image', [ImageController::class, 'index']);
    Route::post('image/{name}', [ImageController::class, 'store']);
    Route::delete('image/{image}', [ImageController::class, 'destroy']);
});
