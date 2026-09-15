<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

// Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes (Halaman Login & Reset Password)
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // Forgot Password & Reset Code Routes (Pastikan mengarah ke ForgotPasswordController)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // Reset Password Routes (Pastikan mengarah ke ResetPasswordController)
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.code');
    Route::post('/reset-password/verify-code', [ResetPasswordController::class, 'verifyResetCode'])->name('password.verify.code');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated Routes (Hanya untuk User yang Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Data Karyawan (CRUD) & Slip Gaji
    Route::resource('karyawan', KaryawanController::class);
    Route::get('/karyawan-captcha', [KaryawanController::class, 'refreshCaptcha'])->name('karyawan.captcha');
    Route::get('/karyawan/{id}/slip', [KaryawanController::class, 'slip'])->name('karyawan.slip');
    Route::match(['get', 'post'], '/karyawan/{id}/slip/cetak', [KaryawanController::class, 'cetakSlip'])->name('karyawan.slip.cetak');
    Route::post('/karyawan/{id}/slip/email', [KaryawanController::class, 'kirimEmail'])->name('karyawan.slip.email');
});