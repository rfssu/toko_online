<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PaymentController;

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
});
Route::middleware(['auth'])->group(function () {


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [HomeController::class, 'updatePassword'])->name('profile.password');
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
    Route::middleware(['role:admin,seller'])->group(function () {
        Route::get('dashboard', function () {
            return view('seller/pages/dashboard');
        })->name('dashboard');


        Route::resource('users', UserController::class);
        Route::resource('barangs', BarangController::class);
        Route::resource('pesanans', PesananController::class);
    });
});

// Midtrans Notification (outside auth - accessed by Midtrans server)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
