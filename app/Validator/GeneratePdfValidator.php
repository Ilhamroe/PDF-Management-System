<?php

namespace App\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GeneratePdfValidator
{
    public static function validate(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'logo_url' => 'nullable|url|max:500',
            'content' => 'required|string',
        ]);
    }

    public static function validateRequiredFields(array $data): void
    {
        $requiredFields = ['title', 'institution_name', 'address', 'content'];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Field '{$field}' is required");
            }
        }
    }
}
