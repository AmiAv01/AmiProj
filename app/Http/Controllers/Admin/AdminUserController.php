<?php

namespace App\Http\Controllers\Admin;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function update(int $userId, AdminUserRequest $request): JsonResponse
    {
        return response()->json(['data' => ['success' => $this->userService->update(new UserDTO($userId, $request->validated('formula')))]]);
    }

    public function destroy(int $userId, Request $request): JsonResponse
    {
        if ($request->user()?->getKey() === $userId) {
            throw ValidationException::withMessages([
                'user' => [__('You cannot delete your own account from the administration panel.')],
            ]);
        }

        return response()->json(['data' => ['success' => $this->userService->destroy($userId)]]);
    }
}
