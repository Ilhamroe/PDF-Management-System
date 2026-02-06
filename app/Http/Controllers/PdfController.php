<?php

namespace App\Http\Controllers;

use App\Service\PdfService;
use App\Validator\GeneratePdfValidator;
use App\Validator\UploadPdfValidator;
use App\Validator\ListPdfValidator;
use App\Validator\DeletePdfValidator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function generate(Request $request): JsonResponse
    {
        try {
            $validated = GeneratePdfValidator::validate($request);
            $result = $this->pdfService->generateReport($validated);

            return response()->json($result, $result['status_code']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function upload(Request $request): JsonResponse
    {
        try {
            UploadPdfValidator::validateRequest($request);
            $file = $request->file('file');
            
            $result = $this->pdfService->uploadPdf($file);

            return response()->json($result, $result['status_code']);
        } catch (Exception $e) {
            $errorCode = UploadPdfValidator::getErrorCode($e->getMessage());
            $statusCode = UploadPdfValidator::getStatusCode($e->getMessage());

            return response()->json([
                'success' => false,
                'status_code' => $statusCode,
                'message' => $e->getMessage(),
                'error_code' => $errorCode,
            ], $statusCode);
        }
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $validated = ListPdfValidator::validate($request);
            $filters = ListPdfValidator::extractFilters($validated);
            $pagination = ListPdfValidator::getPaginationParams($validated);

            $result = $this->pdfService->listPdfs(
                $filters,
                $pagination['page'],
                $pagination['limit']
            );

            return response()->json($result, $result['status_code']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $result = $this->pdfService->deletePdf($id);

            return response()->json($result, $result['status_code']);
        } catch (Exception $e) {
            $statusCode = DeletePdfValidator::getDeleteErrorStatusCode($e->getMessage());

            return response()->json([
                'success' => false,
                'status_code' => $statusCode,
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }
}
