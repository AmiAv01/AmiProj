<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

final class HealthController extends Controller
{
    public function live(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function ready(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
        ];

        $ready = collect($checks)->every(static fn (bool $check): bool => $check);

        return response()->json([
            'status' => $ready ? 'ready' : 'not_ready',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $ready ? 200 : 503);
    }

    private function checkDatabase(): bool
    {
        try {
            DB::select('SELECT 1');

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function checkCache(): bool
    {
        $key = 'health:'.Str::uuid();

        try {
            Cache::put($key, true, 10);

            return Cache::pull($key) === true;
        } catch (Throwable) {
            return false;
        }
    }

    private function checkQueue(): bool
    {
        try {
            Queue::connection()->size();

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
