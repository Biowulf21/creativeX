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

    public function login(array $data): Response
    {
        if(Auth::check())
        {
          throw new AlreadyLoggedInException;
        }

        if (!Auth::attempt($data))
        {
            throw new InvalidCredentialsException;
        }

        $user = Auth::user();

        if ($user instanceof \App\Models\User)
        {
        $token = $user->createToken('auth_token')->accessToken;
        return response(status: 200)->json(['message'=>'Logged in successfully', 'token'=> $token]);
        }

        return response()->error('Something went wrong', 500);

    }
    public function signUp()
    {

    }
    public function singOut()
    {

    }
}
