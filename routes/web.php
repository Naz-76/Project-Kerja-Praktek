<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Applicant\ApplicantDashboardController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ZONA PUBLIK (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/informasi', [PublicController::class, 'info'])->name('public.info');

// Autentikasi
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/auth/google', [AuthController::class, 'googleRedirect'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('auth.google-callback');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| ZONA PENDAFTAR (Siswa / Mahasiswa - Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::prefix('pendaftar')->group(function () {
        Route::get('/dashboard', [ApplicantDashboardController::class, 'index'])->name('applicant.dashboard');
        
        // Form Pendaftaran Single-Page
        Route::get('/pengajuan/baru', [App\Http\Controllers\Applicant\RegistrationController::class, 'create'])->name('applicant.registration.create');
        Route::post('/pengajuan/baru', [App\Http\Controllers\Applicant\RegistrationController::class, 'store'])->name('applicant.registration.store');

        Route::get('/pengajuan/{id}', [ApplicantDashboardController::class, 'showRegistration'])->name('applicant.my-registration.show');
        Route::get('/profil', [ApplicantDashboardController::class, 'profile'])->name('applicant.profile');
        Route::post('/profil', [ApplicantDashboardController::class, 'updateProfile'])->name('applicant.profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| ZONA ADMIN (Kepegawaian Diskominfo - Auth & Admin Role Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin', 'no-cache'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/check-updates', [AdminDashboardController::class, 'checkUpdates'])->name('admin.check-updates');

    // Verifikasi & Penempatan Bidang
    Route::get('/verifikasi', [VerificationController::class, 'index'])->name('admin.verification.index');
    Route::get('/verifikasi/{id}', [VerificationController::class, 'show'])->name('admin.verification.show');
    Route::post('/verifikasi/{id}/approve', [VerificationController::class, 'approve'])->name('admin.verification.approve');
    Route::post('/verifikasi/{id}/reject', [VerificationController::class, 'reject'])->name('admin.verification.reject');
    Route::post('/verifikasi/{id}/complete', [VerificationController::class, 'complete'])->name('admin.verification.complete');

    // Kelola Bidang & Kuota
    Route::get('/bidang-kuota', [DepartmentController::class, 'index'])->name('admin.departments.index');
    Route::post('/bidang-kuota', [DepartmentController::class, 'store'])->name('admin.departments.store');
    Route::put('/bidang-kuota/{id}', [DepartmentController::class, 'update'])->name('admin.departments.update');
    Route::delete('/bidang-kuota/{id}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');

    // Pembimbing Lapangan
    Route::post('/pembimbing-lapangan', [\App\Http\Controllers\Admin\FieldSupervisorController::class, 'store'])->name('admin.supervisors.store');
    Route::delete('/pembimbing-lapangan/{id}', [\App\Http\Controllers\Admin\FieldSupervisorController::class, 'destroy'])->name('admin.supervisors.destroy');

    // Rekapitulasi Laporan
    Route::get('/laporan', [ReportController::class, 'index'])->name('admin.reports.index');

    // Integrasi REST API (Data Sharing Sistem Eksternal)
    Route::get('/api-integrasi', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'index'])->name('admin.api.index');
    Route::post('/api-integrasi', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'store'])->name('admin.api.store');
    Route::patch('/api-integrasi/{id}/toggle', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'toggle'])->name('admin.api.toggle');
    Route::delete('/api-integrasi/{id}', [\App\Http\Controllers\Admin\ApiIntegrationController::class, 'destroy'])->name('admin.api.destroy');

    // Profil Admin
    Route::get('/profil', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profil', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('admin.profile.update');
});
