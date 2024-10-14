<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function uploadImage(string $dataBase64, string $path = 'images/', string $fileName = 'image_') {
        list($imageData, $extension) = Base64Helper::getImage($dataBase64);

        $uniFileName = uniqid($fileName) . '.' . $extension;

        $filePath = $path . $uniFileName;
        Storage::disk('public')->put($filePath, $imageData);

        return $filePath;
    }

    public static function deleteImage(string $path) {
        Storage::disk('public')->delete($path);
    }
}