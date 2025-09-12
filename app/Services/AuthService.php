<?php

namespace App\Services;

use App\Jobs\SendWelcomeEmail;
use App\Repositories\UserRepository;

class AuthService 
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $userData)
    {
        if ($this->userRepository->isEmailUnique($userData["email"]))
        {
            // hash the password using bcrypt
            $userData['password'] = bcrypt($userData['password']);

            // create user using userRepository object database operation
            $user = $this->userRepository->createUser($userData);

            // return user
            return $user;
        }

        return null;
    }
}