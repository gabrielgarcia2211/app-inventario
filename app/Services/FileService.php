<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function saveFile(UploadedFile $file, $userId, $subfolder = null, $disk = 'disk_product')
    {
        $filename = time() . '_' . rand(1, 1000) . $file->getClientOriginalName();
        $path = $this->buildPath($userId, $subfolder, $filename);
        $file->storeAs('', $path, $disk);
        return $path;
    }

    public function deleteFile($path, $disk = 'disk_product')
    {
        if (empty($path)) {
            return false;
        }

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
            return true;
        }
        return false;
    }

    public function getFileUrl($path, $disk = 'disk_product')
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }
        return null;
    }

    private function buildPath($userId, $subfolder, $filename)
    {
        $path = $userId . '/';
        if ($subfolder) {
            $path .= $subfolder . '/';
        }
        $path .= $filename;
        return $path;
    }
}
