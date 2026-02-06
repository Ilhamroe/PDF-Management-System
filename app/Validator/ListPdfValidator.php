<?php

namespace App\Validator;

use App;
use App\Constant\AppConstant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ListPdfValidator
{
    public static function validate(Request $request): array
    {
        return $request->validate([
            'status' => 'nullable|string|in:' . AppConstant::PDF_STATUS_CREATED . ',' . AppConstant::PDF_STATUS_UPLOADED . ',' . AppConstant::PDF_STATUS_DELETED,
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:' . AppConstant::MAX_LIMIT,
        ]);
    }

    public static function extractFilters(array $validated): array
    {
        $filters = [];
        
        if (!empty($validated['status'])) {
            $filters['status'] = $validated['status'];
        }

        return $filters;
    }


    public static function getPaginationParams(array $validated): array
    {
        return [
            'page' => $validated['page'] ?? AppConstant::DEFAULT_PAGE,
            'limit' => $validated['limit'] ?? AppConstant::DEFAULT_LIMIT,
        ];
    }
}
