<?php

namespace App\Exceptions;

use Exception;

class AlreadyFollowingException extends Exception
{
    public function render()
    {
        return response()->json(['message' => 'You are already following this user', 'status' => 'error'], 409);
    }
}
