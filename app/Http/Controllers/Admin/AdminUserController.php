<?php

namespace App\Http\Controllers\Admin;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AdminUserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function update(int $userId, AdminUserRequest $request): JsonResponse
    {
        return response()->json(['data' => ['success' => $this->userService->update(new UserDTO($userId, $request->validated('formula')))]]);
    }

    public function destroy(int $userId): JsonResponse
    {
        return response()->json(['data' => ['success' => $this->userService->destroy($userId)]]);
    }
}
