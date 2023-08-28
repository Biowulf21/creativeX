<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $defaultMessage = 'Cannot perform this operation. User not found.';

        public function __construct($message = null, $code = 0,)
        {
            if ($message == null)
            {
                $message = $this->defaultMessage;
            }
            parent::__construct($message, $code);
        }
    public function render()
    {
        return response()->json(['message'=>$this->message], 404);
    }
}
