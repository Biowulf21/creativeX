<?php

namespace App\Exceptions;

use Exception;

class AlreadyLoggedInException extends Exception
{
    public function render($request)
    {
        return response()->error('The user is already logged in', 400);
    }
}
