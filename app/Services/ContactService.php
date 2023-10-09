<?php

namespace App\Services;

use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function createContact($request)
    {
        try {
            $contacts = [
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ];
            $data = Contact::create($contacts);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }


    // public function deleteCategory($id)
    // {
    //     try {
    //         $data = Category::where('id', $id)->delete();
    //         return $data;
    //     } catch (Exception $e) {
    //         Log::error($e);
    //         return response()->json($e, 500);
    //     }
    // }
}
