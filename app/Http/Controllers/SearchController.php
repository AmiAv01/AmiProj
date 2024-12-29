<?php

namespace App\Http\Controllers;

use App\DTO\SearchQueryDTO;
use App\Http\Requests\SearchFormRequest;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {}

    public function index(SearchFormRequest $request): array
    {
        $search = $request->validated('searchQ');
        return [
            'details' => $this->searchService->getBySearching(new SearchQueryDTO($search)),
            'search' => $search,
        ];
    }
}
