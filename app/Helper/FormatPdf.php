<?php

namespace App\Helper;

use App\Constant\AppConstant;
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
                'filepath' => '/' . $pdfRecord->filepath,
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
                'filepath' => '/' . $pdfRecord->filepath,
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

    public static function savePdfToStorage($pdf, string $filename): string
    {
        $directory = AppConstant::PDF_UPLOAD_PATH;
        $filepath = $directory . '/' . $filename;
        
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        Storage::disk('public')->put($filepath, $pdf->output());
        
        return $filepath;
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

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                @page {
                    margin: 120px 50px 80px 50px;
                }
                
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.6;
                    color: #333;
                }
                
                .header {
                    position: fixed;
                    top: -100px;
                    left: 0;
                    right: 0;
                    height: 100px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 10px;
                    padding-left: 50px;
                    padding-right: 50px;
                }
                
                .header-content {
                    display: table;
                    width: 100%;
                }
                
                .header-left {
                    display: table-cell;
                    width: 90px;
                    vertical-align: middle;
                    padding-right: 15px;
                }
                
                .header-center {
                    display: table-cell;
                    text-align: center;
                    vertical-align: middle;
                    padding-left: 0;
                }
                
                .institution-name {
                    font-size: 18px;
                    font-weight: bold;
                    margin: 0;
                    padding: 0;
                }
                
                .institution-details {
                    font-size: 10px;
                    margin: 5px 0 0 0;
                }
                
                .footer {
                    position: fixed;
                    bottom: -60px;
                    left: 0;
                    right: 0;
                    height: 50px;
                    border-top: 1px solid #333;
                    padding-top: 10px;
                    font-size: 10px;
                    padding-left: 50px;
                    padding-right: 50px;
                }
                
                .content {
                    margin-top: 20px;
                    padding: 0 40px;
                }
                
                .document-title {
                    font-size: 16px;
                    font-weight: bold;
                    text-align: center;
                    margin-bottom: 10px;
                    text-transform: uppercase;
                    padding: 0;
                }
                
                .generated-date {
                    font-size: 11px;
                    text-align: center;
                    margin-bottom: 20px;
                    color: #666;
                }
                
                .document-content {
                    text-align: justify;
                    margin-top: 20px;
                    padding: 0;
                    line-height: 1.8;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="header-content">
                    <div class="header-left">' . $logoHtml . '</div>
                    <div class="header-center">
                        <h1 class="institution-name">' . htmlspecialchars($institutionName) . '</h1>
                        <div class="institution-details">
                            ' . htmlspecialchars($address) . 
                            (!empty($phone) ? '<br>Telp: ' . htmlspecialchars($phone) : '') . '
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <!-- Footer content rendered by DomPDF canvas -->
            </div>
            
            <div class="content">
                <div class="document-title">' . htmlspecialchars($title) . '</div>
                <div class="generated-date">Tanggal Generate: ' . $generatedDate . '</div>
                <div class="document-content">
                    ' . nl2br(htmlspecialchars($content)) . '
                </div>
            </div>
        </body>
        </html>';
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
