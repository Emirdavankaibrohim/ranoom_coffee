<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderController;

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::redirect('/','auth/login');

Route::middleware('admin')->group(function(){
    Route::get('auth/register',[AuthController::class,'registerPage'])->name('userRegister');
    Route::get('auth/login',[AuthController::class,'loginPage'])->name('userLogin');
});

// social login
Route::get('/auth/{provider}/redirect', [ProviderController::class,'redirect']);
Route::get('/auth/{provider}/callback', [ProviderController::class, 'callback']);

// Cart
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');


//payment print
Route::get('/order/print/{orderCode}', [OrderController::class, 'printPaymentSlip'])
    ->name('order.print');

Route::post('/admin/order/update-cooking-status/{id}', [OrderController::class, 'updateCookingStatus'])
    ->name('order.updateCookingStatus');

// Route::middleware(['user'])->group(function () {
//     Route::post('/user/order/confirm', [OrderController::class, 'confirm'])
//         ->name('order.confirm');
// });
