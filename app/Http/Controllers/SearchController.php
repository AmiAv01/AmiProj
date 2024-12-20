<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFormRequest;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function index(SearchFormRequest $request): array
    {
        $search = $request->input('searchQ');

        return [
            'details' => $this->searchService->getBySearching($search),
            'search' => $search,
        ];
    }
}
