<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function render()
    {
        return response()->json(['message'=>'The user cannot perform this action at this action as they cannot be found'], 404);
    }
}
