<?php

namespace App\Services;

use App\DTO\FilterDTO;
use Inertia\Response;
use Inertia\ResponseFactory;

final readonly class CatalogPageService
{
    public function __construct(
        private DetailService   $detailService,
        private FirmService     $firmService,
        private ResponseFactory $responseFactory
    ) {}

    /**
     * @param  array<int, string>  $categories
     * @param  array<int, string>|null  $clientBrands
     */
    public function render(array $categories, string $title, ?array $clientBrands, int $perPage = 12): Response
    {
        return $this->responseFactory->render('Catalog/Index', [
            'details' => $this->detailService->getByFilters($categories, $perPage),
            'title' => $title,
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $clientBrands,
        ]);
    }

    public function getClientBrands(?array $filter): ?array
    {
        return $this->detailService->getClientBrands(new FilterDTO($filter));
    }
}
