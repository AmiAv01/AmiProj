<?php

namespace App\Http\Controllers\Admin;

use App\DTO\NewsPostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsFormRequest;
use App\Http\Resources\NewsPostResource;
use App\Services\NewsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminNewsController extends Controller
{
    public function __construct(protected NewsService $newsService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/News/NewsList', ['news' => $this->newsService->getAll(12)]);
    }

    public function store(NewsFormRequest $request): JsonResponse
    {
        $this->newsService->store(new NewsPostDTO($request->validated('title'), $request->validated('description'), Carbon::now()), auth()->id());

        return response()->json(['items' => NewsPostResource::collection($this->newsService->getAll(12))]);
    }

    public function update(NewsFormRequest $request, int $postId): JsonResponse
    {
        $this->newsService->update(new NewsPostDTO($request->validated('title'), $request->validated('description'), Carbon::now()), $postId);

        return response()->json(['items' => NewsPostResource::collection($this->newsService->getAll(12))]);
    }

    public function destroy(int $postId): JsonResponse
    {
        $this->newsService->destroy($postId);

        return response()->json(['items' => NewsPostResource::collection($this->newsService->getAll(12))]);
    }
}
