<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderInvoiceController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bo-suu-tap/{slug}', [CollectionController::class, 'show'])->name('collection.show');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Cart Route (Public)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/stock-check', [CartController::class, 'checkStock'])->name('cart.stock-check');

// Payment Return
Route::get('/payment/vnpay/return', [CartController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::get('/payment/momo/return', [CartController::class, 'momoReturn'])->name('payment.momo.return');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Unified Account Routes
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AccountController::class, 'index'])->name('index');
        Route::get('/profile', [\App\Http\Controllers\AccountController::class, 'profile'])->name('profile');
        Route::patch('/profile', [\App\Http\Controllers\AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/addresses', [\App\Http\Controllers\AccountController::class, 'addresses'])->name('addresses');
        Route::get('/addresses/create', [\App\Http\Controllers\AccountController::class, 'createAddress'])->name('addresses.create');
        Route::post('/addresses', [\App\Http\Controllers\AccountController::class, 'storeAddress'])->name('addresses.store');
        Route::get('/addresses/{address}/edit', [\App\Http\Controllers\AccountController::class, 'editAddress'])->name('addresses.edit');
        Route::put('/addresses/{address}', [\App\Http\Controllers\AccountController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [\App\Http\Controllers\AccountController::class, 'destroyAddress'])->name('addresses.destroy');
        Route::patch('/addresses/{address}/default', [\App\Http\Controllers\AccountController::class, 'setDefaultAddress'])->name('addresses.default');
        
        // Security
        Route::get('/password', [\App\Http\Controllers\AccountController::class, 'password'])->name('password');
        Route::put('/password', [\App\Http\Controllers\AccountController::class, 'updatePassword'])->name('password.update');

        // Orders within Account
        Route::get('/orders', [\App\Http\Controllers\MyOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\MyOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [\App\Http\Controllers\MyOrderController::class, 'cancel'])->name('orders.cancel');

        // Vouchers within Account
        Route::get('/vouchers', [\App\Http\Controllers\UserVoucherController::class, 'index'])->name('vouchers.index');
        Route::post('/vouchers/save', [\App\Http\Controllers\UserVoucherController::class, 'saveVoucher'])->name('vouchers.save');
    });

    // Profile (Legacy Breeze) - Keeping for potential backward compatibility or dropping
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout Routes (Requires Login)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CartController::class, 'success'])->name('checkout.success');
    Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('checkout.apply-voucher');

    // Product Reviews
    Route::post('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__ . '/auth.php';

// Admin Custom Routes (Stand-alone from Filament)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders/{order}/invoice', [OrderInvoiceController::class, 'download'])->name('orders.invoice');
});

// Search Route
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Catch-all SEO Product Route (Must be at the very bottom)
Route::get('/{categorySlug}/{productSlug}', [ProductController::class, 'show'])->name('product.show');