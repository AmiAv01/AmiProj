<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Http\Requests\NewsFormRequest;
use App\Jobs\SendAdminApproveUserNotification;
use App\Jobs\SendAdminNewUserNotification;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

final class UserService
{
    public function getAll(int $perPage): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function getBySearching(string $search)
    {
        return User::where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate(12)->withQueryString();
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
        return $user->delete();
    }

    public function approveUser(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            $user->approved = 1;
            $user->save();
            SendAdminApproveUserNotification::dispatch($user);
            return true;
        }

        return false;
    }

    public function update(UserDTO $dto): bool
    {
        return User::where('id', '=', $dto->userId)->update(['formula' => Crypt::encrypt($dto->formula)]);
    }
}
