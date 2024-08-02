<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StarterController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(Request $request)
    {
        $details = $this->detailService->getByFilters(['СТАРТЕР'], []);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Стартеры',
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
