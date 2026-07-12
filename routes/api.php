<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
