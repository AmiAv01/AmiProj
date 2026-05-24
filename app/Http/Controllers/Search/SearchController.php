<?php

namespace App\Http\Controllers\Search;

use App\DTO\SearchQueryDTO;
use App\Http\Requests\SearchFormRequest;

class SearchController extends BaseSearchController
{
    public function index(SearchFormRequest $request): array
    {
        $search = $this->getSearchQuery($request);

        return [
            'details' => $this->searchService->getBySearching(new SearchQueryDTO($search)),
            'search' => $search,
        ];
    }
}
