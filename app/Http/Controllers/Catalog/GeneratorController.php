<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class GeneratorController extends Controller
{
    public function __construct(protected DetailService $detailService, protected FirmService $firmService)
    {

    }

    public function index(DetailsFilterRequest $request): Response
    {
            return Inertia::render('Catalog/Index', [
                'details' => $this->detailService->getByFilters(['ГЕНЕРАТОР'], 12),
                'title' => 'Генераторы',
                'categories' => ['brands' => $this->firmService->getAll()],
                'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
            ]);

    }
}
