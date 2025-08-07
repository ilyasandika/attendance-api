<?php

namespace App\Exceptions;

use Exception;

class FieldInUseException extends Exception
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? __('errorMessages.field_in_use'));
    }

    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], 409);
    }
}
