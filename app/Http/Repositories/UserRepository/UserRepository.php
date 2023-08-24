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

    public function signUp(array $data): Response
    {
        $password = $data['password'];
        $password = Hash::make($password);

        $user = new User;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $password;

        $user->save();
        $accessToken = $user->createToken('auth_token')->accessToken;

        return response()->json(['user'=> $user, 'token'=> $accessToken])->status(200);
    }

    public function signOut(): Response
    {
        Auth::logout();
        return response('Logged out successfully', 200);
    }

}


