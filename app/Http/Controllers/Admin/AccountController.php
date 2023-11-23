<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AccountController extends Controller
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
        return view('admin.account.index');
    }

    /**
     * Show user table admin
     * @param Request $request
     * @return view user table
     */
    public function search(Request $request)
    {
        $data = $this->userService->searchCustomer($request->searchName);
        return view('admin.account.table', ['data' => $data]);
    }

    /**
     * Create a new account
     * @param RegisterRequest $request
     * @return response message
     */
    public function create(RegisterRequest $request)
    {
        $data = $this->userService->createAccount($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Update account to admin role
     * @param Request $request
     * @return response message
     */
    public function updateToAdmin(Request $request)
    {
        $data = $this->userService->updateAccountToAdmin($request);
        return response()->json(['data' => $data]);
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
