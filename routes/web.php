<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

$spa = fn () => view('spa');

Route::get('/', $spa)->name('home');
Route::get('/dashboard', $spa)->name('dashboard');
Route::get('/news', $spa)->name('news.index');
Route::get('/info', $spa)->name('info.index');
Route::get('/desktop', $spa)->name('desktop.index');
Route::get('/catalog/search', $spa)->name('catalog-searched.index');
Route::get('/catalog/generators', $spa)->name('generator.index');
Route::get('/catalog/starters', $spa)->name('starter.index');
Route::get('/catalog/bearings', $spa)->name('bearing.index');
Route::get('/catalog/other', $spa)->name('other.index');
Route::get('/catalog/starter_parts/{category?}', $spa)->name('starter-parts.index');
Route::get('/catalog/generator_parts/{category}', $spa)->name('generator-parts.index');
Route::get('/catalog/product/{id}', $spa)->name('product.info');
Route::get('/catalog/starter_parts/product/{id}', $spa)->name('product.info.starter');
Route::get('/catalog/generator_parts/product/{id}', $spa)->name('product.info.generator');

Route::get('/login', $spa)->name('login');
Route::get('/register', $spa)->name('register');
Route::get('/forgot-password', $spa)->name('password.request');
Route::get('/reset-password/{token}', $spa)->name('password.reset');
Route::get('/confirm-password', $spa)->name('password.confirm');
Route::get('/verify-email', $spa)->name('verification.notice');
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::get('/email/verification-notification', $spa)->name('verification.send');
Route::get('/logout', $spa)->name('logout');
Route::get('/password', $spa)->name('password.update');
Route::get('/profile', $spa)->name('profile.edit');
Route::get('/cart', $spa)->name('cart.index');
Route::get('/order', $spa)->name('order.index');
Route::get('/order/{id}', $spa)->name('order.show');

Route::get('/admin/resource/login', $spa)->name('admin.login');
Route::get('/admin/resource/dashboard', $spa)->name('admin.dashboard');
Route::get('/admin/resource/details', $spa)->name('admin.details.index');
Route::get('/admin/resource/orders', $spa)->name('admin.orders.index');
Route::get('/admin/resource/orders/{id}', $spa)->name('admin.orders.show');
Route::get('/admin/resource/news', $spa)->name('admin.news.index');
Route::get('/admin/resource/users', $spa)->name('admin.users.index');
Route::get('/admin/resource/users/{id}', $spa)->name('admin.users.show');
Route::get('/admin/resource/currency', $spa)->name('admin.currency.index');

Route::fallback($spa);
