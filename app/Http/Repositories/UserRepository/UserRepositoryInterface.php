<?php

namespace App\Http\Repositories\UserRepository;

interface UserRepositoryInterface
{
    public function login();
    public function signUp();
    public function singOut();
}
