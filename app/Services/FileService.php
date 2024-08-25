<?php

namespace App\Services;

use Illuminate\Http\File;
use Illuminate\Http\Testing\File as FileTesting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Store file to storage.
     *
     * @param File|UploadedFile|FileTesting $file
     * @param string $path
     * @param bool $isOriginalName
     * @param bool $isPublic
     * @return string
     */
    public function storeFile(File|UploadedFile|FileTesting $file, string $path, bool $isOriginalName = false, bool $isPublic = false): string
    {
        // TODO: make a test for this method
        $original_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        if($isOriginalName) {
            $fileName = $original_name . '.' . $extension;
        } else {
            $fileName = md5(time() . '_' . $original_name) . '.' . $extension;
        }

        if($isPublic) {
            Storage::disk('public')->put($path . '/' . $fileName, file_get_contents($file));
        } else {
            Storage::put($path . '/' . $fileName, file_get_contents($file));
        }

        return $fileName;
    }
}
