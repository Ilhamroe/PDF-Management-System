<?php

namespace App\Repository;

use App\Models\PdfFile;
use App\Constant\AppConstant;

class PdfRepository
{
    public function create(array $data): PdfFile
    {
        return PdfFile::create([
            'filename' => $data['filename'],
            'original_filename' => $data['original_filename'] ?? null,
            'filepath' => $data['filepath'],
            'size' => $data['size'] ?? null,
            'status' => $data['status'] ?? AppConstant::PDF_STATUS_CREATED,
        ]);
    }

    public function find(int $id): ?PdfFile
    {
        return PdfFile::withTrashed()->find($id);
    }

    public function update(int $id, array $data): bool
    {
        $pdfFile = $this->find($id);
        if (!$pdfFile) {
            return false;
        }
        return $pdfFile->update($data);
    }


    public function delete(int $id): bool
    {
        $pdfFile = $this->find($id);
        if (!$pdfFile) {
            return false;
        }
        return $pdfFile->delete();
    }

    public function getPaginatedList(array $filters = [], int $page = 1, int $limit = 10): array
    {
        $query = PdfFile::query();

        $query->withTrashed();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('created_at', 'desc');

        $total = $query->count();

        $offset = ($page - 1) * $limit;
        $items = $query->offset($offset)->limit($limit)->get();

        return [
            'items' => $items,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
            ]
        ];
    }
}
