<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function render($request)
    {

        return response()->json(['message' => 'Invalid credentials. User not found.'], 404);
    }
}
