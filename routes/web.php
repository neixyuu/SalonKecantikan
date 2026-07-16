<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Pelanggan\AnnouncementController as PelangganAnnouncementController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\PaymentController as PelangganPaymentController;
use App\Http\Controllers\Pelanggan\ReservationController as PelangganReservationController;
use App\Http\Controllers\Pelanggan\StatusAkunController;
use App\Http\Controllers\Pelanggan\TreatmentController as PelangganTreatmentController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ReservationVerificationController;
use App\Http\Controllers\Admin\TreatmentController as AdminTreatmentController;
use App\Http\Controllers\Admin\UserVerificationController;
use Illuminate\Support\Facades\Route;

// ─── Landing Page (Publik) ────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Pengumuman – bisa diakses tanpa login
Route::get('/announcements', [PelangganAnnouncementController::class, 'index'])->name('announcements.index');

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Pelanggan ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:pelanggan'])->group(function () {

    // Status akun – bisa diakses meski belum verified
    Route::get('/status-akun', [StatusAkunController::class, 'index'])->name('status-akun');

    // Halaman yang butuh akun verified
    Route::middleware('verified_account')->group(function () {
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');

        Route::get('/treatments', [PelangganTreatmentController::class, 'index'])->name('treatments.index');

        Route::get('/reservations', [PelangganReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create', [PelangganReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [PelangganReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/{reservation}/reschedule', [PelangganReservationController::class, 'rescheduleForm'])->name('reservations.reschedule-form');
        Route::post('/reservations/{reservation}/reschedule-request', [PelangganReservationController::class, 'rescheduleRequest'])->name('reservations.reschedule-request');

        Route::get('/payments/create/{reservation}', [PelangganPaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [PelangganPaymentController::class, 'store'])->name('payments.store');
    });
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Verifikasi user
    Route::get('/users', [UserVerificationController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/verify', [UserVerificationController::class, 'verify'])->name('users.verify');

    // Verifikasi reservasi
    Route::get('/reservations', [ReservationVerificationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/verify', [ReservationVerificationController::class, 'verify'])->name('reservations.verify');
    Route::patch('/reservations/{reservation}/handle-reschedule', [ReservationVerificationController::class, 'handleRescheduleRequest'])->name('reservations.handle-reschedule');
    Route::patch('/reservations/{reservation}/cancel-by-admin', [ReservationVerificationController::class, 'cancelByAdmin'])->name('reservations.cancel-by-admin');

    // Verifikasi pembayaran
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');

    // CRUD Pengumuman
    Route::resource('/announcements', AdminAnnouncementController::class)->except(['show']);

    // CRUD Treatment
    Route::resource('/treatments', AdminTreatmentController::class)->except(['show']);
});
