<?php

namespace App\Services;

use App\Http\Requests\NewsFormRequest;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

final class UserService
{
    public function getAll(int $perPage):LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function getBySearching(string $search)
    {
        return User::where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate(12)->withQueryString();
    }

    public function getById(int $id){
        return User::findOrFail($id);
    }

    public function destroy(int $id):bool
    {
        $user = User::find($id);
        return $user->delete();
    }

    public function approveUser(int $id):bool{
        $user = User::find($id);
        if ($user){
            $user->approved = 1;
            $user->save();
            return true;
        }
        return false;
    }

    public function update(int $userId, string $formula):bool
    {
        return User::where('id', '=', $userId)->update(['formula' => $formula]);
    }
}
