<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LineItemController;
use App\Http\Controllers\CartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('show');
});
Route::name('line_item.')->group(function () {
    Route::post('/line_item/create', [LineItemController::class, 'create'])->name('create');
    Route::post('/line_item/delete', [LineItemController::class, 'delete'])->name('delete');
});
Route::name('cart.')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('index');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/cart/success', [CartController::class, 'success'])->name('success');
});