<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Cart Route (Public)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Payment Return
Route::get('/payment/vnpay/return', [CartController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::get('/payment/momo/return', [CartController::class, 'momoReturn'])->name('payment.momo.return');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-orders', [\App\Http\Controllers\MyOrderController::class, 'index'])->name('my-orders.index');
    Route::get('/my-orders/{order}', [\App\Http\Controllers\MyOrderController::class, 'show'])->name('my-orders.show');
    Route::post('/my-orders/{order}/cancel', [\App\Http\Controllers\MyOrderController::class, 'cancel'])->name('my-orders.cancel');

    // Checkout Routes (Requires Login)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('checkout.apply-voucher');
    
    // User Vouchers
    Route::get('/my-vouchers', [\App\Http\Controllers\UserVoucherController::class, 'index'])->name('my-vouchers.index');
    Route::post('/my-vouchers/save', [\App\Http\Controllers\UserVoucherController::class, 'saveVoucher'])->name('my-vouchers.save');

    // Product Reviews
    Route::post('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__ . '/auth.php';

// Search Route
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Catch-all SEO Product Route (Must be at the very bottom)
Route::get('/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('product.show');