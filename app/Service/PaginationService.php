<?php

namespace App\Service;

class PaginationService
{
    public static function buildPaginationMeta(
        int $page,
        int $limit,
        int $total,
        array $additionalParams = []
    ): array {
        $totalPages = $total > 0 ? ceil($total / $limit) : 0;
        
        $baseUrl = url()->current();
        $queryParams = array_merge(request()->query(), $additionalParams);
        
        $nextUrl = null;
        if ($page < $totalPages) {
            $nextParams = array_merge($queryParams, ['page' => $page + 1, 'limit' => $limit]);
            $nextUrl = $baseUrl . '?' . http_build_query($nextParams);
        }
        
        $previousUrl = null;
        if ($page > 1) {
            $prevParams = array_merge($queryParams, ['page' => $page - 1, 'limit' => $limit]);
            $previousUrl = $baseUrl . '?' . http_build_query($prevParams);
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'total_pages' => $totalPages,
            'next_url' => $nextUrl,
            'previous_url' => $previousUrl,
        ];
    }
}
