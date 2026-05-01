<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\CatalogPageService;
use Inertia\Response;

class OtherDetailsController extends Controller
{
    public function __construct(private readonly CatalogPageService $catalogPageService) {}

    public function index(DetailsFilterRequest $request): Response
    {
        return $this->catalogPageService->render(
            config('parts.filters.other_details'),
            __('title.other_details'),
            $this->catalogPageService->getClientBrands($request->validated('filter'))
        );
    }
}
