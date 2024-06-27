<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\Detail\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogSearchedController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(Request $request)
    {
        $search = $request->input('searchQ');
        $details = $this->detailService->getBySearching($search);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => `Поиск по $search`,
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
