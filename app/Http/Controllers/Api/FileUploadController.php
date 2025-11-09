<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    protected FileUploadService $fileUploadService;
    
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    
    /**
     * Upload a single file with progress tracking
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'directory' => 'required|string',
            'field_name' => 'required|string', // For tracking which form field this file belongs to
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        try {
            $file = $request->file('file');
            $directory = $request->input('directory');
            $fieldName = $request->input('field_name');
            
            // Additional file validation
            $validation = $this->fileUploadService->validateFile($file, [
                'max_size' => 10 * 1024 * 1024, // 10MB
                'allowed_types' => [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/heic',
                    'image/webp',
                ],
            ]);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation['errors'],
                ], 422);
            }
            
            // Upload file to R2
            $uploadedFile = $this->fileUploadService->uploadFile($file, $directory);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'field_name' => $fieldName,
                    'file' => $uploadedFile,
                ],
            ]);
            
        } catch (\Exception $e) {
            \Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Delete a file from R2
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        try {
            $path = $request->input('path');
            $deleted = $this->fileUploadService->deleteFile($path);
            
            return response()->json([
                'success' => $deleted,
                'message' => $deleted ? 'File deleted successfully' : 'File not found',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('File deletion failed', [
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'File deletion failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
