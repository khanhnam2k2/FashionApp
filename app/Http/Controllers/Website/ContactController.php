<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Services\ContactService;

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
     * Show contact page 
     * @return view contact page
     */
    public function index()
    {
        return view('website.contact');
    }

    /**
     * Create new contact 
     * @param StoreContactRequest $request 
     * @return response ok
     */
    public function create(StoreContactRequest $request)
    {
        $this->contactService->createContact($request);
        return response()->json('ok');
    }
}
