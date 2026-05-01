<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
|
| These routes are used for monitoring and readiness checks.
| They should return quickly and not log to avoid spam.
|
*/

Route::group(['middleware' => 'api'], function () {

    /**
     * Liveness Probe: Is the application process running?
     * Used by Kubernetes/Docker to check if container is alive
     */
    Route::get('/health/live', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    })->withoutMiddleware(['api']);

    /**
     * Readiness Probe: Can the application handle requests?
     * Checks database, cache, queue connectivity
     */
    Route::get('/health/ready', function () {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
        ];

        $ready = collect($checks)->every(fn ($check) => $check === true);

        return response()->json([
            'status' => $ready ? 'ready' : 'not_ready',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $ready ? 200 : 503);
    });
});

/**
 * Check database connectivity
 */
if (! function_exists('checkDatabase')) {
    function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

/**
 * Check cache connectivity
 */
if (! function_exists('checkCache')) {
    function checkCache(): bool
    {
        try {
            Cache::put('health_check', true, 1);

            return Cache::get('health_check') === true;
        } catch (Exception $e) {
            return false;
        }
    }
}

/**
 * Check queue connectivity
 */
if (! function_exists('checkQueue')) {
    function checkQueue(): bool
    {
        try {
            // For database queue, check if jobs table exists
            if (config('queue.default') === 'database') {
                DB::table('jobs')->count();

                return true;
            }

            // For Redis queue, check connection
            if (config('queue.default') === 'redis') {
                Cache::store('redis')->put('queue_health', true, 1);

                return true;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
