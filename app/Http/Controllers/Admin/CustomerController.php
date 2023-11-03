<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        return view('admin.customer.index');
    }

    public function search(Request $request)
    {
        $data = $this->userService->searchCustomer($request->searchName);
        return view('admin.customer.table', ['data' => $data]);
    }

    public function delete($id)
    {
        $this->userService->deleteCustomer($id);
        return response()->json('ok');
    }
}
