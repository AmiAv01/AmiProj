<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DetailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Catalog\BearingController;
use App\Http\Controllers\Catalog\CatalogSearchedController;
use App\Http\Controllers\Catalog\GeneratorController;
use App\Http\Controllers\Catalog\GeneratorPartsController;
use App\Http\Controllers\Catalog\OtherDetailsController;
use App\Http\Controllers\Catalog\StarterController;
use App\Http\Controllers\Catalog\StarterPartsController;
use App\Http\Controllers\Info\InfoController;
use App\Http\Controllers\News\NewsController;
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

Route::middleware('auth')->resource('/cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);

Route::group(['prefix' => 'admin', 'middleware' => 'RedirectIfAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // details route
    Route::get('/details', [DetailController::class, 'index'])->name('admin.details.index');
    Route::post('/details/store', [DetailController::class, 'store'])->name('admin.details.store');
    Route::put('/details/update/{id}', [DetailController::class, 'update'])->name('admin.details.update');
    Route::delete('/details/delete/{dt_id}', [DetailController::class, 'delete'])->name('admin.details.delete');

});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
