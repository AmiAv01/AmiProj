<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class GeneratorController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(Request $request): Response
    {
        try{
            $request->validate([
                'filter' => 'array',
            ]);
            return Inertia::render('Catalog/Index', [
                'details' => $this->detailService->getByFilters(['ГЕНЕРАТОР']),
                'title' => 'Генераторы',
                'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
            ]);
        } catch (\Throwable $exception) {
            Log::error('validation error');
        }

    }
}
