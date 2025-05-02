<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Category;
use App\Exceptions\InvalidCategoryException;
use App\Factories\SearchServiceFactoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSearchRequest;
use Illuminate\Support\Facades\Log;


class AdminSearchController extends Controller
{

    public function __construct(private readonly SearchServiceFactoryInterface $searchFactory)
    {}

    public function index(AdminSearchRequest $request)
    {
        $category = $request->validated('category');
        if (!Category::isValid($category)){
            throw new InvalidCategoryException($category);
        }
        $searchService = $this->searchFactory->create($category);
        $results = $searchService->search($request->validated('searchQ'), 1000 ,12);
        return ["$category" => $results];
    }
}
