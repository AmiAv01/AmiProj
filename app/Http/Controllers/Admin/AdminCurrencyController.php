<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CurrencyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyFormRequest;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;

class AdminCurrencyController extends Controller
{
    public function __construct(protected CurrencyService $currencyService) {}

    public function update(CurrencyFormRequest $request): JsonResponse
    {
        return response()->json(['data' => ['success' => $this->currencyService->update(new CurrencyDTO($request->validated('currency')))]]);
    }
}
