<?php

use App\Http\Controllers\Admin\AdminApproveUserController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCurrencyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Cart\ClearCartController;
use App\Http\Controllers\Desktop\DesktopController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminSearchController;
use App\Http\Controllers\Admin\AdminDetailController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Catalog\BearingController;
use App\Http\Controllers\Catalog\CatalogSearchedController;
use App\Http\Controllers\Catalog\GeneratorController;
use App\Http\Controllers\Catalog\GeneratorPartsController;
use App\Http\Controllers\Catalog\OtherDetailsController;
use App\Http\Controllers\Catalog\StarterController;
use App\Http\Controllers\Catalog\StarterPartsController;
use App\Http\Controllers\Info\InfoController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
Route::get('/', [UserController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::get('/api/search', [SearchController::class, 'index']);

Route::group(['prefix' => 'catalog'], (function () {
    Route::get('/search', [CatalogSearchedController::class, 'index']);
    Route::get('/api/search', [SearchController::class, 'index']);
    Route::get('/generators', [GeneratorController::class, 'index'])->name('generator.index');
    Route::get('/starters', [StarterController::class, 'index'])->name('starter.index');
    Route::get('/bearings', [BearingController::class, 'index'])->name('bearing.index');
    Route::get('/starter_parts/{category?}', [StarterPartsController::class, 'index']);
    Route::get('/generator_parts/{category}', [GeneratorPartsController::class, 'index']);
    Route::get('/other', [OtherDetailsController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'index'])->name('product.info');
    Route::get('/starter_parts/product/{id}', [ProductController::class, 'index'])->name('product.info');
    Route::get('/generator_parts/product/{id}', [ProductController::class, 'index'])->name('product.info');
}));

Route::get('/info', [InfoController::class, 'index'])->name('info.index');
Route::get('/desktop', [DesktopController::class, 'index'])->name('desktop.index');

Route::middleware('auth')->resource('/cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
Route::middleware('auth')->put('/clear', [ClearCartController::class, 'index'])->name('cart.clear');
Route::middleware('auth')->resource('/order', OrderController::class)->only(['index', 'store', 'update', 'show']);

Route::group(['prefix' => 'admin', 'middleware' => 'RedirectIfAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // details route
    Route::get('/details', [AdminDetailController::class, 'index'])->name('admin.details.index');
    Route::post('/details/store', [AdminDetailController::class, 'store'])->name('admin.details.store');
    Route::put('/details/update/{id}', [AdminDetailController::class, 'update'])->name('admin.details.update');
    Route::delete('/details/delete/{dt_id}', [AdminDetailController::class, 'delete'])->name('admin.details.delete');

    // orders route
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

    // news route
    Route::get('/news', [AdminNewsController::class, 'index'])->name('admin.news.index');
    Route::post('/news/store', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::patch('/news/{post}', [AdminNewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{post}', [AdminNewsController::class, 'destroy'])->name('admin.news.delete');
    Route::get('/api/search', [AdminSearchController::class, 'index'])->name('admin.search.index');

    //users route
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::delete('/users',[AdminUserController::class, 'destroy'])->name('admin.users.delete');

    //currency route
    Route::get('currency', [AdminCurrencyController::class, 'index'])->name('admin.currency.index');
    Route::post('currency', [AdminCurrencyController::class, 'update'])->name('admin.currency.update');

    Route::put('/approve/{user}', [AdminApproveUserController::class, 'index'])->name('admin.approve.index');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
