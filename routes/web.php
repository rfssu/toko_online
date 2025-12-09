<?php

use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

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

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');


// Authenticated Routes

// Ini memberi nama rute 'login' yang dicari Laravel
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

Route::middleware('guest')->group(function () {
    // Login (Yang sudah Anda buat)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    // --- TAMBAHKAN INI (OBAT ERROR REGISTER) ---
    // Menampilkan form daftar
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    // Memproses pendaftaran
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::middleware(['auth'])->group(function () {

    // --- TAMBAHKAN BARIS INI (OBAT ERROR LOGOUT) ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('dashboard', function () {
        return view('seller/pages/dashboard');
    })->name('dashboard');
    
    Route::resource('users', UserController::class);
    Route::resource('barangs', BarangController::class);
});


