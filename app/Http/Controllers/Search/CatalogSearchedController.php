<?php

namespace App\Http\Controllers\Search;

use App\DTO\FilterDTO;
use App\DTO\SearchQueryDTO;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use App\Services\SearchService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class CatalogSearchedController extends BaseSearchController
{
    public function __construct(protected SearchService $searchService, protected FirmService $firmService, protected DetailService $detailService)
    {
        parent::__construct($this->searchService);
    }

    public function index(SearchCatalogFormRequest $request): Response
    {
        Log::info($request);
        $search = $this->getSearchQuery($request);
        $details = $this->searchService->getBySearchingWithPagination(new SearchQueryDTO($search));

        return Inertia::render('SearchedCatalog/SearchedCatalog', [
            'details' => $details,
            'title' => "Поиск по $search",
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
