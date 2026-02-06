<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PdfFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pdf_files';

    protected $fillable = [
        'filename',
        'original_filename',
        'filepath',
        'size',
        'status',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
