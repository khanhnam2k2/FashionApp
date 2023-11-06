<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $userService;

    /**
     * This is the constructor declaration.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show user page admin
     * @return view user list management page
     */
    public function index()
    {
        return view('admin.customer.index');
    }

    /**
     * Show user table admin
     * @param Request $request
     * @return view user table
     */
    public function search(Request $request)
    {
        $data = $this->userService->searchCustomer($request->searchName);
        return view('admin.customer.table', ['data' => $data]);
    }

    /**
     * Delete user 
     * @param number $id id of user 
     * @return response ok
     */
    public function delete($id)
    {
        $this->userService->deleteCustomer($id);
        return response()->json('ok');
    }
}
