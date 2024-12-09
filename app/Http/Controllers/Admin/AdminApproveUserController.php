<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;

class AdminApproveUserController extends Controller
{
    public function __construct(protected UserService $userService)
    {}

    public function index(int $id){
        return $this->userService->approveUser($id);
    }
}
