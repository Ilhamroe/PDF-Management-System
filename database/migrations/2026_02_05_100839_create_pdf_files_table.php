<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constant\AppConstant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pdf_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 255);
            $table->string('original_filename', 255)->nullable();
            $table->string('filepath', 500);
            $table->bigInteger('size')->nullable();
            $table->enum('status', [
                AppConstant::PDF_STATUS_CREATED, 
                AppConstant::PDF_STATUS_UPLOADED, 
                AppConstant::PDF_STATUS_DELETED
            ]);
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_files');
    }
};
