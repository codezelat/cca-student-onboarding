<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a single file to R2 with optimization
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $disk
     * @return array ['path' => string, 'url' => string, 'size' => int]
     */
    public function uploadFile(UploadedFile $file, string $directory, ?string $disk = null): array
    {
        $disk = $disk ?? config('filesystems.default');
        
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);
        $path = $directory . '/' . $filename;
        
        // Optimize file before upload (for images)
        $optimizedFile = $this->optimizeFileIfNeeded($file);
        
        // Upload to R2
        Storage::disk($disk)->put(
            $path,
            file_get_contents($optimizedFile->getRealPath()),
            [
                'visibility' => 'private',
                'CacheControl' => 'private, max-age=0, no-cache',
                'ContentType' => $file->getMimeType(),
            ]
        );
        
        // Clean up optimized temp file if different from original
        if ($optimizedFile !== $file && file_exists($optimizedFile->getRealPath())) {
            @unlink($optimizedFile->getRealPath());
        }
        
        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
        ];
    }
    
    /**
     * Upload multiple files to R2
     *
     * @param array $files Array of UploadedFile objects
     * @param string $directory
     * @param string|null $disk
     * @return array Array of file data
     */
    public function uploadMultipleFiles(array $files, string $directory, ?string $disk = null): array
    {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $uploadedFiles[] = $this->uploadFile($file, $directory, $disk);
            }
        }
        
        return $uploadedFiles;
    }
    
    /**
     * Generate a unique filename with timestamp and random string
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Sanitize original name
        $sanitized = Str::slug(substr($originalName, 0, 50));
        
        // Create unique filename: sanitized-name_timestamp_random.ext
        return sprintf(
            '%s_%s_%s.%s',
            $sanitized,
            now()->format('YmdHis'),
            Str::random(8),
            $extension
        );
    }
    
    /**
     * Optimize file if it's an image (reduce size without quality loss)
     * This saves on R2 storage and bandwidth costs
     *
     * @param UploadedFile $file
     * @return UploadedFile
     */
    private function optimizeFileIfNeeded(UploadedFile $file): UploadedFile
    {
        $mimeType = $file->getMimeType();
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        
        // Only optimize images, skip PDFs and documents
        if (!in_array($mimeType, $imageTypes)) {
            return $file;
        }
        
        // Skip if file is already small enough (< 500KB)
        if ($file->getSize() < 500 * 1024) {
            return $file;
        }
        
        try {
            // Use GD or Imagick if available for optimization
            // For now, return original file
            // In production, you can use packages like spatie/image-optimizer or intervention/image
            return $file;
            
        } catch (\Exception $e) {
            // If optimization fails, return original file
            \Log::warning('File optimization failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
            return $file;
        }
    }
    
    /**
     * Delete a file from R2
     *
     * @param string $path
     * @param string|null $disk
     * @return bool
     */
    public function deleteFile(string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        
        return false;
    }
    
    /**
     * Delete multiple files from R2
     *
     * @param array $paths
     * @param string|null $disk
     * @return void
     */
    public function deleteMultipleFiles(array $paths, ?string $disk = null): void
    {
        foreach ($paths as $path) {
            if (is_string($path)) {
                $this->deleteFile($path, $disk);
            } elseif (is_array($path) && isset($path['path'])) {
                $this->deleteFile($path['path'], $disk);
            }
        }
    }
    
    /**
     * Get public URL for a file
     *
     * @param string $path
     * @param string|null $disk
     * @return string
     */
    public function getFileUrl(string $path, ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        return Storage::disk($disk)->url($path);
    }
    
    /**
     * Validate file before upload
     *
     * @param UploadedFile $file
     * @param array $rules
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateFile(UploadedFile $file, array $rules = []): array
    {
        $errors = [];
        
        // Default max size: 10MB
        $maxSize = $rules['max_size'] ?? 10 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            $errors[] = sprintf(
                'File size (%s) exceeds maximum allowed size (%s)',
                $this->formatBytes($file->getSize()),
                $this->formatBytes($maxSize)
            );
        }
        
        // Check allowed mime types
        if (isset($rules['allowed_types'])) {
            $mimeType = $file->getMimeType();
            if (!in_array($mimeType, $rules['allowed_types'])) {
                $errors[] = sprintf(
                    'File type %s is not allowed. Allowed types: %s',
                    $mimeType,
                    implode(', ', $rules['allowed_types'])
                );
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
    
    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @return string
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
