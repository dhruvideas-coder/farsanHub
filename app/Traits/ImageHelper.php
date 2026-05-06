<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHelper
{
    protected function compressAndStoreImage($file, string $folder, int $maxWidth = 1200, int $quality = 80): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $tmpPath   = $file->getPathname();

        $gdInfo     = gd_info();
        $webpEncode = !empty($gdInfo['WebP Support']) || !empty($gdInfo['WebP Encode Support']);

        $outputExt   = $webpEncode ? 'webp' : 'jpg';
        $filename    = uniqid() . '.' . $outputExt;
        $storagePath = $folder . '/' . $filename;
        $fullPath    = storage_path('app/public/' . $storagePath);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // imagecreatefromstring auto-detects format — avoids format-specific GD errors
        $imageData = @file_get_contents($tmpPath);
        $src       = $imageData ? @imagecreatefromstring($imageData) : false;

        if (!$src) {
            $fallbackName = uniqid() . '.' . $extension;
            $file->storeAs($folder, $fallbackName, 'public');
            return $folder . '/' . $fallbackName;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        if ($origW > $maxWidth) {
            $ratio = $maxWidth / $origW;
            $newW  = $maxWidth;
            $newH  = (int) round($origH * $ratio);
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);

        // Preserve transparency for PNG/WebP with alpha
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefill($dst, 0, 0, $transparent);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        if ($webpEncode) {
            imagewebp($dst, $fullPath, $quality);
        } else {
            imagejpeg($dst, $fullPath, $quality);
        }

        imagedestroy($src);
        imagedestroy($dst);

        return $storagePath;
    }

    protected function deleteStorageImage(?string $path): void
    {
        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
