<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\CatalogPageService;
use Inertia\Response;

class BearingController extends Controller
{
    public function __construct(private readonly CatalogPageService $catalogPageService) {}

    public function index(DetailsFilterRequest $request): Response
    {
        return $this->catalogPageService->render(
            config('parts.filters.bearing'),
            __('title.bearing'),
            $this->catalogPageService->getClientBrands($request->validated('filter'))
        );
    }
}
