<?php

namespace App\Exceptions;

use Exception;

class AlreadyFollowingException extends Exception
{
    public function render($request)
    {
        return response()->json(['message' => 'Already following this user.',], 409);
    }
}
