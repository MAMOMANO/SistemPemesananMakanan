<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Menampilkan semua menu
Route::get('/menu', [MenuController::class, 'index'])->name('menus.index'); // Menampilkan semua menu
Route::post('/menu', [MenuController::class, 'store'])->name('menus.store'); // Menyimpan menu baru
Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy'); // Menghapus menu

// Rute untuk login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk registrasi
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Register success route
Route::get('/register/success', function () {
    return view('auth.register_success');
})->name('register.success');

// Route untuk profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Middleware untuk mengamankan route order, cart, dan payment
Route::middleware(['auth'])->group(function () {
    // Route untuk cart
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/increase/{id}', [CartController::class, 'increaseQuantity'])->name('cart.increase');
    Route::patch('/cart/decrease/{id}', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Route untuk order
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Menampilkan daftar order
    Route::post('/order', [OrderController::class, 'store'])->name('order.store'); // Menyimpan order
    Route::get('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout'); // Menyimpan order

    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel'); // Membatalkan order

    // Route untuk payment
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store'); // Menyimpan payment
    Route::get('/payments/{orderId}', [PaymentController::class, 'index'])->name('payments.index');
    // Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index'); // Menampilkan daftar pembayaran
});
