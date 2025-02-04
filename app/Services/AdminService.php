<?php

namespace App\Services;

use App\DTO\SearchQueryDTO;

class AdminService
{
    public function chooseService(string $category): DetailService|NewsService|OrderService|UserService{
        return match($category){
            'details' => new DetailService(),
            'orders' => new OrderService(),
            'news' => new NewsService(),
            'users' => new UserService(),
        };
    }
}
