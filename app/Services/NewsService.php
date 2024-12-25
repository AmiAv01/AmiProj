<?php

namespace App\Services;

use App\DTO\NewsPostDTO;
use App\Http\Requests\NewsFormRequest;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

final class NewsService
{
    public function getAll($perPage):LengthAwarePaginator
    {
        return News::join('user', 'news.author', '=', 'user.id')->select(['news.id', 'news.title', 'news.date', 'news.description', 'user.name'])->paginate($perPage);
    }

    public function store(NewsPostDTO $dto, int $adminId):News
    {
        return News::create(['title' => $dto->title, 'date' => $dto->dateTime ,'description' => $dto->description, 'author' => $adminId]);
    }

    public function getBySearching(string $search)
    {
        return News::where('title', 'like', "%$search%")->paginate(12)->withQueryString();
    }

    public function update(NewsPostDTO $dto, int $id):bool
    {
        $news = News::find($id);
        return $news->update(['title' => $dto->title, 'description' => $dto->description]);
    }

    public function destroy(int $id):bool
    {
        $news = News::find($id);
        return $news->delete();
    }
}
