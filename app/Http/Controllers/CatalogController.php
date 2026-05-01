<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\CatalogMetadataService;
use App\Services\DetailService;
use App\Services\FirmService;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function __construct(
        private readonly DetailService $detailService,
        private readonly FirmService $firmService,
        private readonly CatalogMetadataService $metadataService
    ) {}

    public function index(DetailsFilterRequest $request, string $type, ?string $category = null): Response
    {
        $metadata = $this->metadataService->getMetadata($type, $category);

        $filterDto = new FilterDTO($request->validated('filter'));

        return Inertia::render('Catalog/Index', [
            'details'      => $this->detailService->getByFilters($metadata->filters, 12),
            'title'        => $metadata->title,
            'categories'   => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands($filterDto),
        ]);
    }
}
