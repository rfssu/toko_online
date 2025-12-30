<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LabaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController; // Kept from original, not in provided snippet but used later
use App\Http\Controllers\HomeController; // Kept from original, not in provided snippet but used later
use App\Http\Controllers\ProfileController; // Kept from original, not in provided snippet but used later
use App\Http\Controllers\ForgotPasswordController; // Kept from original, not in provided snippet but used later

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('user.status')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
    Route::get('/tentang-kami', [HomeController::class, 'tentang'])->name('tentang');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('file/{file}/download', [FileController::class, 'download'])->name('file.download');
    Route::get('file/{file}/preview', [FileController::class, 'preview'])->name('file.preview');
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

        // Password Reset Routes
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });
    Route::middleware(['auth'])->group(function () {


        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [HomeController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [HomeController::class, 'avatar'])->name('profile.avatar');
        // BUYER - Cart Routes
        Route::post('/cart/add', [PesanController::class, 'addToCartAjax'])->name('cart.add');
        Route::get('/check-out', [PesanController::class, 'check_out'])->name('checkout');
        Route::post('/pesanan/konfirmasi', [PesanController::class, 'konfirmasi'])->name('pesanan.konfirmasi');
        Route::get('/pesanan/{id}/delete', [PesanController::class, 'delete'])->name('pesanan.delete');
        // BUYER - Order History
        Route::get('/history', [HistoryController::class, 'index'])->name('history');
        Route::get('/history/{id}', [HistoryController::class, 'detail'])->name('history.detail');

        // PAYMENT - Midtrans Integration
        Route::get('/payment/{pesanan_id}', [PaymentController::class, 'index'])->name('payment.index');
        Route::get('/payment/{pesanan_id}/status', [PaymentController::class, 'checkStatus'])->name('payment.status');
        Route::post('/payment/update-status', [PaymentController::class, 'updateStatus'])->name('payment.update-status');
        Route::middleware(['role:admin,seller'])->group(function () {
            Route::get('dashboard', [LabaController::class, 'index'])->name('dashboard');
            Route::get('dashboard/export', [LabaController::class, 'export'])->name('dashboard.export');
            Route::get('setting/profile', [ProfileController::class, 'index'])->name('setting.profile.index');
            Route::put('setting/profile/update/{id}', [ProfileController::class, 'update'])->name('setting.profile.update');



            // Product Import Routes
            Route::get('/barangs/template/download', [BarangController::class, 'downloadTemplate'])->name('barangs.template');
            Route::post('barangs/import', [BarangController::class, 'import'])->name('barangs.import');

            // QR CODE SCANNER ROUTES
            Route::get('/pesanans/scanner', [PesananController::class, 'showScanner'])->name('pesanans.scanner');
            Route::post('/pesanans/verify-qr', [PesananController::class, 'verifyQr'])->name('pesanans.verify-qr');
            Route::post('/pesanans/confirm-pickup-qr', [PesananController::class, 'confirmPickupFromQr'])->name('pesanans.confirm-pickup-qr');

            // Mark order as ready for pickup
            Route::post('/pesanans/{id}/mark-ready', [PesananController::class, 'markReady'])->name('pesanans.markReady');

            // Resource routes

            Route::resource('users', UserController::class)->middleware(['role:admin']);
            Route::resource('barangs', BarangController::class);
            Route::resource('pesanans', PesananController::class)->except(['create', 'store', 'destroy']);
        });
    });

    // Midtrans Notification (outside auth - accessed by Midtrans server)
    Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
});
