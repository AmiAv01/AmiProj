<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CurrencyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyFormRequest;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminCurrencyController extends Controller
{
    public function __construct(protected CurrencyService $currencyService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/Currency/Index', ['currency' => $this->currencyService->getCurrency()]);
    }

    public function update(CurrencyFormRequest $request): JsonResponse
    {
        return response()->json(['success' => $this->currencyService->update(new CurrencyDTO($request->validated('currency')))]);
    }
}
