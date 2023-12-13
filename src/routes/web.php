<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Admin Access Routes
Route::group(['middleware' => 'isAdmin'], function () {
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{product}/delete', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/viewuser', [UserController::class, 'viewuser'])->name('user.viewuser');
    Route::get('/purchaseinfo', [PurchaseDetailController::class, 'index'])->name('purchase.index');

});

//User Access Routes
Route::group(['middleware' => 'isUser'], function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'addToCart'])->name('cart.addToCart');
    Route::delete('/cart', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/userpurchases', [PurchaseDetailController::class, 'userindex'])->name('purchase.userindex');
    Route::get('/cart/checkout', [PurchaseDetailController::class, 'checkout'])->name('checkout');
    Route::post('/stripe', [PurchaseDetailController::class, 'stripe'])->name('stripe');
    Route::get('/payment/success', [PurchaseDetailController::class, 'success'])->name('payment.success');
    Route::get('/payment/unsuccess', [PurchaseDetailController::class, 'unsuccess'])->name('payment.unsuccess');
    
});

Auth::routes();

//Common Access Routes
Route::group([], function () {
    Route::get('/', [ProductController::class, 'welcomeproducts'])->name('welcome');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/webhook', [PurchaseDetailController::class, 'webhook'])->name('webhook');


});
