<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Services\ContactService;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }
    public function showContact()
    {
        return view('website.contact.index');
    }

    public function create(StoreContactRequest $request)
    {
        $this->contactService->createContact($request);
        return response()->json('ok');
    }
}
