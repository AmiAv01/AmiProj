<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CatalogSearchedController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(Request $request)
    {
        $search = $request->input('searchQ');
        $details = $this->detailService->getBySearchingWithPagination($search);
        Log::info($details);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => "Поиск по $search",
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
