<?php

declare(strict_types=1);

namespace App\Core;

/**
 * ImageCache - handles image resizing and caching using GD library
 */
class ImageCache
{
    private string $cacheDir;
    private int $quality = 85;
    private ?string $logFile = null;

    public function __construct(?string $cacheDir = null)
    {
        // Cache must be in public/ so browser can access it
        $this->cacheDir = $cacheDir ?? dirname(__DIR__, 2) . '/public/cache/images';
        $this->logFile = dirname(__DIR__, 2) . '/storage/logs/image-cache.log';
        $this->ensureDirectory();
    }

    /**
     * Get cached or generate resized image
     * 
     * @param string $path Path to original image (relative to public/)
     * @param int $width Target width in pixels
     * @param int $height Target height in pixels
     * @return string Path to cached image (relative to public/)
     */
    public function get(string $path, int $width, int $height): string
    {
        if (empty($path)) {
            return '';
        }

        // Generate cache key
        $hash = \md5($path . $width . $height);
        $ext = \pathinfo($path, PATHINFO_EXTENSION);
        $cachedFilename = "{$hash}.{$ext}";
        $cachedPath = $this->cacheDir . "/{$cachedFilename}";
        
        // Return cached version if exists and is valid
        if (\file_exists($cachedPath) && \filesize($cachedPath) > 0) {
            $this->log("✓ HIT: {$cachedFilename}");
            return "cache/images/{$cachedFilename}";
        }

        // Get original image path (relative to project root)
        $sourcePath = dirname(__DIR__, 2) . '/public/' . \ltrim($path, '/');
        $this->log("  → Source path: {$sourcePath}");
        
        if (!\file_exists($sourcePath)) {
            $this->log("  ✗ File not found at: {$sourcePath}");
            return $path; // fallback to original
        }

        $this->log("→ GENERATE: {$path} {$width}x{$height}");

        // Try to generate resized image
        if ($this->resize($sourcePath, $cachedPath, $width, $height)) {
            $this->log("✓ CREATED: {$cachedFilename}");
            return "cache/images/{$cachedFilename}";
        }

        // If generation failed - return original (browser will display full size image)
        // This is better than broken image
        $this->log("⚠ FALLBACK to original: {$path}");
        return $path;
    }

    /**
     * Resize image using GD library
     * Determines type by MIME type (from getimagesize), not just file extension
     * This is important because files may be misnamed (e.g. webp saved as .jpg)
     */
    private function resize(string $source, string $dest, int $width, int $height): bool
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        // Check file exists and is readable
        if (!\file_exists($source)) {
            $this->log("  ✗ File not found: {$source}");
            return false;
        }

        if (!\is_readable($source)) {
            $this->log("  ✗ File not readable: {$source}");
            return false;
        }

        $fileSize = \filesize($source);
        if ($fileSize === false || $fileSize === 0) {
            $this->log("  ✗ File empty: {$source}");
            return false;
        }

        // Get file info
        $info = @\getimagesize($source);
        if (!$info) {
            $this->log("  ✗ getimagesize() failed");
            return false;
        }

        $mime = $info['mime'];
        $origWidth = $info[0];
        $origHeight = $info[1];
        $this->log("  → MIME: {$mime} | Size: {$origWidth}x{$origHeight} | File: {$fileSize} bytes");

        // If WebP and GD doesn't support it - return false to trigger fallback
        if ($mime === 'image/webp') {
            $this->log("  ⚠ WebP format detected - GD doesn't support it, using original");
            return false;
        }

        // Load image based on MIME TYPE (not file extension)
        $image = null;
        
        if ($mime === 'image/jpeg') {
            $image = @\imagecreatefromjpeg($source);
            if (!$image) {
                $this->log("  ✗ imagecreatefromjpeg() failed");
            }
        } elseif ($mime === 'image/png') {
            $image = @\imagecreatefrompng($source);
            if (!$image) {
                $this->log("  ✗ imagecreatefrompng() failed");
            }
        } elseif ($mime === 'image/gif') {
            $image = @\imagecreatefromgif($source);
            if (!$image) {
                $this->log("  ✗ imagecreatefromgif() failed");
            }
        } else {
            $this->log("  ✗ Unsupported MIME type: {$mime}");
            return false;
        }

        if (!$image) {
            return false;
        }

        // Calculate new dimensions preserving aspect ratio
        $origWidth = \imagesx($image);
        $origHeight = \imagesy($image);
        $ratio = $origWidth / $origHeight;
        
        // Fit into target dimensions while preserving aspect ratio
        $newWidth = $width;
        $newHeight = (int)($width / $ratio);
        
        if ($newHeight > $height) {
            $newHeight = $height;
            $newWidth = (int)($height * $ratio);
        }
        
        $this->log("  → Resize from {$origWidth}x{$origHeight} to {$newWidth}x{$newHeight} (ratio: " . number_format($ratio, 2) . ")");

        // Create new image with target size
        $resized = \imagecreatetruecolor($newWidth, $newHeight);
        
        if (!$resized) {
            \imagedestroy($image);
            $this->log("  ✗ imagecreatetruecolor() failed");
            return false;
        }

        // Resample (high quality resize)
        $result = \imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        if (!$result) {
            \imagedestroy($image);
            \imagedestroy($resized);
            $this->log("  ✗ imagecopyresampled() failed");
            return false;
        }

        \imagedestroy($image);

        // Save based on MIME type (not destination extension)
        $result = false;
        
        $this->log("  → Attempting to save to: {$dest}");
        $destDir = \dirname($dest);
        $this->log("  → Dest dir: {$destDir} (writable: " . (\is_writable($destDir) ? 'YES' : 'NO') . ")");
        
        if ($mime === 'image/jpeg') {
            $result = \imagejpeg($resized, $dest, $this->quality);
        } elseif ($mime === 'image/png') {
            $result = \imagepng($resized, $dest, 9);
        } elseif ($mime === 'image/gif') {
            $result = \imagegif($resized, $dest);
        }

        $this->log("  → Save result: " . ($result ? 'TRUE' : 'FALSE'));

        \imagedestroy($resized);

        if (!$result) {
            $this->log("  ✗ imagejpeg/png/gif returned false");
            return false;
        }

        // Verify file was created
        if (!\file_exists($dest)) {
            $this->log("  ✗ File not created: {$dest}");
            return false;
        }

        $savedSize = \filesize($dest);
        if ($savedSize === 0) {
            $this->log("  ✗ File created but empty (0 bytes)");
            return false;
        }

        $this->log("  ✓ Saved successfully: {$savedSize} bytes");
        return true;
    }

    /**
     * Ensure cache directory exists and is writable
     */
    private function ensureDirectory(): void
    {
        if (!\is_dir($this->cacheDir)) {
            if (@\mkdir($this->cacheDir, 0777, true)) {
                $this->log("✓ Cache dir created: {$this->cacheDir}");
            } else {
                $this->log("✗ Cannot create cache dir: {$this->cacheDir}");
                return;
            }
        } else {
            $this->log("✓ Cache dir exists: {$this->cacheDir}");
        }
        
        // Check if writable
        if (!\is_writable($this->cacheDir)) {
            $this->log("✗ Cache dir NOT writable, trying chmod...");
            if (@\chmod($this->cacheDir, 0777)) {
                $this->log("✓ chmod 0777 applied");
            } else {
                $this->log("✗ chmod failed");
            }
        } else {
            $this->log("✓ Cache dir is writable");
        }
    }

    /**
     * Clear all cached images
     */
    public function clearCache(): void
    {
        if (!\is_dir($this->cacheDir)) {
            return;
        }

        $files = \glob($this->cacheDir . '/*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (\is_file($file)) {
                    @\unlink($file);
                }
            }
        }
        $this->log("✓ Cache cleared");
    }

    /**
     * Log messages
     */
    private function log(string $message): void
    {
        if (!$this->logFile) {
            return;
        }

        $timestamp = \date('Y-m-d H:i:s');
        @\file_put_contents($this->logFile, "[{$timestamp}] {$message}\n", FILE_APPEND | LOCK_EX);
    }
}

