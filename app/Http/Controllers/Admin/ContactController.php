<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        return view('admin.contact.index');
    }

    public function search(Request $request)
    {
        $data = $this->contactService->searchContact($request->searchName);
        return view('admin.contact.table', compact('data'));
    }

    public function delete($id)
    {
        $this->contactService->deleteContact($id);
        return response()->json('ok');
    }
}
