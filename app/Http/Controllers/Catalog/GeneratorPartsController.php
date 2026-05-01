<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use Inertia\Inertia;
use Inertia\Response;

class GeneratorPartsController extends Controller
{
    public function __construct(protected DetailService $detailService, protected FirmService $firmService) {}

    public function index(string $category, DetailsFilterRequest $request): Response
    {
        $searchFilters = config("parts.filters.generator_parts.$category", []);

        return Inertia::render('Catalog/Index', [
            'details' => $this->detailService->getByFilters($searchFilters, 12),
            'title' => __("title.$category"),
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
