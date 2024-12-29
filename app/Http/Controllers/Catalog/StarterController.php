<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StarterController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(DetailsFilterRequest $request): Response
    {
        $details = $this->detailService->getByFilters(['СТАРТЕР'], 12);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Стартеры',
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
