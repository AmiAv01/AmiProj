<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

function checkDatabase(): bool
{
    try {
        DB::connection()->getPdo();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function checkCache(): bool
{
    try {
        Cache::put('health_check', true, 1);

        return Cache::get('health_check') === true;
    } catch (Exception $e) {
        return false;
    }
}

function checkQueue(): bool
{
    try {
        if (config('queue.default') === 'database') {
            DB::table('jobs')->count();

            return true;
        }

        if (config('queue.default') === 'redis') {
            Cache::store('redis')->put('queue_health', true, 1);

            return true;
        }

        return true;
    } catch (Exception $e) {
        return false;
    }
}
