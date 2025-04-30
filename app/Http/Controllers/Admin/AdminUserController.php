<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use app\Services\Cart\CartService;
use App\Services\OrderService;
use App\Services\UserService;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function __construct(protected UserService $userService, protected CartService $cartService, protected OrderService $orderService)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Admin/User/UserList', ['users' => $this->userService->getAll(12)]);
    }

    public function show(int $user): Response
    {
        return Inertia::render('Admin/User/UserCard', ['user' => $this->userService->getById($user), 'cart' => $this->cartService->getCartItems($user),
            'orders' => $this->orderService->getByUserId($user), 'formula' => $this->userService->getUserFormula($user)]);
    }

    public function update(int $user, AdminUserRequest $request)
    {
        return $this->userService->update($user, $request->validated('formula'));
    }

    public function destroy(int $post)
    {
        return $this->userService->destroy($post);
    }
}
