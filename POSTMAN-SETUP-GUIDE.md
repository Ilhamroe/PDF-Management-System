# Postman Collection Setup Guide

This guide will help you set up and use the Postman Collection for testing the PDF Management System API.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation Steps](#installation-steps)
- [Collection Overview](#collection-overview)
- [Testing Workflow](#testing-workflow)
- [Environment Variables](#environment-variables)
- [Common Issues](#common-issues)

## Prerequisites

- **Postman Desktop App** or **Postman Web** installed
- **PDF Management System** running locally (see main README.md for setup)
- Basic understanding of HTTP methods and REST APIs

## Installation Steps

### 1. Import Collection

1. Open Postman
2. Click the **Import** button (top left corner)
3. Select **File** tab
4. Choose `postman_collection.json` from the project root
5. Click **Import**

The collection "PDF Management System API" will appear in your Collections sidebar.

### 2. Import Environment (Recommended)

1. Click **Import** button again
2. Select `postman_environment.json`
3. Click **Import**
4. Select the environment from the dropdown (top right): **PDF Management System - Local**

### 3. Verify Base URL

Check that the `base_url` variable is set correctly:

- Click the **Environment** quick look (eye icon)
- Verify `base_url` = `http://localhost:8000/api`
- If your server runs on a different port, update accordingly

## Collection Overview

The collection contains **4 main endpoints**:

### 1. Generate PDF

- **Method:** POST
- **Endpoint:** `/pdf/generate`
- **Purpose:** Create a new PDF from HTML content
- **Body Type:** JSON

### 2. Upload PDF

- **Method:** POST
- **Endpoint:** `/pdf/upload`
- **Purpose:** Upload an existing PDF file
- **Body Type:** Form Data (multipart/form-data)

### 3. List PDFs

- **Method:** GET
- **Endpoint:** `/pdf/list`
- **Purpose:** Retrieve paginated list of PDFs
- **Parameters:** status, page, limit

### 4. Delete PDF

- **Method:** DELETE
- **Endpoint:** `/pdf/{id}`
- **Purpose:** Soft delete a PDF by ID
- **Path Variable:** id (integer)

## Testing Workflow

### Step-by-Step Testing Guide

#### 1. Start Your Local Server

```bash
# Navigate to project directory
cd pdf-management-system

# Start Laravel development server
php artisan serve
```

Server should be running at `http://localhost:8000`

#### 2. Test Generate PDF Endpoint

1. Open **1. Generate PDF** request
2. Review the JSON body (pre-filled with example data)
3. Click **Send**
4. Expected response: **201 Created** with PDF details
5. Note the `id` from the response (you'll need it for delete test)

**Example Success Response:**

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

**Tips:**

- Modify the `title`, `institution_name`, etc. to test different content
- Try removing required fields to see validation errors
- For `logo_url`, use PNG or JPG images (SVG will show placeholder)

#### 3. Test Upload PDF Endpoint

1. Open **2. Upload PDF** request
2. Go to **Body** tab (should show form-data)
3. Click **Select Files** next to the `file` key
4. Choose a PDF file from your computer (max 10MB)
5. Click **Send**
6. Expected response: **201 Created** with upload details

**Example Success Response:**

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

**Error Testing:**

- Try uploading a non-PDF file (expect **422 Unprocessable Entity**)
- Try uploading a file > 10MB (expect **413 Payload Too Large**)
- Try sending without a file (expect **400 Bad Request**)

#### 4. Test List PDFs Endpoint

**A. List All PDFs (Default)**

1. Open **3. List PDFs (All)** request
2. Click **Send**
3. Expected response: **200 OK** with array of PDFs

**B. List with Filters**

1. Open **3. List PDFs (with Filters)** request
2. Review query parameters:
    - `status`: CREATED, UPLOADED, or DELETED
    - `page`: 1 (first page)
    - `limit`: 5 (5 items per page)
3. Click **Send**
4. Expected response: **200 OK** with filtered results

**C. Test Pagination**

1. Open **3. List PDFs (Pagination)** request
2. Modify query parameters:
    - `page`: 2
    - `limit`: 20
3. Click **Send**

**Example Success Response:**

```json
{
    "success": true,
    "message": "PDF list retrieved successfully",
    "status_code": 200,
    "data": [
        {
            "id": 1,
            "filename": "report_20260206_143025_aBc123.pdf",
            "original_name": "Monthly Sales Report.pdf",
            "size": 156789,
            "status": "CREATED",
            "created_at": "2026-02-06T14:30:25+07:00",
            "deleted_at": null
        }
    ],
    "pagination": {
        "page": 1,
        "limit": 10,
        "total": 1
    }
}
```

#### 5. Test Delete PDF Endpoint

1. Open **4. Delete PDF** request
2. Edit the URL path: replace `1` with an actual PDF ID from list/generate response
    - Example: `http://localhost:8000/api/pdf/3`
3. Click **Send**
4. Expected response: **200 OK** with deleted record details

**Example Success Response:**

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

**Error Testing:**

- Try deleting the same PDF twice (expect **409 Conflict**)
- Try deleting a non-existent ID (expect **404 Not Found**)

## Environment Variables

### Available Variables

| Variable | Default Value             | Description       |
| -------- | ------------------------- | ----------------- |
| base_url | http://localhost:8000/api | Full API base URL |
| host     | localhost                 | Server host       |
| port     | 8000                      | Server port       |
| protocol | http                      | HTTP protocol     |

### Changing Environment

**For Production/Staging:**

1. Duplicate the environment: Right-click → Duplicate
2. Rename to "PDF Management System - Production"
3. Update variables:
    ```
    base_url: https://api.example.com/api
    host: api.example.com
    port: 443
    protocol: https
    ```
4. Select the new environment from dropdown

### Using Variables in Requests

Variables are automatically substituted in requests:

```
{{base_url}}/pdf/generate
→ http://localhost:8000/api/pdf/generate
```

## Common Issues

### Issue 1: "Could not get any response"

**Cause:** Laravel server is not running or running on different port

**Solution:**

1. Check if server is running: `php artisan serve`
2. Verify port in terminal output
3. Update `base_url` in environment if needed

### Issue 2: "404 Not Found" for all endpoints

**Cause:** Wrong base URL or missing `/api` prefix

**Solution:**

1. Check `base_url` includes `/api`: `http://localhost:8000/api`
2. Verify routes are registered: `php artisan route:list`

### Issue 3: "413 Request Entity Too Large"

**Cause:** File size exceeds server or application limit

**Solution:**

1. Check file is < 10MB
2. If needed, increase limit in `php.ini`:
    ```ini
    upload_max_filesize = 10M
    post_max_size = 10M
    ```

### Issue 4: "500 Internal Server Error"

**Cause:** Application error (database, permissions, etc.)

**Solution:**

1. Check Laravel logs: `storage/logs/laravel.log`
2. Run migrations: `php artisan migrate`
3. Check storage permissions: `php artisan storage:link`
4. Enable debug mode in `.env`: `APP_DEBUG=true`

### Issue 5: File Upload Shows "No file uploaded"

**Cause:** Form data not configured correctly

**Solution:**

1. Ensure Body type is **form-data**
2. Key name is exactly `file`
3. Type is set to **File** (not Text)
4. A file is actually selected

### Issue 6: Generate PDF Returns 400 with Validation Error

**Cause:** Missing required fields in request body

**Solution:**

1. Verify all required fields are present:
    - title
    - institution_name
    - address
    - content
2. Check JSON syntax is valid
3. Ensure Content-Type header is `application/json`

## Testing Best Practices

1. **Test in Order:**
    - Generate → Upload → List → Delete
    - This ensures you have data to work with

2. **Save Responses:**
    - Click "Save Response" to keep successful examples
    - Useful for documentation and comparison

3. **Use Console:**
    - Open Postman Console (View → Show Postman Console)
    - View full request/response details for debugging

4. **Create Tests:**
    - Add test scripts to validate responses automatically
    - Example:

        ```javascript
        pm.test("Status code is 201", function () {
            pm.response.to.have.status(201);
        });

        pm.test("Response has success field", function () {
            var jsonData = pm.response.json();
            pm.expect(jsonData.success).to.eql(true);
        });
        ```

5. **Use Variables for IDs:**
    - Save generated PDF IDs to environment variables
    - Reference them in subsequent requests
    - Example script (in Generate PDF request Tests tab):
        ```javascript
        var jsonData = pm.response.json();
        pm.environment.set("pdf_id", jsonData.data.id);
        ```
    - Then use `{{pdf_id}}` in Delete request URL

## Advanced Features

### Pre-request Scripts

Add dynamic data to requests:

```javascript
// Set current timestamp
pm.environment.set("timestamp", new Date().toISOString());

// Generate random title
pm.environment.set(
    "random_title",
    "Report " + Math.random().toString(36).substr(2, 9),
);
```

### Collection Runner

Run all requests automatically:

1. Click collection name
2. Click **Run** button
3. Select requests to run
4. Set iterations and delay
5. Click **Run PDF Management System API**

### Mock Server

Create a mock server for frontend development:

1. Right-click collection → **Mock Collection**
2. Name your mock
3. Use mock URL in frontend: `https://[mock-id].mock.pstmn.io`

## Additional Resources

- [Main README](README.md) - Project setup and documentation
- [API Documentation in README](README.md#api-documentation) - Detailed endpoint specs
- [Postman Learning Center](https://learning.postman.com/) - Official Postman docs
- [Laravel Documentation](https://laravel.com/docs) - Laravel framework docs

---

**Need Help?**

If you encounter issues not covered in this guide:

1. Check server logs: `storage/logs/laravel.log`
2. Review API responses for error messages
3. Open an issue on GitHub with:
    - Request details (method, URL, body)
    - Response received
    - Expected behavior

**Happy Testing!** 🚀
