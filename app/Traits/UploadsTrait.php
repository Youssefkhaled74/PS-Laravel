<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

trait UploadsTrait
{
    /**
     * Upload single file and return relative public path or null
     * Backwards-compatible: this is a wrapper around uploadOne
     */
    public function uploadImage(?UploadedFile $file, string $dir, ?string $filename = null): ?string
    {
        if (! $file) return null;
        // If caller provides a directory like 'uploads/vendor/avatars' we accept it;
        // otherwise treat $dir as model/subfolder and build path inside uploads/..
        if (str_starts_with(trim($dir, "\/ "), 'uploads/')) {
            // caller passed full relative path; uploadOne expects model/subfolder style,
            // so we still use uploadOne but with model=null to place under provided dir
            $model = null;
            $sub = ltrim($dir, "\/ ");
            return $this->uploadOne($file, $model, $sub, null, $filename);
        }

        // Normal usage: $dir is model or model/subfolder
        $parts = explode('/', trim($dir, "\/ "));
        $model = $parts[0] ?? $dir;
        $sub = count($parts) > 1 ? implode('/', array_slice($parts, 1)) : null;
        return $this->uploadOne($file, $model, $sub, null, $filename);
    }

    /**
     * Upload many files. Wrapper around uploadMany
     */
    public function uploadImages(array $files, string $dir): array
    {
        if (str_starts_with(trim($dir, "\/ "), 'uploads/')) {
            return $this->uploadMany($files, null, ltrim($dir, "\/ "));
        }

        $parts = explode('/', trim($dir, "\/ "));
        $model = $parts[0] ?? $dir;
        $sub = count($parts) > 1 ? implode('/', array_slice($parts, 1)) : null;
        return $this->uploadMany($files, $model, $sub);
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
        try {
            if (File::exists($full)) {
                return File::delete($full);
            }
            // treat missing file as successful delete for idempotency
            return true;
        } catch (\Throwable $e) {
            return false;
        }
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

    /**
     * Upload a single file to public/uploads/{model}/{subfolder?}/{ownerId?}/
     * Returns relative path starting with 'uploads/...'
     */
    public function uploadOne(UploadedFile $file, ?string $model, ?string $subfolder = null, $ownerId = null, ?string $prefix = null): string
    {
        // extra safety validation
        $this->validateUploadedFile($file);

        $modelPart = $model ? $this->sanitizeFolder($model) : null;
        $subPart = $subfolder ? $this->sanitizeFolder($subfolder) : null;
        $ownerPart = $ownerId ? trim((string)$ownerId) : null;

        $parts = ['uploads'];
        if ($modelPart) $parts[] = $modelPart;
        if ($subPart) {
            // allow nested subfolders like documents/id_cards
            foreach (explode('/', $subPart) as $p) {
                $parts[] = $p;
            }
        }
        if ($ownerPart) $parts[] = $ownerPart;

        $relativeDir = implode('/', $parts);
        $targetDir = public_path($relativeDir);

        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $time = date('Ymd_His');
        $rand = Str::random(8);
        $safePrefix = $prefix ? $this->sanitizeFolder($prefix) . '_' : '';
        $filename = $safePrefix . $time . '_' . $rand . '.' . $ext;

        $file->move($targetDir, $filename);

        return $relativeDir . '/' . $filename;
    }

    /**
     * Upload many files and return array of relative paths
     * $files may be array of UploadedFile objects
     */
    public function uploadMany(array $files, ?string $model, ?string $subfolder = null, $ownerId = null, ?string $prefix = null): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadOne($file, $model, $subfolder, $ownerId, $prefix);
            }
        }
        return array_values($paths);
    }

    /**
     * Replace existing file with new one. Returns new relative path.
     */
    public function replaceFile(?string $oldRelativePath, UploadedFile $newFile, string $model, ?string $subfolder = null, $ownerId = null, ?string $prefix = null): string
    {
        if ($oldRelativePath) {
            $this->deleteFile($oldRelativePath);
        }
        return $this->uploadOne($newFile, $model, $subfolder, $ownerId, $prefix);
    }

    /**
     * Return public URL for a stored relative path, or null
     */
    public function publicUrl(?string $relativePath): ?string
    {
        if (! $relativePath) return null;
        if (str_starts_with($relativePath, 'http')) return $relativePath;
        return asset($relativePath);
    }

    /**
     * Sanitize folder or prefix names: lowercase, replace spaces with underscores, remove special chars
     */
    protected function sanitizeFolder(string $input): string
    {
        $s = mb_strtolower($input);
        $s = preg_replace('/[^a-z0-9\/\-_]+/u', '_', $s);
        $s = preg_replace('/_+/', '_', $s);
        $s = trim($s, "_\/");
        return $s;
    }
}
