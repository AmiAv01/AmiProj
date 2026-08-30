<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Exceptions\UserNotFoundException;
use App\Models\Cart;
use App\Models\News;
use App\Models\Order;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

final class UserService
{
    public function getAll(int $perPage): LengthAwarePaginator
    {
        return User::query()
            ->select(['id', 'name', 'email', 'isAdmin', 'approved'])
            ->paginate($perPage);
    }

    public function getBySearching(string $search, int $perPage): LengthAwarePaginator
    {
        return User::query()
            ->select(['id', 'name', 'email', 'isAdmin', 'approved'])
            ->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getById(int $id): User
    {
        $user = User::where('id', '=', $id)->select(['name', 'email', 'isAdmin', 'id'])->first();

        return $user ?? throw new UserNotFoundException($id);
    }

    public function getUserFormula(int $id): string
    {
        $formula = User::where('id', '=', $id)->value('formula');
        if (! is_string($formula)) {
            throw new UserNotFoundException($id);
        }

        return Crypt::decrypt($formula);
    }

    public function destroy(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            $user = User::query()->lockForUpdate()->find($id) ?? throw new UserNotFoundException($id);

            $cart = Cart::query()->where('user_id', $id)->lockForUpdate()->first();
            if ($cart) {
                $cart->items()->delete();
                $cart->delete();
            }

            Order::query()->where('created_by', $id)->update(['created_by' => null]);
            Order::query()->where('updated_by', $id)->update(['updated_by' => null]);
            News::query()->where('author', $id)->update(['author' => null]);

            return $user->delete();
        }, 3);
    }

    public function approveUser(int $id): bool
    {
        $user = User::find($id) ?? throw new UserNotFoundException($id);
        $user->approved = true;
        $user->save();

        return true;
    }

    public function update(UserDTO $dto): bool
    {
        $user = User::find($dto->userId) ?? throw new UserNotFoundException($dto->userId);

        return $user->update(['formula' => Crypt::encrypt($dto->formula)]);
    }
}
