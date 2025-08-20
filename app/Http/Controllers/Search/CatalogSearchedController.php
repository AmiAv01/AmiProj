<?php

namespace App\Http\Controllers\Search;

use App\DTO\FilterDTO;
use App\DTO\SearchQueryDTO;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use App\Services\Product\ProductViewService;
use App\Services\SearchService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class CatalogSearchedController extends BaseSearchController
{
    public function __construct(protected SearchService $searchService, protected FirmService $firmService, protected DetailService $detailService, protected ProductViewService $productViewService)
    {
        parent::__construct($this->searchService);
    }

    public function index(SearchCatalogFormRequest $request): Response
    {
        $search = $this->getSearchQuery($request);
        $searchResult = $this->searchService->getBySearchingWithPagination(new SearchQueryDTO($search));
        if ($searchResult->count() === 1) {
            $detail = $searchResult->first();
            $viewData = $this->productViewService->getViewDataForProduct($detail->id_or_appropriate_identifier);
            $viewName = $this->productViewService->resolveViewName();

            return Inertia::render($viewName, $viewData);
        }


        return Inertia::render('SearchedCatalog/SearchedCatalog', [
            'details' => $searchResult,
            'title' => "Поиск по $search",
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
