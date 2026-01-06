<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadService
{
    /** Upload an image to public/uploads and return relative path (without leading slash) */
    public function uploadPublicImage(UploadedFile $file, string $dir, ?string $prefix = null): string
    {
        $dir = trim($dir, '/');
        $ext = $file->getClientOriginalExtension();
        $name = ($prefix ? $prefix.'_': '') . Str::random(8) . '_' . time() . '.' . $ext;

        $targetDir = public_path($dir);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $name);

        return $dir . '/' . $name;
    }

    /** Delete file under public path if exists. */
    public function deletePublicFile(?string $path): void
    {
        if (! $path) return;
        $full = public_path($path);
        if (file_exists($full) && is_file($full)) {
            @unlink($full);
        }
    }
}
