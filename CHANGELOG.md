# Changelog

All notable changes to the PDF Management System will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned

- User authentication system
- File sharing functionality
- PDF compression options
- Batch file operations
- Advanced search capabilities

## [1.0.0] - 2026-02-06

### Added

- Initial release of PDF Management System
- PDF generation from HTML content with institution headers
- PDF file upload with validation (10MB limit, PDF only)
- Paginated PDF listing with status filtering
- Soft delete functionality for PDF records
- RESTful API with 4 main endpoints
- Repository-Service architecture pattern
- Comprehensive error handling and validation
- Postman collection for API testing
- Complete documentation (README, API docs, Database schema)

### Features

- **Generate PDF Endpoint:**
    - Dynamic HTML to PDF conversion
    - Institution header with logo support
    - Automatic page numbering and timestamps
    - A4 portrait format
    - Returns PDF metadata with file path

- **Upload PDF Endpoint:**
    - File type validation (PDF only)
    - File size validation (max 10MB)
    - MIME type verification
    - Unique filename generation
    - Original filename preservation
    - Comprehensive error codes

- **List PDFs Endpoint:**
    - Pagination support (customizable page/limit)
    - Filter by status (CREATED, UPLOADED, DELETED)
    - Includes soft-deleted records
    - Returns full metadata with timestamps

- **Delete PDF Endpoint:**
    - Soft delete implementation
    - Status updates to DELETED
    - Prevents duplicate deletion (409 Conflict)
    - Returns deleted record details

### Architecture

- **Controllers:** HTTP request/response handling
- **Validators:** Request validation and error mapping
- **Services:** Business logic implementation
- **Repositories:** Database abstraction layer
- **Helpers:** Reusable utility functions
- **Constants:** Centralized configuration

### Technical Stack

- Laravel 12.0
- PHP 8.2
- MySQL/MariaDB
- barryvdh/laravel-dompdf 3.1 for PDF generation
- PSR-4 autoloading

### Database

- Single `pdf_files` table with soft deletes
- Status enum: CREATED, UPLOADED, DELETED
- Indexes on status and deleted_at
- Timestamps for created_at, updated_at, deleted_at

### Documentation

- Complete README with installation guide
- API documentation with request/response examples
- Database schema documentation with ERD
- Postman collection with all endpoints
- Postman setup guide
- Contributing guidelines
- Code of conduct

### Error Handling

- Standardized JSON error responses
- HTTP status codes (200, 201, 400, 404, 409, 413, 422)
- Custom error codes for uploads
- Validation error messages
- Exception handling throughout

### Testing Support

- PHPUnit configuration
- Test structure setup
- Postman collection for manual testing
- Example test cases in documentation

### Configuration

- Environment-based configuration
- Configurable pagination limits
- File size limits in constants
- Upload path configuration
- DomPDF settings

### Security

- File type validation
- MIME type checking
- File size limits
- SQL injection prevention (Eloquent ORM)
- No user-generated SQL queries

[Unreleased]: https://github.com/ilhamroe/pdf-management-system/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/ilhamroe/pdf-management-system/releases/tag/v1.0.0
