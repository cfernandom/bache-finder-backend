<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Base64Helper
{
    public static function getImage(string $base64) {
        $parts = explode(',', $base64);
        $base64Data = end($parts);

        try {
            $image = base64_decode($base64Data);

            if ($image === false) {
                return [];
            }
            
            $imgResource = imagecreatefromstring($image);
            if ($imgResource === false) {
                return [];
            }

            $size = getimagesizefromstring($image);
            if ($size === false || !$size[0] || !$size[1]) {
                return [];
            }


            $allowedExtensions = ['image/png', 'image/jpg', 'image/jpeg'];

            if (!in_array($size['mime'], $allowedExtensions)) {
                return [];
            }

            $parts = explode('/', $size['mime']);
            $extension = end($parts);

            return [$image, $extension];
        } catch (\Exception $e) {
            Log::error('Base64 Helper Error: ' . $e->getMessage(), ['exception' => $e]);
            return [];
        }
    }
}
