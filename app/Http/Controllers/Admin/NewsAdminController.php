<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsFormRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class NewsAdminController extends Controller
{
    public function __construct(protected NewsService $newsService)
    {}

    public function index():Response
    {
        return Inertia::render('Admin/News/NewsList', ['news' => $this->newsService->getAll()]);
    }

    public function store(NewsFormRequest $request){
        $request->validated();
        return $this->newsService->store($request->all(), auth()->id());
    }

    public function update(NewsFormRequest $request,int $post)
    {

        $request->validated();
        Log::info($request);
        return $this->newsService->update($request->all(), $post);
    }

    public function destroy(int $post){
        return $this->newsService->destroy($post);
    }
}
