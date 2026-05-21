<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Public routes (home, products, about, contact, auth)
| User routes with auth middleware (cart, checkout, payment, orders, account)
| Admin routes with admin middleware (dashboard, products, orders, users, messages, reports)
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// CSRF Token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Home page with featured products (Requirements: 9.1, 19.2)
Route::get('/', [HomeController::class, 'index'])->name('home');

// About page (Requirements: 9.2)
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Contact page (Requirements: 9.3, 9.4)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Product routes - public (Requirements: 2.1, 2.2, 2.3, 2.4, 11.1, 11.2)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// ============================================================================
// GUEST ROUTES (Authentication - only accessible when not logged in)
// ============================================================================

// Guest routes (Requirements: 1.1, 1.2, 1.3, 1.4, 14.1, 14.2, 14.3, 14.4)
Route::middleware('guest')->group(function () {
    // Registration (Requirements: 1.1, 1.2)
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Login (Requirements: 1.3, 1.4)
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Password Reset (Requirements: 14.1, 14.2, 14.3, 14.4)
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ============================================================================
// AUTHENTICATED USER ROUTES
// ============================================================================

// Authenticated routes (Requirements: 1.5, 3.x, 4.x, 5.x, 8.x, 12.x, 15.x)
Route::middleware('auth')->group(function () {
    // Logout (Requirements: 1.5)
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Cart routes (Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 15.1)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');

    // Checkout routes (Requirements: 4.1, 4.2, 4.3, 4.6, 15.2)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

    // Payment routes (Requirements: 4.3, 4.4, 4.5)
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');

    // Orders routes (Requirements: 5.1, 5.2, 5.3, 12.1, 12.2, 12.3, 15.3)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');

    // Invoice routes (Requirements: 8.1, 8.2, 8.4)
    Route::get('/invoices/{order}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{order}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Account routes (Requirements: 1.1)
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================

// Admin routes (Requirements: 6.1, 7.1, 10.1, 13.1, 16.1, 20.1)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard route (Requirements: 10.1, 10.2, 10.3)
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Admin Product routes (Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 11.3, 17.1, 19.1)
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-availability', [\App\Http\Controllers\Admin\ProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
    Route::post('/products/{product}/toggle-featured', [\App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Admin Invoice routes (Requirements: 8.2, 8.3, 8.4)
    Route::get('/invoices/{order}', [\App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{order}/print', [\App\Http\Controllers\Admin\InvoiceController::class, 'print'])->name('invoices.print');

    // Admin Order routes (Requirements: 7.1, 7.2, 7.3, 7.4, 7.5)
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');

    // Admin User routes (Requirements: 13.1, 13.2)
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');

    // Admin Message routes (Requirements: 16.1, 16.2, 16.3)
    Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{message}/read', [\App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('messages.mark-as-read');

    // Admin Report routes (Requirements: 20.1, 20.2, 20.3)
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
});
