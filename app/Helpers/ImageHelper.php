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

    public static function deleteImage(string $relativePath)  {
        if (Storage::disk('public')->exists($relativePath)) {
            $deleted = Storage::disk('public')->delete($relativePath);
            if ($deleted) {
                return "Imagen del recurso eliminada correctamente.";
            } else {
                return $relativePath . "No se pudo eliminar la imagen del recurso.";
            }
        } else {
            return $relativePath . "La imagen del recurso no fue encontrada.";
        }
    }
}