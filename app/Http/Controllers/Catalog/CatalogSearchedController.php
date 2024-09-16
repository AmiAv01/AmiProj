<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CatalogSearchedController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {

    }

    public function index(Request $request)
    {
        $search = $request->input('searchQ');
        $details = $this->searchService->getBySearchingWithPagination($search);
        Log::info($details);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => "Поиск по $search",
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
