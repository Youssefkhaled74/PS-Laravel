<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

trait UploadsTrait
{
    /**
     * Upload single image and return relative public path or null
     */
    public function uploadImage(?UploadedFile $file, string $dir, ?string $filename = null): ?string
    {
        if (! $file) {
            return null;
        }

        $this->validateUploadedFile($file);

        $dir = trim($dir, "\/ ");
        $targetDir = public_path($dir);

        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $safeName = $filename ? pathinfo($filename, PATHINFO_FILENAME) : Str::random(20);
        $safeName = Str::slug($safeName) ?: Str::random(8);
        $newName = $safeName . '.' . $ext;

        $file->move($targetDir, $newName);

        return $dir . '/' . $newName;
    }

    /**
     * Upload multiple images. Accepts array of UploadedFile (or empty) and returns array of relative paths.
     */
    public function uploadImages(array $files, string $dir): array
    {
        $paths = [];
        if (empty($files)) {
            return $paths;
        }

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadImage($file, $dir);
            }
        }

        return array_values(array_filter($paths));
    }

    /**
     * Delete a file under public path. Accepts relative path like 'uploads/users/abc.jpg'
     */
    public function deleteFile(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        $full = public_path(ltrim($path, '\/'));
        if (File::exists($full)) {
            return File::delete($full);
        }

        return false;
    }

    /**
     * Basic validation of uploaded file according to config/uploads.php
     * Throws \InvalidArgumentException on invalid file
     */
    protected function validateUploadedFile(UploadedFile $file): void
    {
        $allowed = Config::get('uploads.allowed_extensions', ['jpg','jpeg','png','gif','webp']);
        $maxKb = Config::get('uploads.max_kb', 5120);

        $ext = strtolower($file->getClientOriginalExtension());
        $sizeKb = intval($file->getSize() / 1024);

        $forbidden = ['php','php3','php4','phtml','exe','sh','bat','pl'];
        if (in_array($ext, $forbidden, true)) {
            throw new \InvalidArgumentException('Invalid file type.');
        }

        if (! in_array($ext, $allowed, true)) {
            throw new \InvalidArgumentException('File extension not allowed.');
        }

        if ($sizeKb > $maxKb) {
            throw new \InvalidArgumentException('File size exceeds limit.');
        }
    }
}
