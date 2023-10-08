<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class BaseService
{

    /**
     * Upload files to storage
     *
     * @param $files
     * @return path files
     */
    public function uploadFile($files, $newFolder = null)
    {
        try {
            $imagePath = $files;
            $imageName = $imagePath->getClientOriginalName();
            $filename = explode('.', $imageName)[0];
            $extension = $imagePath->getClientOriginalExtension();
            $picName =  Str::slug(time() . "_" . $filename, "_") . "." . $extension;
            $folder = $newFolder ? 'uploads/' . $newFolder : 'uploads';
            $path = $files->storeAs($folder, $picName, 'public');
            return $path;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }


    /**
     * Delete files to storage
     *
     * @param path files
     * @return true
     */
    public function deleteFile($path)
    {
        try {
            if (Storage::exists('public/' . $path)) {
                Storage::delete('public/' . $path);
            }
            return true;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
