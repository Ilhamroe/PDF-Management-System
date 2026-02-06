# Summary Perbaikan PDF Generation

## ✅ Yang Sudah Diperbaiki:

### 1. **Timezone**

- Config: Asia/Jakarta (WIB +7)
- Format tanggal: "06 February 2026 08:18:39"

### 2. **Storage Path**

- Location: `storage/app/public/uploads/pdf/`
- Accessible via: `http://localhost:8000/storage/uploads/pdf/filename.pdf`

### 3. **Logo Handling**

- ✅ PNG/JPG: Supported via base64 encoding
- ⚠️ SVG: Limited support (menampilkan placeholder dengan pesan "Use PNG/JPG")
- Reason: DomPDF memiliki keterbatasan dalam render SVG

### 4. **Page Numbering**

- Menggunakan DomPDF `page_text()` method
- Syntax: `$canvas->page_text(520, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));`

## 🔧 Testing:

### Test dengan PNG Logo (Recommended):

```powershell
.\test-pdf-with-png-logo.ps1
```

### Test dengan SVG Logo (akan tampil placeholder):

```powershell
.\test-pdf-with-logo.ps1
```

## ⚠️ Known Limitations:

### SVG Logo:

DomPDF tidak support SVG dengan baik. **Solusi**:

1. Convert SVG ke PNG/JPG dulu
2. Gunakan online converter: https://cloudconvert.com/svg-to-png
3. Atau gunakan logo PNG/JPG langsung

### Page Numbering:

Jika page numbers tidak muncul, kemungkinan issue:

1. DomPDF version compatibility
2. Posisi koordinat (520, 820) mungkin perlu disesuaikan

## 🎯 Alternatif Solusi untuk Logo SVG:

Jika ingin support SVG, bisa:

1. Install library `dompdf/dompdf-svg-lib` (sudah included tapi limited)
2. Convert SVG to PNG server-side menggunakan ImageMagick/GD
3. Simpan logo di`public/images/` dan reference by path, bukan URL

## 📝 Contoh Request yang Bekerja dengan Baik:

```json
{
    "title": "Laporan Kunjungan Pasien",
    "institution_name": "RS Sehat Sentosa",
    "address": "Jl. Kesehatan No. 123, Jakarta",
    "phone": "(021) 123-4567",
    "logo_url": "https://via.placeholder.com/150.png", // ← USE PNG!
    "content": "Berikut adalah laporan..."
}
```

## 📂 File Locations:

- Service: `app/Service/PdfService.php`
- Output: `storage/app/public/uploads/pdf/`
- URL Access: `http://localhost:8000/storage/uploads/pdf/filename.pdf`
- Download API: `GET /api/pdf/{id}/download`

## 🐛 Troubleshooting:

Jika **page numbers masih tidak muncul**:

1. Check DomPDF version: `composer show barryvdh/laravel-dompdf`
2. Coba adjust posisi Y coordinate (820 → 800 atau lainnya)
3. Verify `$dompdf->render()` dipanggil sebelum `page_text()`

Jika **logo tidak muncul**:

1. Pastikan URL accessible (not behind authentication)
2. Gunakan PNG/JPG bukan SVG
3. Check file size (jangan terlalu besar)
4. Try local file: copy logo ke `public/images/logo.png` dan reference: `public_path('images/logo.png')`
