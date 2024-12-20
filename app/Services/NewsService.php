<?php

namespace App\Services;

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

    public function store(array $request, int $adminId):News
    {
        Log::info($adminId);
        return News::create(['title' => $request['title'], 'date' => Carbon::now() ,'description' => $request['description'], 'author' => $adminId]);
    }

    public function getBySearching(string $search)
    {
        return News::where('title', 'like', "%$search%")->paginate(12)->withQueryString();
    }

    public function update(array $request, int $id):bool
    {
        $news = News::find($id);
        return $news->update(['title' => $request['title'], 'description' => $request['description']]);
    }

    public function destroy(int $id):bool
    {
        $news = News::find($id);
        return $news->delete();
    }
}
