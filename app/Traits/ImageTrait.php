<?php

namespace App\Traits;

trait ImageTrait
{
    public function toPdfImageSrc($value)
    {
        if (! $value) {
            return null;
        }

        // Base64 already
        if (strpos($value, 'data:') === 0) {
            return $value;
        }

        $localPath = null;
        $isHttp = str_starts_with($value, 'http://') || str_starts_with($value, 'https://');

        // Convert URL → local path
        if ($isHttp) {
            $urlPath = parse_url($value, PHP_URL_PATH);
            if ($urlPath) {
                $candidate = public_path(ltrim($urlPath, '/'));
                if (is_file($candidate) && is_readable($candidate)) {
                    $localPath = $candidate;
                }
            }
        }

        // Check local paths
        if (! $localPath) {
            $trimmed = ltrim((string) $value, '/');

            $paths = [
                public_path($trimmed),
                storage_path('app/public/' . preg_replace('#^storage/#', '', $trimmed)),
            ];

            foreach ($paths as $path) {
                if (is_file($path) && is_readable($path)) {
                    $localPath = $path;
                    break;
                }
            }
        }

        // Convert to base64
        if ($localPath) {
            $ext = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));

            // Fix WEBP for DomPDF
            if ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
                $img = @imagecreatefromwebp($localPath);
                if ($img) {
                    ob_start();
                    imagepng($img);
                    $png = ob_get_clean();
                    imagedestroy($img);

                    return 'data:image/png;base64,' . base64_encode($png);
                }
            }

            $mime = mime_content_type($localPath) ?: 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($localPath));
        }

        return $value;
    }
}