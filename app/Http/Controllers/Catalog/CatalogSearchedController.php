<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\DTO\SearchQueryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Http\Requests\SearchFormRequest;
use App\Services\DetailService;
use App\Services\FirmService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CatalogSearchedController extends Controller
{
    public function __construct(protected SearchService $searchService, protected FirmService $firmService)
    {

    }

    public function index(SearchCatalogFormRequest $request): Response
    {
        $search = $request->validated('searchQ');
        $details = $this->searchService->getBySearchingWithPagination(new SearchQueryDTO($search));

        return Inertia::render('SearchedCatalog/SearchedCatalog', [
            'details' => $details,
            'title' => "Поиск по $search",
            'categories' => ['brands' => $this->firmService->getAll()],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
