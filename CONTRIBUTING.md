# Contributing to PDF Management System

Thank you for your interest in contributing to the PDF Management System! This document provides guidelines and instructions for contributing to this project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Testing Guidelines](#testing-guidelines)
- [Documentation](#documentation)

## Code of Conduct

By participating in this project, you agree to maintain a respectful and inclusive environment. We expect all contributors to:

- Be respectful and considerate of others
- Welcome newcomers and help them get started
- Accept constructive criticism gracefully
- Focus on what is best for the community and the project

## Getting Started

### 1. Fork the Repository

Click the "Fork" button at the top right of the repository page.

### 2. Clone Your Fork

```bash
git clone https://github.com/YOUR_USERNAME/pdf-management-system.git
cd pdf-management-system
```

### 3. Add Upstream Remote

```bash
git remote add upstream https://github.com/ORIGINAL_OWNER/pdf-management-system.git
```

### 4. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies (if needed)
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

### 5. Create a Branch

```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/your-bug-fix-name
```

## Development Workflow

### Branch Naming Convention

Use descriptive branch names with prefixes:

- `feature/` - New features
    - Example: `feature/add-pdf-compression`
- `fix/` - Bug fixes
    - Example: `fix/upload-validation-error`
- `docs/` - Documentation updates
    - Example: `docs/update-api-examples`
- `refactor/` - Code refactoring
    - Example: `refactor/extract-pdf-service`
- `test/` - Test additions or modifications
    - Example: `test/add-upload-validation-tests`

### Keeping Your Fork Updated

```bash
# Fetch upstream changes
git fetch upstream

# Merge upstream main into your local main
git checkout main
git merge upstream/main

# Push updates to your fork
git push origin main

# Rebase your feature branch
git checkout feature/your-feature-name
git rebase main
```

## Coding Standards

This project follows Laravel and PSR coding standards.

### PHP Code Style

We use **Laravel Pint** for code formatting:

```bash
# Format all files
./vendor/bin/pint

# Check specific files
./vendor/bin/pint app/Service/PdfService.php
```

### Key Standards

1. **Indentation:** 4 spaces (no tabs)
2. **Line Length:** Maximum 120 characters
3. **Naming Conventions:**
    - Classes: `PascalCase`
    - Methods: `camelCase`
    - Variables: `camelCase`
    - Constants: `UPPER_SNAKE_CASE`
    - Database tables: `snake_case`

4. **File Organization:**
    ```
    app/
    ├── Constant/     # Application constants
    ├── Helper/       # Helper classes
    ├── Http/
    │   └── Controllers/
    ├── Models/       # Eloquent models
    ├── Repository/   # Data access layer
    ├── Service/      # Business logic
    └── Validator/    # Request validation
    ```

### Code Quality

- **Single Responsibility:** One class/method should do one thing well
- **DRY Principle:** Don't Repeat Yourself
- **SOLID Principles:** Follow SOLID design principles
- **Type Hints:** Always use type hints for parameters and return types
- **DocBlocks:** Add PHPDoc for public methods

**Example:**

```php
/**
 * Generate a PDF report from provided data
 *
 * @param array $data The report data including title, content, etc.
 * @return array The generated PDF details
 * @throws Exception If PDF generation fails
 */
public function generateReport(array $data): array
{
    // Implementation
}
```

## Commit Guidelines

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation changes
- `style` - Code style changes (formatting, no logic change)
- `refactor` - Code refactoring
- `test` - Adding or updating tests
- `chore` - Maintenance tasks

### Examples

**Good commit messages:**

```
feat(upload): add file size limit validation

Implement 10MB file size limit for PDF uploads with proper error handling.
Closes #123

fix(delete): prevent double deletion of PDF files

Add status check before deletion to return 409 Conflict if file is already deleted.

docs(readme): update installation instructions

Add missing steps for DomPDF installation and storage link creation.

refactor(service): extract PDF storage logic to helper

Move savePdfToStorage method from PdfService to FormatPdf helper class
for better code organization and reusability.
```

**Bad commit messages:**

```
update code
fix bug
changes
WIP
asdf
```

### Atomic Commits

- Make small, focused commits
- Each commit should represent one logical change
- Don't mix multiple unrelated changes in one commit

## Pull Request Process

### 1. Ensure Your Code Follows Standards

```bash
# Run code formatter
./vendor/bin/pint

# Run tests
php artisan test

# Check for errors
php artisan config:clear
php artisan cache:clear
```

### 2. Update Documentation

- Update README.md if adding new features
- Update API documentation for new endpoints
- Add/update code comments
- Update CHANGELOG.md (if exists)

### 3. Write/Update Tests

- Add unit tests for new functionality
- Update existing tests if behavior changes
- Ensure all tests pass

### 4. Create Pull Request

1. Push your branch to your fork:

    ```bash
    git push origin feature/your-feature-name
    ```

2. Go to the original repository on GitHub

3. Click "New Pull Request"

4. Select your fork and branch

5. Fill in the PR template:

    ```markdown
    ## Description

    Brief description of changes

    ## Type of Change

    - [ ] Bug fix
    - [ ] New feature
    - [ ] Breaking change
    - [ ] Documentation update

    ## Related Issue

    Closes #123

    ## Testing

    - Describe how you tested the changes
    - List any manual testing steps

    ## Checklist

    - [ ] Code follows project style guidelines
    - [ ] Self-review completed
    - [ ] Comments added for complex code
    - [ ] Documentation updated
    - [ ] Tests added/updated
    - [ ] All tests pass
    ```

6. Click "Create Pull Request"

### 5. Respond to Feedback

- Address all review comments
- Push additional commits to the same branch
- Re-request review when ready

### 6. After Merge

```bash
# Switch to main branch
git checkout main

# Pull latest changes
git pull upstream main

# Delete feature branch
git branch -d feature/your-feature-name
git push origin --delete feature/your-feature-name
```

## Testing Guidelines

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Unit/PdfServiceTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter testGeneratePdf
```

### Writing Tests

**Example Unit Test:**

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Service\PdfService;
use App\Repository\PdfRepository;

class PdfServiceTest extends TestCase
{
    public function test_generate_pdf_with_valid_data()
    {
        $pdfService = new PdfService(new PdfRepository());

        $data = [
            'title' => 'Test Report',
            'institution_name' => 'Test Institution',
            'address' => 'Test Address',
            'content' => 'Test content',
        ];

        $result = $pdfService->generateReport($data);

        $this->assertTrue($result['success']);
        $this->assertEquals(201, $result['status_code']);
        $this->assertArrayHasKey('data', $result);
    }
}
```

**Example Feature Test:**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class PdfControllerTest extends TestCase
{
    public function test_upload_pdf_endpoint()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/pdf/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'PDF uploaded successfully',
                 ]);
    }
}
```

### Test Coverage Goals

- Aim for at least 80% code coverage
- Critical paths should have 100% coverage
- Test both success and failure scenarios

## Documentation

### What to Document

1. **Code Comments:**
    - Complex algorithms
    - Non-obvious business logic
    - Public API methods

2. **README Updates:**
    - New features
    - Changed behavior
    - Installation steps
    - Configuration options

3. **API Documentation:**
    - New endpoints
    - Changed request/response formats
    - New error codes

4. **Inline Examples:**
    - Usage examples for complex features
    - Configuration examples

### Documentation Style

- Use clear, concise language
- Provide code examples where helpful
- Keep examples up-to-date
- Use proper Markdown formatting

## Common Issues and Solutions

### Database Issues

```bash
# Reset database
php artisan migrate:fresh

# Seed data
php artisan db:seed
```

### Permission Issues

```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows
# Right-click storage folder → Properties → Security → Edit
```

### Cache Issues

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Getting Help

- **Documentation:** Check README.md and other docs first
- **Issues:** Search existing issues before creating new ones
- **Discussions:** Use GitHub Discussions for questions
- **Code Review:** Don't hesitate to ask for clarification on review comments

## Recognition

Contributors will be recognized in:

- CONTRIBUTORS.md file
- Release notes for significant contributions
- GitHub contributors page

## Questions?

Feel free to open an issue with the `question` label if you need help or clarification on anything.

---

Thank you for contributing! Your efforts help make this project better for everyone. 🎉
