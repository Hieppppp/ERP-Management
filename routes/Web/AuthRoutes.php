<?php

namespace Routes\Web;

use App\Http\Controllers\WEB\Auth\ForgotPasswordController;
use App\Http\Controllers\WEB\Auth\LoginController;
use Illuminate\Support\Facades\Route;

class AuthRoutes
{
    public static function routes()
    {
        return Route::prefix('/')->group(function () {
            Route::get('/login', [LoginController::class, 'index'])->name('login');
            Route::post('/login', [LoginController::class, 'login']);
            Route::get('/logout', [LoginController::class, 'logout']);
            Route::get('/forgot-password', [ForgotPasswordController::class, 'index']);
            Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
            Route::get('/resend-email', [ForgotPasswordController::class, 'resendEmailView']);
            Route::post('/resend-email', [ForgotPasswordController::class, 'resendMail']);
            Route::get('/reset-password', [ForgotPasswordController::class, 'resetPasswordView'])->name('password.reset');
            Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
        });
    }
}
