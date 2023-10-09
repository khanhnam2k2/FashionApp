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

    public function searchContact($searchName)
    {
        try {
            $contacts = Contact::select('contacts.*');
            if ($searchName != null && $searchName != '') {
                $contacts->where('contacts.name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('contacts.email', 'LIKE', '%' . $searchName . '%');
            }
            $contacts = $contacts->latest()->paginate(5);
            return $contacts;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function deleteContact($id)
    {
        try {
            $data = Contact::where('id', $id)->delete();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
