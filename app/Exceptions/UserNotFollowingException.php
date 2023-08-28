<?php

namespace App\Exceptions;

use Exception;

class UserNotFollowingException extends Exception
{
    public function render()
    {
        return response()->json(['message' => 'You are not following this user. Cannot unfollow.'], 409);
    }
}
