<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AdminApproveUserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(int $id): JsonResponse
    {
        return response()->json(['success' => $this->userService->approveUser($id)]);
    }
}
