# PDF Management System

A RESTful API system for managing PDF files built with Laravel 12. This system provides functionality for generating PDFs from HTML content, uploading existing PDF files, listing PDFs with filters, and soft-deleting PDF records.

## Table of Contents

- [Description](#description)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Database Schema](#database-schema)
- [Postman Collection](#postman-collection)
- [License](#license)

## Description

PDF Management System is a robust API service designed to handle PDF document operations. The system allows users to:

- Generate custom PDF documents with institution headers and logos
- Upload existing PDF files with validation
- List PDF files with pagination and filtering
- Soft delete PDF records with status tracking

All PDF files are stored in the `storage/app/public/uploads/pdf` directory with unique generated filenames to prevent conflicts.

## Tech Stack

### Backend

- **PHP** `^8.2`
- **Laravel** `^12.0` - PHP web application framework
- **MySQL** - Relational database management system

### Libraries & Packages

- **barryvdh/laravel-dompdf** `^3.1` - PDF generation library
- **Laravel Tinker** `^2.10.1` - REPL for Laravel

### Development Tools

- **PHPUnit** `^11.5.3` - Testing framework
- **Laravel Pint** `^1.24` - Code style fixer
- **Laravel Sail** `^1.41` - Docker development environment
- **Mockery** `^1.6` - Mocking framework

## Features

### 1. PDF Generation

- Generate PDF from dynamic HTML content
- Support for institution information (name, address, phone)
- Logo integration (PNG/JPG formats, Base64 encoded)
- Automatic page numbering and timestamps
- A4 portrait paper format

### 2. PDF Upload

- File validation (type, size, extension)
- Maximum file size: 10MB
- Only PDF files allowed (MIME type validation)
- Unique filename generation
- Original filename preservation

### 3. PDF Listing

- Pagination support (customizable page and limit)
- Filter by status (CREATED, UPLOADED, DELETED)
- Includes soft-deleted records
- Returns complete metadata

### 4. PDF Deletion

- Soft delete implementation
- Status tracking (prevents double deletion)
- Returns deleted record details

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL or MariaDB
- Git

### Step-by-Step Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/ilhamroe/pdf-management-system.git
    cd pdf-management-system
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Create environment file**

    ```bash
    cp .env.example .env
    ```

4. **Configure database in `.env` file**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pdf_management_system
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

5. **Generate application key**

    ```bash
    php artisan key:generate
    ```

6. **Install and configure DomPDF**

    ```bash
    composer require barryvdh/laravel-dompdf
    php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
    ```

7. **Create symbolic link for storage**

    ```bash
    php artisan storage:link
    ```

8. **Run database migrations**

    ```bash
    php artisan migrate
    ```

    Or fresh migration (resets database):

    ```bash
    php artisan migrate:fresh
    ```

9. **Start development server**

    ```bash
    php artisan serve
    ```

    The API will be available at `http://localhost:8000`

### Optional: Install frontend dependencies

```bash
npm install
npm run dev
```

## Project Structure

```
pdf-management-system/
├── app/
│   ├── Constant/
│   │   └── AppConstant.php          # Application constants (Status, Error codes, Config)
│   ├── Helper/
│   │   └── FormatPdf.php            # PDF formatting & generation helpers
│   ├── Http/
│   │   └── Controllers/
│   │       └── PdfController.php    # Main PDF API controller
│   ├── Models/
│   │   └── PdfFile.php              # PDF file model with soft deletes
│   ├── Repository/
│   │   └── PdfRepository.php        # Database operations abstraction
│   ├── Service/
│   │   └── PdfService.php           # Business logic layer
│   └── Validator/
│       ├── DeletePdfValidator.php   # Delete operation validation
│       ├── GeneratePdfValidator.php # PDF generation validation
│       ├── ListPdfValidator.php     # List operation validation
│       └── UploadPdfValidator.php   # Upload validation & error handling
├── database/
│   └── migrations/
│       └── 2026_02_05_100839_create_pdf_files_table.php
├── routes/
│   └── api.php                       # API route definitions
└── storage/
    └── app/
        └── public/
            └── uploads/
                └── pdf/              # PDF storage directory
```

### Architecture Pattern

This project follows a **Repository-Service Pattern** with clear separation of concerns:

- **Controllers**: Handle HTTP requests/responses
- **Validators**: Request validation and error code mapping
- **Services**: Business logic implementation
- **Repositories**: Database operations abstraction
- **Helpers**: Reusable utility functions
- **Constants**: Centralized configuration values

## API Documentation

### Publishment URL

```
https://documenter.getpostman.com/view/49395306/2sBXc8oioA
```

### Base URL

```
http://localhost:8000/api
```

### Response Format

All responses follow this standard format:

**Success Response:**

```json
{
  "success": true,
  "message": "Operation message",
  "status_code": 200,
  "data": { ... }
}
```

**Error Response:**

```json
{
    "success": false,
    "status_code": 400,
    "message": "Error message",
    "error_code": "ERROR_CODE" // (optional, for specific errors)
}
```

---

### 1. Generate PDF

Generate a new PDF document from provided content.

**Endpoint:** `POST /api/pdf/generate`

**Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Request Body:**

```json
{
    "title": "Monthly Report",
    "institution_name": "PT. Example Company",
    "address": "Jl. Example No. 123, Jakarta",
    "phone": "021-12345678",
    "logo_url": "https://example.com/logo.png",
    "content": "This is the main content of the PDF document.\n\nIt supports multiple paragraphs and formatting."
}
```

**Request Parameters:**

| Field            | Type   | Required | Description                                |
| ---------------- | ------ | -------- | ------------------------------------------ |
| title            | string | Yes      | Document title (max 255 chars)             |
| institution_name | string | Yes      | Institution/company name (max 255 chars)   |
| address          | string | Yes      | Institution address (max 500 chars)        |
| phone            | string | No       | Phone number (max 50 chars)                |
| logo_url         | string | No       | URL to logo image (PNG/JPG, max 500 chars) |
| content          | string | Yes      | Main document content                      |

**Success Response (201):**

```json
{
    "success": true,
    "message": "PDF generated successfully",
    "status_code": 201,
    "data": {
        "id": 1,
        "filename": "report_20260206_143025_aBc123.pdf",
        "filepath": "/uploads/pdf/report_20260206_143025_aBc123.pdf",
        "status": "CREATED",
        "created_at": "2026-02-06T14:30:25+07:00"
    }
}
```

**Error Response (400):**

```json
{
    "success": false,
    "status_code": 400,
    "message": "Field 'title' is required"
}
```

---

### 2. Upload PDF

Upload an existing PDF file to the system.

**Endpoint:** `POST /api/pdf/upload`

**Headers:**

```
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body (Form Data):**

```
file: [PDF file]
```

**Request Parameters:**

| Field | Type | Required | Description                         |
| ----- | ---- | -------- | ----------------------------------- |
| file  | file | Yes      | PDF file (max 10MB, .pdf extension) |

**Success Response (201):**

```json
{
    "success": true,
    "message": "PDF uploaded successfully",
    "status_code": 201,
    "data": {
        "id": 2,
        "original_name": "document.pdf",
        "filename": "upload_20260206_143530_xYz789.pdf",
        "filepath": "/uploads/pdf/upload_20260206_143530_xYz789.pdf",
        "size": 245678,
        "status": "UPLOADED",
        "created_at": "2026-02-06T14:35:30+07:00"
    }
}
```

**Error Responses:**

**400 - No File Uploaded:**

```json
{
    "success": false,
    "status_code": 400,
    "message": "No file uploaded",
    "error_code": "NO_FILE"
}
```

**413 - File Too Large:**

```json
{
    "success": false,
    "status_code": 413,
    "message": "File size exceeds maximum limit (10MB)",
    "error_code": "FILE_TOO_LARGE"
}
```

**422 - Invalid File Type:**

```json
{
    "success": false,
    "status_code": 422,
    "message": "Only PDF files are allowed",
    "error_code": "INVALID_EXTENSION"
}
```

**422 - Invalid MIME Type:**

```json
{
    "success": false,
    "status_code": 422,
    "message": "Invalid file type. Must be application/pdf",
    "error_code": "INVALID_MIME_TYPE"
}
```

---

### 3. List PDFs

Retrieve a paginated list of PDF files with optional filtering.

**Endpoint:** `GET /api/pdf/list`

**Headers:**

```
Accept: application/json
```

**Query Parameters:**

| Parameter | Type    | Required | Description                                    |
| --------- | ------- | -------- | ---------------------------------------------- |
| status    | string  | No       | Filter by status: CREATED, UPLOADED, DELETED   |
| page      | integer | No       | Page number (default: 1, min: 1)               |
| limit     | integer | No       | Items per page (default: 10, min: 1, max: 100) |

**Example Requests:**

```
GET /api/pdf/list
GET /api/pdf/list?status=UPLOADED
GET /api/pdf/list?page=2&limit=20
GET /api/pdf/list?status=CREATED&page=1&limit=5
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "PDF list retrieved successfully",
    "status_code": 200,
    "data": [
        {
            "id": 1,
            "filename": "report_20260206_143025_aBc123.pdf",
            "original_name": "Monthly Report.pdf",
            "size": 156789,
            "status": "CREATED",
            "created_at": "2026-02-06T14:30:25+07:00",
            "deleted_at": null
        },
        {
            "id": 2,
            "filename": "upload_20260206_143530_xYz789.pdf",
            "original_name": "document.pdf",
            "size": 245678,
            "status": "UPLOADED",
            "created_at": "2026-02-06T14:35:30+07:00",
            "deleted_at": null
        }
    ],
    "pagination": {
        "page": 1,
        "limit": 10,
        "total": 2
    }
}
```

**Error Response (400):**

```json
{
    "success": false,
    "status_code": 400,
    "message": "Validation error message"
}
```

---

### 4. Delete PDF

Soft delete a PDF file by ID.

**Endpoint:** `DELETE /api/pdf/{id}`

**Headers:**

```
Accept: application/json
```

**Path Parameters:**

| Parameter | Type    | Required | Description |
| --------- | ------- | -------- | ----------- |
| id        | integer | Yes      | PDF file ID |

**Example Request:**

```
DELETE /api/pdf/1
```

**Success Response (200):**

```json
{
    "success": true,
    "message": "PDF deleted successfully",
    "status_code": 200,
    "data": {
        "id": 1,
        "filename": "report_20260206_143025_aBc123.pdf",
        "status": "DELETED",
        "deleted_at": "2026-02-06T15:00:00+07:00"
    }
}
```

**Error Responses:**

**404 - Not Found:**

```json
{
    "success": false,
    "status_code": 404,
    "message": "PDF file not found"
}
```

**409 - Already Deleted:**

```json
{
    "success": false,
    "status_code": 409,
    "message": "PDF file is already deleted"
}
```

**400 - Other Errors:**

```json
{
    "success": false,
    "status_code": 400,
    "message": "Failed to delete PDF file"
}
```

---

## Database Schema

### Table: `pdf_files`

| Column            | Type            | Nullable | Default        | Description                              |
| ----------------- | --------------- | -------- | -------------- | ---------------------------------------- |
| id                | BIGINT UNSIGNED | No       | AUTO_INCREMENT | Primary key                              |
| filename          | VARCHAR(255)    | No       | -              | Generated unique filename                |
| original_filename | VARCHAR(255)    | Yes      | NULL           | Original uploaded filename               |
| filepath          | VARCHAR(500)    | No       | -              | Relative path to file in storage         |
| size              | BIGINT          | Yes      | NULL           | File size in bytes                       |
| status            | ENUM            | No       | -              | File status (CREATED, UPLOADED, DELETED) |
| created_at        | TIMESTAMP       | Yes      | NULL           | Record creation timestamp                |
| updated_at        | TIMESTAMP       | Yes      | NULL           | Record update timestamp                  |
| deleted_at        | TIMESTAMP       | Yes      | NULL           | Soft delete timestamp                    |

**Indexes:**

- Primary Key: `id`
- Index on `status` for filtering
- Index on `deleted_at` for soft delete queries

**Status Values:**

- `CREATED` - PDF generated by the system
- `UPLOADED` - PDF uploaded by user
- `DELETED` - PDF soft deleted

### Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────┐
│         pdf_files               │
├─────────────────────────────────┤
│ id (PK)                         │
│ filename                        │
│ original_filename               │
│ filepath                        │
│ size                            │
│ status (ENUM)                   │
│ created_at                      │
│ updated_at                      │
│ deleted_at (Soft Delete)        │
└─────────────────────────────────┘
```

**Note:** Currently a single table design. Future enhancements may include:

- User authentication (users table)
- File categories (categories table)
- File tags (tags, file_tags pivot table)
- File sharing (shared_files table)

---

## Postman Collection

A complete Postman collection is available in the repository for easy API testing.

### Import Instructions

1. Open Postman
2. Click "Import" button
3. Select the file: `postman_collection.json` (in root directory)
4. The collection will be imported with all endpoints configured

### Collection Contents

The Postman collection includes:

- All 4 API endpoints (Generate, Upload, List, Delete)
- Pre-configured request bodies and headers
- Example requests with various scenarios
- Environment variables for base URL

### Using the Collection

1. **Set Environment Variables:**
    - `base_url`: `http://localhost:8000/api`

2. **Test Each Endpoint:**
    - Generate PDF: Use the example JSON body
    - Upload PDF: Select a test PDF file
    - List PDFs: Try different query parameters
    - Delete PDF: Use an existing PDF ID

3. **View Responses:**
    - Check status codes
    - Verify response structure
    - Test error scenarios

For detailed endpoint usage, refer to:

- `POSTMAN-UPLOAD-GUIDE.md`
- `POSTMAN-LIST-GUIDE.md`
- `POSTMAN-DELETE-GUIDE.md`

---

## Error Codes Reference

### Upload Errors

| Error Code        | Status | Description                      |
| ----------------- | ------ | -------------------------------- |
| NO_FILE           | 400    | No file was uploaded             |
| FILE_TOO_LARGE    | 413    | File exceeds 10MB limit          |
| INVALID_EXTENSION | 422    | File extension is not .pdf       |
| INVALID_MIME_TYPE | 422    | MIME type is not application/pdf |
| UPLOAD_FAILED     | 400    | General upload failure           |

### Delete Errors

| Status | Description                     |
| ------ | ------------------------------- |
| 404    | PDF file not found              |
| 409    | PDF file already deleted        |
| 400    | Failed to delete (other errors) |

---

## Testing

Run the test suite:

```bash
php artisan test
```

Run with coverage:

```bash
php artisan test --coverage
```

---

## Development

### Code Style

This project uses Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```

### Running in Development Mode

Start the development server with hot reload:

```bash
php artisan serve
```

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Contact & Support

For questions, issues, or feature requests, please open an issue on GitHub.

**Happy Coding!** 🚀
