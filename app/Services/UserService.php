<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

final class UserService
{
    public function getAll(int $perPage): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function getBySearching(string $search)
    {
        return User::where(function ($query) use ($search): void {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })->paginate(12)->withQueryString();
    }

    public function getById(int $id)
    {
        return User::where('id', '=', $id)->select(['name', 'email', 'isAdmin', 'id'])->first();
    }

    public function getUserFormula(int $id)
    {
        return Crypt::decrypt(User::where('id', '=', $id)->pluck('formula')->first());
    }

    public function destroy(int $id): bool
    {
        $user = User::find($id);
        if (! $user) {
            return false;
        }

        return $user->delete();
    }

    public function approveUser(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            $user->approved = 1;
            $user->save();

            return true;
        }

        return false;
    }

    public function update(UserDTO $dto): bool
    {
        return User::where('id', '=', $dto->userId)->update(['formula' => Crypt::encrypt($dto->formula)]);
    }
}
