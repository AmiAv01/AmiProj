<?php

namespace App\Services;

use App\Models\News;

class NewsService
{
    public function getAll()
    {
        return News::join('user', 'news.author', '=', 'user.id')->select('news.id', 'news.title', 'news.date', 'news.description', 'user.name')->paginate(12);
    }

    public function destroy()
    {

    }

    public function getBySearching(string $search)
    {
        return News::where('title', 'like', "%$search%")->paginate(12)->withQueryString();
    }

    public function show()
    {
    }

    public function update()
    {

    }
}
