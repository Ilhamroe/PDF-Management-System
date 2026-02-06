<?php

namespace App\Validator;

class DeletePdfValidator
{
    public static function getDeleteErrorStatusCode(string $message): int
    {
        if (str_contains($message, 'not found')) {
            return 404;
        }

        if (str_contains($message, 'already deleted')) {
            return 409;
        }

        return 400;
    }
}
