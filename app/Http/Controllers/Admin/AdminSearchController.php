<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Category;
use App\Factories\SearchServiceFactoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSearchRequest;
use Illuminate\Http\JsonResponse;

class AdminSearchController extends Controller
{
    private const int SEARCH_RESULTS_PER_PAGE = 50;

    private const int DEFAULT_PER_PAGE = 12;

    public function __construct(private readonly SearchServiceFactoryInterface $searchFactory) {}

    public function index(AdminSearchRequest $request): JsonResponse
    {
        $category = Category::from($request->validated('category'));
        $searchService = $this->searchFactory->create($category);
        $results = $searchService->search(
            (string) $request->validated('searchQ', ''),
            self::SEARCH_RESULTS_PER_PAGE,
            self::DEFAULT_PER_PAGE,
        );

        return response()->json([$category->value => $results]);
    }
}
