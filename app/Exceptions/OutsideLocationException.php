<?php

namespace App\Exceptions;

use Exception;

class OutsideLocationException extends Exception
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? __('errorMessages.not_allowed_outside_location'));
    }

    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], 409);
    }
}
