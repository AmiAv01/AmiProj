<?php

namespace App\Http\Controllers\Admin;

use App\DTO\NewsPostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsFormRequest;
use App\Http\Resources\NewsPostResource;
use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AdminNewsController extends Controller
{
    public function __construct(protected NewsService $newsService)
    {}

    public function index():Response
    {
        return Inertia::render('Admin/News/NewsList', ['news' => $this->newsService->getAll(12)]);
    }

    public function store(NewsFormRequest $request): array{
        $post = $this->newsService->store(new NewsPostDTO($request->validated('title'), $request->validated('description'), Carbon::now()), auth()->id());
        return ['items' => $this->newsService->getAll(12)];
    }

    public function update(NewsFormRequest $request, int $postId): array
    {
        $this->newsService->update(new NewsPostDTO($request->validated('title'), $request->validated('description'), Carbon::now()), $postId);
        return ['items' => $this->newsService->getAll(12)];
    }

    public function destroy(int $postId): array{
        $this->newsService->destroy($postId);
        return ['items' => $this->newsService->getAll(12)];
    }
}
