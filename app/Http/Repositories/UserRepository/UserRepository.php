<?php

namespace App\Http\Repositories\UserRepository;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Repositories\UserRepository\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AlreadyLoggedInException;

class UserRepository implements UserRepositoryInterface
{

    public void function login()
    {

    }
    public function signUp()
    {

    }
    public function singOut()
    {

    }
}
