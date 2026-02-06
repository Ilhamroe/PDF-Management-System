# Quick Start Guide

Get up and running with PDF Management System in 5 minutes!

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or MariaDB
- Git

## Installation (5 Steps)

### 1. Clone & Install

```bash
git clone https://github.com/ilhamroe/pdf-management-system.git
cd pdf-management-system
composer install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_DATABASE=pdf_management_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Setup Database & Storage

```bash
# Create database (MySQL)
mysql -u root -p -e "CREATE DATABASE pdf_management_system"

# Run migrations
php artisan migrate

# Setup storage
php artisan storage:link
```

### 4. Install DomPDF (PDF Generation Library)

```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### 5. Start Server

```bash
php artisan serve
```

Your API is now running at **http://localhost:8000** 🚀

## Quick Test with Postman

### 1. Import Collection

1. Open Postman
2. Click **Import**
3. Select `postman_collection.json` from project root
4. Select `postman_environment.json`
5. Choose environment: "PDF Management System - Local"

### 2. Test Generate PDF

1. Open request: **1. Generate PDF**
2. Click **Send**
3. You should get **201 Created** response
4. PDF file is saved in `storage/app/public/uploads/pdf/`

### 3. Test Upload PDF

1. Open request: **2. Upload PDF**
2. Select a PDF file (Body → file → Select Files)
3. Click **Send**
4. You should get **201 Created** response

### 4. Test List PDFs

1. Open request: **3. List PDFs (All)**
2. Click **Send**
3. You should see all PDFs you created/uploaded

### 5. Test Delete PDF

1. Open request: **4. Delete PDF**
2. Change URL: `/api/pdf/1` (use actual ID from list)
3. Click **Send**
4. You should get **200 OK** response

## Quick cURL Examples

### Generate PDF

```bash
curl -X POST http://localhost:8000/api/pdf/generate \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Test Report",
    "institution_name": "My Company",
    "address": "123 Main St, City",
    "content": "This is a test PDF content."
  }'
```

### Upload PDF

```bash
curl -X POST http://localhost:8000/api/pdf/upload \
  -H "Accept: application/json" \
  -F "file=@/path/to/your/file.pdf"
```

### List PDFs

```bash
curl -X GET "http://localhost:8000/api/pdf/list?page=1&limit=10" \
  -H "Accept: application/json"
```

### Delete PDF

```bash
curl -X DELETE http://localhost:8000/api/pdf/1 \
  -H "Accept: application/json"
```

## Common Issues

### Issue: "SQLSTATE[HY000] [1049] Unknown database"

**Solution:** Create the database first:

```bash
mysql -u root -p -e "CREATE DATABASE pdf_management_system"
```

### Issue: "The storage link could not be created"

**Solution:** Remove existing link and recreate:

```bash
# Windows
rmdir public\storage
php artisan storage:link

# Linux/Mac
rm public/storage
php artisan storage:link
```

### Issue: "No application encryption key has been specified"

**Solution:** Generate the key:

```bash
php artisan key:generate
```

### Issue: "Class 'DomPDF' not found"

**Solution:** Install and configure DomPDF:

```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## Next Steps

Now that you're up and running:

1. **Read the full docs**: [README.md](README.md)
2. **Explore API**: [API Documentation](README.md#api-documentation)
3. **Understand database**: [DATABASE-SCHEMA.md](DATABASE-SCHEMA.md)
4. **Learn Postman**: [POSTMAN-SETUP-GUIDE.md](POSTMAN-SETUP-GUIDE.md)
5. **Contribute**: [CONTRIBUTING.md](CONTRIBUTING.md)

## One-Command Setup (Advanced)

If you prefer a single command setup:

```bash
git clone https://github.com/ilhamroe/pdf-management-system.git && \
cd pdf-management-system && \
composer install && \
cp .env.example .env && \
php artisan key:generate && \
mysql -u root -p -e "CREATE DATABASE pdf_management_system" && \
php artisan migrate && \
composer require barryvdh/laravel-dompdf && \
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider" && \
php artisan storage:link && \
echo "Setup complete! Run: php artisan serve"
```

_Note: You'll still need to edit `.env` for database credentials_

## Development Tips

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### View Routes

```bash
php artisan route:list
```

### Database Reset

```bash
php artisan migrate:fresh
```

### Code Formatting

```bash
./vendor/bin/pint
```

### Run Tests

```bash
php artisan test
```

## Support

- **Documentation**: [README.md](README.md)
- **Issues**: [GitHub Issues](https://github.com/ilhamroe/pdf-management-system/issues)
- **Security**: [SECURITY.md](SECURITY.md)

---

**Happy Coding!** 🎉

For detailed documentation, see [README.md](README.md)
