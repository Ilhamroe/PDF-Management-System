<?php

namespace App\Constant;
class AppConstant
{
    const PDF_STATUS_CREATED = 'CREATED';
    const PDF_STATUS_UPLOADED = 'UPLOADED';
    const PDF_STATUS_DELETED = 'DELETED';
    
    const MAX_FILE_SIZE = 10485760; // 10 * 1024 * 1024 bytes (10MB)
    const PDF_UPLOAD_PATH = 'uploads/pdf';
    
    const ERROR_FILE_TOO_LARGE = 'FILE_TOO_LARGE';
    const ERROR_INVALID_EXTENSION = 'INVALID_EXTENSION';
    const ERROR_INVALID_MIME_TYPE = 'INVALID_MIME_TYPE';
    const ERROR_NO_FILE = 'NO_FILE';
    const ERROR_UPLOAD_FAILED = 'UPLOAD_FAILED';
    
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    const MAX_LIMIT = 100;
}