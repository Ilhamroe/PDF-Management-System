<?php

namespace App\Helper;

use App\Constant\AppConstant;
use App\Models\PdfFile;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormatPdf
{
    public static function generateReportFilename(): string
    {
        $date = now()->format('Ymd_His');
        $uniqueId = Str::random(6);
        return "report_{$date}_{$uniqueId}.pdf";
    }

    public static function generateUploadFilename(): string
    {
        $date = now()->format('Ymd_His');
        $uniqueId = Str::random(6);
        return "upload_{$date}_{$uniqueId}.pdf";
    }

    public static function formatPdfResponse($pdfRecord): array
    {
        return [
            'success' => true,
            'message' => 'PDF generated successfully',
            'status_code' => 201,
            'data' => [
                'id' => $pdfRecord->id,
                'filename' => $pdfRecord->filename,
                'filepath' => url(Storage::url($pdfRecord->filepath)),
                'status' => $pdfRecord->status,
                'created_at' => $pdfRecord->created_at->toIso8601String(),
            ]
        ];
    }

    public static function formatUploadResponse($pdfRecord): array
    {
        return [
            'success' => true,
            'message' => 'PDF uploaded successfully',
            'status_code' => 201,
            'data' => [
                'id' => $pdfRecord->id,
                'original_name' => $pdfRecord->original_filename,
                'filename' => $pdfRecord->filename,
                'filepath' => url(Storage::url($pdfRecord->filepath)),
                'size' => $pdfRecord->size,
                'status' => $pdfRecord->status,
                'created_at' => $pdfRecord->created_at->toIso8601String(),
            ]
        ];
    }

    public static function formatDeleteResponse($pdfRecord): array
    {
        return [
            'success' => true,
            'message' => 'PDF deleted successfully',
            'status_code' => 200,
            'data' => [
                'id' => $pdfRecord->id,
                'filename' => $pdfRecord->filename,
                'status' => $pdfRecord->status,
                'deleted_at' => $pdfRecord->deleted_at?->toIso8601String(),
            ]
        ];
    }

    public static function formatListItem($pdf): array
    {
        return [
            'id' => $pdf->id,
            'filename' => $pdf->filename,
            'original_name' => $pdf->original_filename,
            'size' => $pdf->size,
            'status' => $pdf->status,
            'created_at' => $pdf->created_at->toIso8601String(),
            'deleted_at' => $pdf->deleted_at?->toIso8601String(),
        ];
    }

    public static function savePdfToStorage(PDF $pdf, string $filename): string
    {
        $directory = AppConstant::PDF_UPLOAD_PATH;
        $filepath = $directory . '/' . $filename;
        
        try {
             if (!Storage::disk('public')->exists($directory)) {
                if(!Storage::disk('public')->makeDirectory($directory)){
                    throw new Exception('Failed to create directory: ' . $directory);
                }
            }

            if(!Storage::disk('public')->put($filepath, $pdf->output())){
                throw new Exception('Failed to save PDF to storage at: ' . $filepath);
            }

            return $filepath;
        } catch (\Throwable $th) {
            throw new Exception('Failed to save PDF to storage: ' . $th->getMessage());
        }
    }

    public static function createPdfHtml(array $data): string
    {
        $title = $data['title'];
        $institutionName = $data['institution_name'];
        $address = $data['address'];
        $phone = $data['phone'] ?? '';
        $logoUrl = $data['logo_url'] ?? '';
        $content = $data['content'];
        $generatedDate = now()->format('d F Y H:i');
        $logoHtml = self::prepareLogo($logoUrl);

        return view('template', [
            'title' => $title,
            'institutionName' => $institutionName,
            'address' => $address,
            'phone' => $phone,
            'logoHtml' => $logoHtml,
            'logoUrl' => $logoUrl,
            'content' => $content,
            'generatedDate' => $generatedDate,
        ])->render();
    }

    private static function prepareLogo(string $logoUrl): string
    {
        if (empty($logoUrl)) {
            return '';
        }

        if (stripos($logoUrl, '.svg') !== false) {
            return '<div style="width: 80px; height: 80px; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #999; text-align: center; padding: 5px; box-sizing: border-box;">Logo<br/>(Use PNG/JPG)</div>';
        }

        try {
            $imageData = @file_get_contents($logoUrl);
            if ($imageData !== false && strlen($imageData) > 0) {
                $base64 = base64_encode($imageData);
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageData);
                return '<img src="data:' . $mimeType . ';base64,' . $base64 . '" style="max-width: 80px; max-height: 80px;" alt="Logo">';
            }
        } catch (Exception $e) {
            throw new Exception('Failed to load logo image: ' . $e->getMessage());
        }

        return '<div style="width: 80px; height: 80px; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #999;">Logo</div>';
    }
}
