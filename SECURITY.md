# Security Policy

## Supported Versions

The following versions of PDF Management System are currently being supported with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take the security of PDF Management System seriously. If you believe you have found a security vulnerability, please report it to us as described below.

### Where to Report

**Please do NOT report security vulnerabilities through public GitHub issues.**

Instead, please report them via email to: **security@yourdomain.com** (replace with actual contact)

Alternatively, you can:

- Open a private security advisory on GitHub
- Contact the maintainers directly through GitHub

### What to Include

Please include the following information in your report:

1. **Type of vulnerability** (e.g., SQL injection, XSS, file upload bypass, etc.)
2. **Full paths of source file(s)** related to the vulnerability
3. **Location of the affected source code** (tag/branch/commit or direct URL)
4. **Step-by-step instructions to reproduce** the issue
5. **Proof-of-concept or exploit code** (if possible)
6. **Impact of the vulnerability** and potential attack scenarios
7. **Your assessment of severity** (Critical, High, Medium, Low)

### What to Expect

After submitting a vulnerability report, you can expect:

1. **Acknowledgment within 48 hours** - We'll confirm receipt of your report
2. **Initial assessment within 5 business days** - We'll evaluate the report and severity
3. **Regular updates** - We'll keep you informed about our progress
4. **Timeline for fix** - We'll provide an estimated timeline for the fix
5. **Credit** - We'll acknowledge your contribution in the security advisory (if you wish)

### Response Timeline

- **Critical vulnerabilities**: Patch released within 7 days
- **High severity**: Patch released within 14 days
- **Medium severity**: Patch released within 30 days
- **Low severity**: Included in next regular release

### Security Update Process

1. **Verification** - We verify the vulnerability and determine its impact
2. **Development** - We develop and test a fix
3. **Coordination** - We coordinate with you about disclosure timing
4. **Release** - We release the security patch
5. **Disclosure** - We publish a security advisory (coordinated disclosure)

## Security Best Practices

When deploying PDF Management System, please follow these security best practices:

### 1. Environment Configuration

```bash
# .env file
APP_ENV=production
APP_DEBUG=false
APP_KEY=[generate-strong-key]
```

- **Never** set `APP_DEBUG=true` in production
- Use a strong, randomly generated `APP_KEY`
- Keep `.env` file secure and not in version control

### 2. File Upload Security

The system includes several built-in protections:

- **File type validation** (PDF only)
- **MIME type checking**
- **File size limits** (10MB default)
- **Unique filename generation**
- **Storage outside web root**

Ensure these validations are not bypassed:

```php
// app/Validator/UploadPdfValidator.php
// Do not modify validation logic without security review
```

### 3. Database Security

- Use **prepared statements** (already implemented via Eloquent)
- Keep database credentials secure
- Use **strong database passwords**
- Limit database user privileges (no need for DROP, ALTER in production)

```sql
-- Create limited-privilege user
CREATE USER 'pdf_app'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON pdf_management_system.* TO 'pdf_app'@'localhost';
FLUSH PRIVILEGES;
```

### 4. File System Permissions

```bash
# Secure permissions (Linux/Mac)
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Ensure .env is not readable by others
chmod 600 .env
```

### 5. HTTPS in Production

Always use HTTPS in production:

```nginx
# nginx example
server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    # ... rest of configuration
}
```

### 6. Regular Updates

- Keep Laravel and dependencies updated
- Monitor security advisories
- Apply patches promptly

```bash
# Check for updates
composer outdated

# Update dependencies
composer update
```

### 7. Rate Limiting

Implement rate limiting to prevent abuse:

```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // API routes
});
```

### 8. Input Validation

All user inputs are validated. Do not bypass validators:

- `GeneratePdfValidator` - PDF generation inputs
- `UploadPdfValidator` - File upload validation
- `ListPdfValidator` - Query parameter validation
- `DeletePdfValidator` - Delete operation validation

### 9. Monitoring & Logging

- Monitor application logs regularly
- Set up alerts for suspicious activities
- Log security-relevant events

```bash
# Review logs
tail -f storage/logs/laravel.log
```

### 10. Backup Strategy

- Regular database backups
- Backup uploaded files
- Test restore procedures
- Keep backups encrypted and off-site

## Known Security Considerations

### File Storage

- **Storage location**: `storage/app/public/uploads/pdf/`
- **Access**: Through symbolic link `/storage/uploads/pdf/`
- **Consideration**: Files are publicly accessible via URL
- **Mitigation**: Unique filenames prevent guessing, consider authentication in future

### Soft Deletes

- Deleted files remain in storage
- Database records are soft-deleted
- **Consideration**: Deleted data still accessible in database
- **Mitigation**: Implement hard delete after retention period

### PDF Generation

- Uses DomPDF library for PDF generation
- Processes user-supplied content
- **Consideration**: Potential for XXE or other injection attacks
- **Mitigation**: Content is sanitized with `htmlspecialchars()`

### Logo Loading

- Fetches external images for logos
- Uses `file_get_contents()` on URLs
- **Consideration**: SSRF (Server-Side Request Forgery) potential
- **Mitigation**: Consider implementing:
    - URL whitelist
    - Image upload instead of URL
    - Request timeout
    - Size limits

## Disclosure Policy

We follow **Coordinated Disclosure**:

1. Security researcher reports vulnerability privately
2. We acknowledge and investigate
3. We develop and test a fix
4. We coordinate with reporter on disclosure timing
5. We release the fix
6. We publish security advisory (typically 30 days after fix release)

## Security Hall of Fame

We acknowledge security researchers who responsibly disclose vulnerabilities:

- _Currently empty - be the first!_

## Contact

For security concerns:

- **Email**: security@yourdomain.com
- **PGP Key**: [Link to PGP key] (if available)

For general issues:

- **GitHub Issues**: https://github.com/ilhamroe/pdf-management-system/issues

---

**Last Updated:** 2026-02-06  
**Policy Version:** 1.0

Thank you for helping keep PDF Management System and its users safe! 🔒
