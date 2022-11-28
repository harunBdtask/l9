<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadRemoveService
{
    // Upload The File and return the File Path
    public static function fileUpload($path, $file, $type): string
    {
        $folderPath = $path . '/' . $type . '/';
        $fileParts = explode(";base64,", $file);
        $fileTypeAux = isset($fileParts[0]) ? explode($type . "/", $fileParts[0]) : null;
        $filePart = isset($fileTypeAux[1]) ? trim($fileTypeAux[1]) : null;
        $fileBase64 = isset($fileParts[1]) ? base64_decode($fileParts[1]) : null;
        $file = $folderPath . time() . rand(10000, 99999) . '.' . $filePart;
        Storage::disk('public')->put($file, $fileBase64);

        return $file;
    }

    // Remove File
    public static function removeFile($path)
    {
        Storage::disk('public')->delete($path);
    }
}
