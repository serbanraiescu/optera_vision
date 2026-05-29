<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Upload an image file, process WebP conversions & thumbnails via GD,
     * and fall back safely to original formats if conversion fails.
     */
    public function upload(UploadedFile $file, string $folder = 'general'): Media
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // 1. Generate unique random filename
        $uuidName = Str::uuid()->toString();
        $webpFilename = $uuidName . '.webp';
        
        $folderPath = "media/{$folder}";
        $mainPath = "{$folderPath}/{$webpFilename}";
        $thumbPath = "{$folderPath}/thumb_{$webpFilename}";

        // Make sure directory exists inside storage public disk
        Storage::disk('public')->makeDirectory($folderPath);

        $width = null;
        $height = null;
        $webpSuccess = false;

        // Try WebP conversion using PHP GD
        try {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                $type = $imageInfo[2];

                $srcImage = null;
                switch ($type) {
                    case IMAGETYPE_JPEG:
                        $srcImage = @imagecreatefromjpeg($file->getRealPath());
                        break;
                    case IMAGETYPE_PNG:
                        $srcImage = @imagecreatefrompng($file->getRealPath());
                        break;
                    case IMAGETYPE_WEBP:
                        $srcImage = @imagecreatefromwebp($file->getRealPath());
                        break;
                }

                if ($srcImage) {
                    // Create main compressed WebP file (Quality: 80)
                    $tempWebp = tempnam(sys_get_temp_dir(), 'media_main');
                    if (@imagewebp($srcImage, $tempWebp, 80)) {
                        Storage::disk('public')->put($mainPath, file_get_contents($tempWebp));
                        @unlink($tempWebp);
                        $webpSuccess = true;
                    }

                    // Create thumbnail (proportional crop/resize to max 150x150)
                    $thumbSize = 150;
                    $thumbImage = imagecreatetruecolor($thumbSize, $thumbSize);
                    
                    // Maintain transparency for PNG if converting
                    imagealphablending($thumbImage, false);
                    imagesavealpha($thumbImage, true);

                    // Proportional resizing crop math
                    $aspectRatio = $width / $height;
                    if ($aspectRatio > 1) {
                        $srcWidth = $height;
                        $srcHeight = $height;
                        $srcX = intval(($width - $height) / 2);
                        $srcY = 0;
                    } else {
                        $srcWidth = $width;
                        $srcHeight = $width;
                        $srcX = 0;
                        $srcY = intval(($height - $width) / 2);
                    }

                    if (@imagecopyresampled($thumbImage, $srcImage, 0, 0, $srcX, $srcY, $thumbSize, $thumbSize, $srcWidth, $srcHeight)) {
                        $tempThumb = tempnam(sys_get_temp_dir(), 'media_thumb');
                        if (@imagewebp($thumbImage, $tempThumb, 80)) {
                            Storage::disk('public')->put($thumbPath, file_get_contents($tempThumb));
                            @unlink($tempThumb);
                        }
                        @imagedestroy($thumbImage);
                    }
                    @imagedestroy($srcImage);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("MediaService GD conversion failed for file '{$originalName}'. Fallback initialized. Error: " . $e->getMessage());
            $webpSuccess = false;
        }

        // 2. Fallback Handling: Keep original format if WebP conversion fails
        if (!$webpSuccess) {
            $fallbackFilename = $uuidName . '.' . $extension;
            $mainPath = "{$folderPath}/{$fallbackFilename}";
            
            // Put original file onto storage public disk
            Storage::disk('public')->putFileAs($folderPath, $file, $fallbackFilename);
            $thumbPath = null; // No thumbnail generated on fallback failure
        }

        // 3. Register table logs
        return Media::create([
            'filename' => basename($mainPath),
            'original_name' => $originalName,
            'path' => $mainPath,
            'thumbnail_path' => $thumbPath,
            'mime_type' => $mimeType,
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'folder' => $folder,
        ]);
    }
}
