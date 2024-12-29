<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\DTO\SearchQueryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Http\Requests\SearchFormRequest;
use App\Services\DetailService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CatalogSearchedController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {

    }

    public function index(SearchCatalogFormRequest $request): Response
    {
        $search = $request->validated('searchQ');
        $details = $this->searchService->getBySearchingWithPagination(new SearchQueryDTO($search));

        return Inertia::render('SearchedCatalog/SearchedCatalog', [
            'details' => $details,
            'title' => "Поиск по $search",
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
