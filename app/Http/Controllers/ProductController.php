<?php

namespace App\Http\Controllers;

use App\Services\Detail\DetailService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(int $id)
    {
        Log::channel('stderr')->info($id);
        $detail = $this->detailService->getById($id);
        Log::channel('stderr')->info($detail);

        return Inertia::render('Card/Index', ['detail' => $detail]);
    }
}
