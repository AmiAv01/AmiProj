<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use Inertia\Inertia;
use Inertia\Response;

class StarterPartsController extends Controller
{
    public function __construct(protected DetailService $detailService, protected FirmService $firmService) {}

    public function index(string $category, DetailsFilterRequest $request): Response
    {
        $filters = config("parts.filters.starter_parts.$category", []);

        return Inertia::render('Catalog/Index', [
            'details' => $this->detailService->getByFilters($filters, 12),
            'title' => __("title.starter_$category") ?: __("title.$category"),
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
