<?php

namespace App\Validator;

use App\Constant\AppConstant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UploadPdfValidator
{
    public static function validateRequest(Request $request): void
    {
        $request->validate([
            'file' => 'required|file',
        ]);
    }

    public static function validateFile($file): void
    {
        if (!$file) {
            throw new \Exception('No file uploaded');
        }

        if ($file->getClientOriginalExtension() !== 'pdf') {
            throw new \Exception('Only PDF files are allowed');
        }

        if ($file->getMimeType() !== 'application/pdf') {
            throw new \Exception('Invalid file type. Must be application/pdf');
        }

        if ($file->getSize() > AppConstant::MAX_FILE_SIZE) {
            throw new \Exception('File size exceeds maximum limit (10MB)');
        }
    }

    public static function getErrorCode(string $message): string
    {
        if (str_contains($message, 'size exceeds')) {
            return AppConstant::ERROR_FILE_TOO_LARGE;
        }
        
        if (str_contains($message, 'Only PDF files')) {
            return AppConstant::ERROR_INVALID_EXTENSION;
        }
        
        if (str_contains($message, 'Invalid file type')) {
            return AppConstant::ERROR_INVALID_MIME_TYPE;
        }
        
        if (str_contains($message, 'No file')) {
            return AppConstant::ERROR_NO_FILE;
        }

        return AppConstant::ERROR_UPLOAD_FAILED;
    }

    public static function getStatusCode(string $message): int
    {
        if (str_contains($message, 'size exceeds')) {
            return 413; 
        }
        
        if (str_contains($message, 'Only PDF files') || str_contains($message, 'Invalid file type')) {
            return 422;
        }
        
        if (str_contains($message, 'No file')) {
            return 400; 
        }

        return 400;
    }
}
