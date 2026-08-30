<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PublicContentController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\AdminController as ApiAdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController as ApiCartController;
use App\Http\Controllers\Api\V1\OrderController as ApiOrderController;
use App\Http\Controllers\Api\V1\RecoveryController;
use App\Http\Controllers\Admin\AdminApproveUserController;
use App\Http\Controllers\Admin\AdminCurrencyController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminSearchController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (): void {
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware(['web', 'guest']);
    Route::post('/auth/admin-login', [AuthController::class, 'adminLogin'])->middleware(['web', 'guest']);
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware(['web', 'guest']);
    Route::post('/auth/forgot-password', [RecoveryController::class, 'forgotPassword'])->middleware(['web', 'guest']);
    Route::post('/auth/reset-password', [RecoveryController::class, 'resetPassword'])->middleware(['web', 'guest']);
    Route::get('/home', [PublicContentController::class, 'home']);
    Route::get('/news', [PublicContentController::class, 'news']);
    Route::get('/catalog/search', [PublicContentController::class, 'search']);
    Route::get('/catalog/autocomplete', [PublicContentController::class, 'autocomplete']);
    Route::get('/catalog/{type}/{category?}', [PublicContentController::class, 'catalog']);
    Route::get('/products/{id}', [PublicContentController::class, 'product']);

    Route::middleware(['web', 'auth:sanctum'])->group(function (): void {
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/confirm-password', [RecoveryController::class, 'confirmPassword']);
        Route::post('/auth/verification-notification', [RecoveryController::class, 'verificationNotification'])->middleware('throttle:6,1');
        Route::get('/profile', [AccountController::class, 'profile']);
        Route::patch('/profile', [AccountController::class, 'update']);
        Route::put('/profile/password', [AccountController::class, 'password']);
        Route::delete('/profile', [AccountController::class, 'destroy']);
        Route::put('/cart/clear', [ApiCartController::class, 'clear']);
        Route::apiResource('cart', ApiCartController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names([
                'index' => 'api.cart.index',
                'store' => 'api.cart.store',
                'update' => 'api.cart.update',
                'destroy' => 'api.cart.destroy',
            ]);
        Route::apiResource('orders', ApiOrderController::class)
            ->only(['index', 'store', 'show'])
            ->names([
                'index' => 'api.orders.index',
                'store' => 'api.orders.store',
                'show' => 'api.orders.show',
            ]);

        Route::prefix('admin')->middleware('admin')->group(function (): void {
            Route::get('/dashboard', [ApiAdminController::class, 'dashboard']);
            Route::get('/details', [ApiAdminController::class, 'details']);
            Route::get('/news', [ApiAdminController::class, 'news']);
            Route::post('/news', [AdminNewsController::class, 'store']);
            Route::patch('/news/{postId}', [AdminNewsController::class, 'update']);
            Route::delete('/news/{postId}', [AdminNewsController::class, 'destroy']);
            Route::get('/orders', [ApiAdminController::class, 'orders']);
            Route::get('/orders/{id}', [ApiAdminController::class, 'order']);
            Route::put('/orders/{id}', [AdminOrderController::class, 'update']);
            Route::get('/users', [ApiAdminController::class, 'users']);
            Route::get('/users/{id}', [ApiAdminController::class, 'user']);
            Route::put('/users/{userId}', [AdminUserController::class, 'update']);
            Route::delete('/users/{userId}', [AdminUserController::class, 'destroy']);
            Route::put('/users/{id}/approve', [AdminApproveUserController::class, 'index']);
            Route::get('/currency', [ApiAdminController::class, 'currency']);
            Route::post('/currency', [AdminCurrencyController::class, 'update']);
            Route::get('/search', [AdminSearchController::class, 'index']);
        });
    });
});

// Health check endpoints - no authentication required
Route::get('/health/live', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
})->withoutMiddleware(['api']);

Route::get('/health/ready', function () {
    $checks = [
        'database' => checkDatabase(),
        'cache' => checkCache(),
        'queue' => checkQueue(),
    ];

    $ready = collect($checks)->every(fn ($check) => $check === true);

    return response()->json([
        'status' => $ready ? 'ready' : 'not_ready',
        'timestamp' => now()->toIso8601String(),
        'checks' => $checks,
    ], $ready ? 200 : 503);
})->withoutMiddleware(['api']);
