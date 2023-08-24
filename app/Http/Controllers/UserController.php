<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository\UserRepositoryInterface;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
    private $user_repository;
    public function __construct(UserRepositoryInterface $user_repository){
        $this->user_repository = $user_repository;
    }

    public function logInUser(LoginRequest $request): Response
    {
        return $this->user_repository->login($request->all());
    }
    public function logOutUser(): Response
    {
        return $this->user_repository->signOut();
    }

    //TODO: update params with proper sign up request class
    public function store(Request $request): Response
    {
        return $this->user_repository->signUp($request->all());
    }

