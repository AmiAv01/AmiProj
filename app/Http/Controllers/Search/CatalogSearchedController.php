<?php

namespace App\Http\Controllers\Search;

use App\DTO\FilterDTO;
use App\DTO\SearchQueryDTO;
use App\Exceptions\NoResultsFoundException;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use App\Services\SearchService;
use Inertia\Inertia;
use Inertia\Response;

class CatalogSearchedController extends BaseSearchController
{
    public function __construct(protected SearchService $searchService, protected FirmService $firmService, protected DetailService $detailService)
    {
        parent::__construct($this->searchService);
    }

    /**
     * @throws NoResultsFoundException
     */
    public function index(SearchCatalogFormRequest $request): Response
    {
        $search = $this->getSearchQuery($request);

        return Inertia::render('SearchedCatalog/SearchedCatalog', [
            'details' => $this->searchService->getBySearchingWithPagination(new SearchQueryDTO($search)),
            'title' => __('Search by :search', ['search' => $search]),
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
