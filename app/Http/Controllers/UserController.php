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

