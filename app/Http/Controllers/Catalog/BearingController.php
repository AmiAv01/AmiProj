<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\Detail\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BearingController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(Request $request)
    {
        $details = $this->detailService->getByCategory(['ПОДШИПНИК', 'ПОДШИПНИК КОМПРЕССОРА КОНДИЦИОНЕРА'], []);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Подшипники',
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
