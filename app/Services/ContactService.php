<?php

namespace App\Services;

use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\Log;

class ContactService
{
    /**
     * Create contact
     * @param $request
     * @return true
     */
    public function createContact($request)
    {
        try {
            $contacts = [
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ];

            Contact::create($contacts);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Get contact list paginate
     * @return Array contact list
     */
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

    /**
     * Delete contact
     * @param number $id id of contact
     * @return true
     */
    public function deleteContact($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
