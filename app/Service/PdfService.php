<?php

namespace App\Service;

use App\Constant\AppConstant;
use App\Helper\FormatPdf;
use App\Repository\PdfRepository;
use App\Validator\UploadPdfValidator;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Storage;
use Exception;

class PdfService
{
    protected $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    public function generateReport(array $data): array
    {
        try {
            $filename = FormatPdf::generateReportFilename();
            $pdfContent = FormatPdf::createPdfHtml($data);
            
            $pdf = app()->make(PDF::class);
            $pdf->loadHTML($pdfContent);
            $pdf->setPaper('A4', 'portrait');
            
            $dompdf = $pdf->getDomPDF();
            $dompdf->render();
            
            $canvas = $dompdf->getCanvas();
            $timestamp = now()->format('d/m/Y H:i');
            
            $canvas->page_text(50, 820, "Generated: {$timestamp}", null, 10, array(0, 0, 0)); 
            $canvas->page_text(480, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
            
            $filepath = FormatPdf::savePdfToStorage($pdf, $filename);
            
            $fileSize = Storage::disk('public')->size($filepath);
            
            $pdfRecord = $this->pdfRepository->create([
                'filename' => $filename,
                'original_filename' => $data['title'] . '.pdf',
                'filepath' => $filepath,
                'size' => $fileSize,
            ]);

            return FormatPdf::formatPdfResponse($pdfRecord);
        } catch (Exception $e) {
            throw new Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function uploadPdf($file): array
    {
        try {
            UploadPdfValidator::validateFile($file);
            
            $originalName = $file->getClientOriginalName();
            $filename = FormatPdf::generateUploadFilename();
            
            $directory = AppConstant::PDF_UPLOAD_PATH;
            $filepath = $directory . '/' . $filename;
            
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            Storage::disk('public')->put($filepath, file_get_contents($file->getRealPath()));
            
            $fileSize = Storage::disk('public')->size($filepath);
            
            $pdfRecord = $this->pdfRepository->create([
                'filename' => $filename,
                'original_filename' => $originalName,
                'filepath' => $filepath,
                'size' => $fileSize,
                'status' => AppConstant::PDF_STATUS_UPLOADED,
            ]);

            return FormatPdf::formatUploadResponse($pdfRecord);
        } catch (Exception $e) {
            throw new Exception('Failed to upload PDF: ' . $e->getMessage());
        }
    }

    public function listPdfs(array $filters = [], int $page = 1, int $limit = 10): array
    {
        $result = $this->pdfRepository->getPaginatedList($filters, $page, $limit);

        $data = $result['items']->map(function ($pdf) {
            return FormatPdf::formatListItem($pdf);
        });

        return [
            'success' => true,
            'message' => 'PDF list retrieved successfully',
            'status_code' => 200,
            'data' => $data->toArray(),
            'pagination' => $result['pagination'],
        ];
    }

    public function deletePdf(int $id): array
    {
        $pdfRecord = $this->pdfRepository->find($id);
        
        if (!$pdfRecord) {
            throw new Exception('PDF file not found');
        }
        
        if ($pdfRecord->status === AppConstant::PDF_STATUS_DELETED || $pdfRecord->deleted_at !== null) {
            throw new Exception('PDF file is already deleted');
        }
        
        $updated = $this->pdfRepository->update($id, [
            'status' => AppConstant::PDF_STATUS_DELETED,
        ]);
        
        if (!$updated) {
            throw new Exception('Failed to delete PDF file');
        }
        
        $this->pdfRepository->delete($id);
        
        $pdfRecord = $this->pdfRepository->find($id);
        
        return FormatPdf::formatDeleteResponse($pdfRecord);
    }
}
