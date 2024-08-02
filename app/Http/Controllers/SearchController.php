<?php

namespace App\Http\Controllers;

use App\Services\DetailService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->input('searchQ');

        return [
            'details' => $this->detailService->getBySearching($search),
        ];
    }
}
