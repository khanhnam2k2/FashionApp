<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $contactService;

    /**
     * This is the constructor declaration.
     * @param ContactService $contactService
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Show contact page admin
     * @return view contact list management page
     */
    public function index()
    {
        return view('admin.contact.index');
    }

    /**
     * Show contact table admin
     * @param Request $request
     * @return view contact table
     */
    public function search(Request $request)
    {
        $data = $this->contactService->searchContact($request->searchName);
        return view('admin.contact.table', compact('data'));
    }

    /**
     * Delete contact 
     * @param number $id id of contact 
     * @return response ok
     */
    public function delete($id)
    {
        $this->contactService->deleteContact($id);
        return response()->json('ok');
    }
}
