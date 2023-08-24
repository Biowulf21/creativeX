<?php

namespace App\Http\Repositories\UserRepository;

use Illuminate\Http\Response;

interface UserRepositoryInterface
{
    /**
     * @return Response
     * @param array<int,mixed> $data
     */

    public function login(array $data);

    /**
     * @return Response
     * @param array<int,mixed> $data
     */

    public function signUp(array $data);

    /**
     * @return void
     */
    public function singOut();
}
