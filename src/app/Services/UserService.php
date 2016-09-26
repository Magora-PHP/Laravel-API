<?php


namespace App\Services;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    const USERS = User::class;

    static public function getAllUsers(): Collection
    {
        return (self::USERS)::all();
    }

    static public function store($data): User
    {
        $user = new User();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->fill($data);
        $user->save();
        return $user;
    }
}
