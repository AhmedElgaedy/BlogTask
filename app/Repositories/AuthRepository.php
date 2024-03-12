<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }
}