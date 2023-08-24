<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function render($request)
    {
        return response()->error('Invalid credentials', 400);
    }
}
