<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación con Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// RUTA TEMPORAL - EJECUTAR UNA VEZ Y ELIMINAR
Route::get('/storage-link', function () {
    \Artisan::call('storage:link');

    return '✅ Storage link creado! <a href="/">Ir al inicio</a>';
});

// ruta principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// rutas del catalogo
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// rutas del carrito de compras
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::match(['put', 'patch', 'post'], '/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// ruta del chatbot FitBot
Route::post('/fitbot/chat', [App\Http\Controllers\FitBotController::class, 'chat'])->name('fitbot.chat');

// rutas de checkout - REQUIEREN EMAIL VERIFICADO
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [App\Http\Controllers\OrderController::class, 'process'])->name('checkout.process');
});

// rutas de pedidos - solo requieren login (sin verificación)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/order/confirmation/{id}', [App\Http\Controllers\OrderController::class, 'confirmation'])->name('order.confirmation');
});

// rutas de perfil de usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// rutas de autenticación (archivo separado)
require __DIR__.'/auth.php';

// rutas del panel de administracion (admin y bodeguero)
Route::prefix('admin')->middleware(['auth', 'role:admin|bodeguero'])->group(function () {

    // dashboard - accesible para admin y bodeguero
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // rutas de productos - accesible para admin y bodeguero
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    // rutas de pedidos - accesible para admin y bodeguero
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
});

// rutas exclusivas para administradores
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    // rutas de usuarios - SOLO ADMIN
    Route::resource('users', AdminUserController::class)->names('admin.users');

    // rutas de categorías - SOLO ADMIN
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');

    // rutas de reportes - SOLO ADMIN
    Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('admin.reports.sales');
});
