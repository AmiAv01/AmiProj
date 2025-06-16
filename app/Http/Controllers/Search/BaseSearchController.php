<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseSearchController extends Controller
{
    public function __construct(protected SearchService $searchService) {}

    protected function getSearchQuery(FormRequest $request): string
    {
        return $request->validated('searchQ');
    }
}
