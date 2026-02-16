<?php

use Illuminate\Support\Facades\Route;

Route::get('/Salu.university-edu-pk-result-verification', [\App\Http\Controllers\Public\ResultVerificationController::class, 'showHome'])->name('public.home');
Route::post('/Salu.university-edu-pk-result-verification', [\App\Http\Controllers\Public\ResultVerificationController::class, 'verifyHome'])->name('public.home.verify');

Route::get('/verify', [\App\Http\Controllers\Public\ResultVerificationController::class, 'showForm'])->name('public.verify.form');
Route::post('/verify', [\App\Http\Controllers\Public\ResultVerificationController::class, 'verify'])->name('public.verify');

Route::get('/scan/{student:qr_token}', [\App\Http\Controllers\Public\ResultVerificationController::class, 'scan'])->name('public.scan');

Route::get('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/results/students', [\App\Http\Controllers\Admin\ResultController::class, 'studentsForSelection'])->name('results.students');

    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class)->except(['show']);
    Route::resource('years', \App\Http\Controllers\Admin\YearController::class)->except(['show']);
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->except(['show']);
    Route::resource('results', \App\Http\Controllers\Admin\ResultController::class)->except(['show']);
});
