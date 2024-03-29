<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository\UserRepositoryInterface;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user_repository;
    public function __construct(UserRepositoryInterface $user_repository){
        $this->user_repository = $user_repository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Sign up a new user.
     */

    public function logInUser(LoginRequest $request): JsonResponse
    {
        return $this->user_repository->login($request->all());
    }

    public function logOutUser(): JsonResponse
    {
        return $this->user_repository->signOut();
    }

    //TODO: update params with proper sign up request class
    public function store(SignUpRequest $request): JsonResponse
    {
        return $this->user_repository->signUp($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
