<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function index(Request $request): array
    {
        $request->validate([
            'searchQ' => 'string|nullable|max:255',
        ]);
        $search = $request->input('searchQ');

        return [
            'details' => $this->searchService->getBySearching($search),
            'search' => $search,
        ];
    }
}
