<?php

namespace App\Http\Controllers\Search;

use App\DTO\SearchQueryDTO;
use App\Http\Requests\SearchFormRequest;
use Illuminate\Support\Facades\Log;

class SearchController extends BaseSearchController
{
    public function index(SearchFormRequest $request): array
    {
        Log::info($request);
        $search = $this->getSearchQuery($request);
        return [
            'details' => $this->searchService->getBySearching(new SearchQueryDTO($search)),
            'search' => $search,
        ];
    }
}
