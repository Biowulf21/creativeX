<?php

namespace App\Http\Repositories\UserRepository;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Repositories\UserRepository\UserRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['message'=>'Logged in successfully', 'token'=> $token])->setStatusCode(200);
        }

        return response()->error('Something went wrong', 500);

    }

    public function signUp(array $data): JsonResponse
    {

        $password = $data['password'];
        $password = Hash::make($password);

        $user = new User;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $password;
        $user->account_handle = $data['account_handle'];
        $user->bio = $data['bio'];


        $user->save();
        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user'=> $user, 'token'=> $accessToken])->setStatusCode(200);
    }

    public function signOut(): JsonResponse
    {
        Auth::logout();
        return response()->json(['message'=>'Logged out successfully'])->setStatusCode(200);
    }

}


