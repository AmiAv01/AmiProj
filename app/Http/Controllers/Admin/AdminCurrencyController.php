<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CurrencyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyFormRequest;
use App\Services\CurrencyService;
use Inertia\Inertia;
use Inertia\Response;

class AdminCurrencyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Currency/Index');
    }
    public function update(CurrencyFormRequest $request, CurrencyService $currencyService)
    {
        return ['currency' => $currencyService->update(new CurrencyDTO($request->validated('currency')))];
    }
}
