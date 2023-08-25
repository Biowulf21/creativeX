<?php

namespace App\Http\Repositories\UserRepository;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

interface UserRepositoryInterface
{
    public function login(array $data);
    public function signUp(array $data);
    public function signOut();
}
