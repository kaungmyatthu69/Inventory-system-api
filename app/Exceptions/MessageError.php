<?php

namespace App\Exceptions;

use Exception;

class MessageError extends Exception
{
    public function __construct(
        public readonly string $errorMessage,
        public readonly int $statusCode = 422,
        public readonly mixed $data = null,
    ) {
        parent::__construct($errorMessage, $statusCode);
    }
}
