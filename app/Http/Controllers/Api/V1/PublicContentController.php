<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\FilterDTO;
use App\DTO\SearchQueryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Http\Requests\SearchFormRequest;
use App\Services\CatalogMetadataService;
use App\Services\DetailService;
use App\Services\FirmService;
use App\Services\Interface\ProductViewServiceInterface;
use App\Services\NewsService;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;

class PublicContentController extends Controller
{
    public function __construct(
        private readonly DetailService $details,
        private readonly NewsService $news,
        private readonly FirmService $firms,
        private readonly CatalogMetadataService $metadata,
        private readonly SearchService $search,
        private readonly ProductViewServiceInterface $products,
    ) {}

    public function home(): JsonResponse
    {
        return response()->json([
            'data' => [
                'details' => $this->details->getAll(4),
                'posts' => $this->news->getAll(3),
            ],
        ]);
    }

    public function news(): JsonResponse
    {
        return response()->json(['data' => ['posts' => $this->news->getAll(12)]]);
    }

    public function catalog(DetailsFilterRequest $request, string $type, ?string $category = null): JsonResponse
    {
        $metadata = $this->metadata->getMetadata($type, $category);
        $details = $this->details->getByFilters($metadata->filters, 12);
        $details->withPath($this->catalogBrowserPath($type, $category));

        return response()->json([
            'data' => [
                'details' => $details,
                'title' => $metadata->title,
                'categories' => ['brands' => $this->firms->getAll()],
                'clientBrands' => $this->details->getClientBrands(new FilterDTO($request->validated('filter'))),
            ],
        ]);
    }

    public function search(SearchCatalogFormRequest $request): JsonResponse
    {
        $query = (string) $request->validated('searchQ');
        $details = $this->search->getBySearchingWithPagination(new SearchQueryDTO($query));
        $details->withPath('/catalog/search');

        return response()->json([
            'data' => [
                'details' => $details,
                'title' => __('Search by :search', ['search' => $query]),
                'categories' => ['brands' => $this->firms->getAll()],
                'clientBrands' => $this->details->getClientBrands(new FilterDTO($request->validated('filter'))),
            ],
        ]);
    }

    public function autocomplete(SearchFormRequest $request): JsonResponse
    {
        $query = (string) $request->validated('searchQ');

        return response()->json(['data' => [
            'details' => $this->search->getBySearching(new SearchQueryDTO($query)),
            'search' => $query,
        ]]);
    }

    public function product(string $id): JsonResponse
    {
        return response()->json(['data' => array_merge(
            $this->products->getViewDataForProduct($id),
            ['_component' => $this->products->resolveViewName()],
        )]);
    }

    private function catalogBrowserPath(string $type, ?string $category): string
    {
        return match ($type) {
            'generator' => '/catalog/generators',
            'starter' => '/catalog/starters',
            'bearing' => '/catalog/bearings',
            'other_details' => '/catalog/other',
            'starter_parts' => '/catalog/starter_parts/'.($category ?? ''),
            'generator_parts' => '/catalog/generator_parts/'.($category ?? ''),
            default => '/catalog',
        };
    }
}
