<?php


namespace App\Http\Controllers\Admin;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Services\Cart\CartService;
use App\Services\OrderService;
use App\Services\UserService;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function __construct(protected UserService $userService, protected CartService $cartService, protected OrderService $orderService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/User/UserList', ['users' => $this->userService->getAll(12)]);
    }

    public function show(int $userId): Response
    {
        return Inertia::render('Admin/User/UserCard', [
            'user' => $this->userService->getById($userId),
            'cart' => $this->cartService->getCartItemsByUserId($userId),
            'orders' => $this->orderService->getByUserId($userId),
            'formula' => $this->userService->getUserFormula($userId)
        ]);
    }

    public function update(int $userId, AdminUserRequest $request)
    {
        return $this->userService->update(new UserDTO($userId, $request->validated('formula')));
    }

    public function destroy(int $userId)
    {
        return $this->userService->destroy($userId);
    }
}
