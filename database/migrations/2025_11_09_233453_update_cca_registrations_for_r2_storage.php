<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration doesn't change the schema because we're using JSON columns
     * which can store both old format (strings/arrays of strings) and new format
     * (arrays of objects with path, url, size, etc.)
     * 
     * The FileUploadService now returns:
     * {
     *   "path": "registrations/academic/file.pdf",
     *   "url": "https://pub-xxx.r2.dev/registrations/academic/file.pdf",
     *   "size": 1048576,
     *   "original_name": "document.pdf",
     *   "mime_type": "application/pdf"
     * }
     * 
     * This is already compatible with the existing JSON columns.
     */
    public function up(): void
    {
        // No schema changes needed - JSON columns already support the new format
        // Old data: ["path/to/file1.pdf", "path/to/file2.pdf"]
        // New data: [{"path": "...", "url": "...", "size": ...}, {...}]
        // Both work with JSON columns
        
        // If you want to migrate existing local files to R2, do it manually
        // See FILE_UPLOAD_IMPLEMENTATION.md for migration guide
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No schema changes to reverse
    }
};
