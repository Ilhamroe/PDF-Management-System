<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::prefix('pdf')->group(function () {
    Route::post('/generate', [PdfController::class, 'generate']);
    Route::post('/upload', [PdfController::class, 'upload']);
    Route::get('/list', [PdfController::class, 'list']);
    Route::delete('/{id}', [PdfController::class, 'delete']);
});
