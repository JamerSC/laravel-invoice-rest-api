<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findByUsername($username)
    {
        // find the username using where() & find first() 
        return $this->user->where('username', $username)->first();
    }

    public function isEmailUnique($email)
    {
        // find the email using where() & check if doesntExist()
        return $this->user->where('email', $email)->doesntExist();
    }

    public function createUser(array $userData)
    {
        return $this->user->create($userData);
    }
}