<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    /**
     * Upload file in ckeditor
     * @param Request $request
     * @return response object
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $originName = $file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            // Lưu ảnh vào thư mục storage/app/public
            $filePath = $file->storeAs('public/media', $fileName);

            // Lấy URL của ảnh từ thư mục storage/app/public
            $url = Storage::url($filePath);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }
}
