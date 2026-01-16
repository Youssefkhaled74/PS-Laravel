<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait UploadTrait
{
    /**
     * Upload a file to public directory and return relative path
     * 
     * @param UploadedFile $file
     * @param string $path Relative path from public/ (e.g., 'uploads/vendor/documents')
     * @return string Relative path (e.g., 'uploads/vendor/documents/filename.jpg')
     */
    public function uploadPublicFile(UploadedFile $file, string $path): string
    {
        // Clean path
        $path = trim($path, '/\\');
        
        // Create full directory path
        $targetDir = public_path($path);
        
        // Create directory if it doesn't exist
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.' . $extension;
        
        // Move file to public directory
        $file->move($targetDir, $filename);
        
        // Return relative path (without leading slash)
        return $path . '/' . $filename;
    }

    /**
     * Upload multiple files to public directory
     * 
     * @param array $files Array of UploadedFile instances
     * @param string $path Relative path from public/
     * @return array Array of relative paths
     */
    public function uploadMultiplePublicFiles(array $files, string $path): array
    {
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadPublicFile($file, $path);
            }
        }
        
        return $paths;
    }

    /**
     * Delete a file from public directory
     * 
     * @param string|null $relativePath Relative path from public/ (e.g., 'uploads/vendor/documents/file.jpg')
     * @return bool
     */
    public function deletePublicFile(?string $relativePath): bool
    {
        if (!$relativePath) {
            return false;
        }
        
        $fullPath = public_path($relativePath);
        
        if (File::exists($fullPath)) {
            return File::delete($fullPath);
        }
        
        return false;
    }

    /**
     * Get full URL for a public file
     * 
     * @param string|null $relativePath
     * @return string|null
     */
    public function getPublicFileUrl(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }
        
        return asset($relativePath);
    }
}
