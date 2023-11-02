<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function getTotalOrderInMonth(Request $request)
    {
        $data = $this->orderService->getTotalOrderInMonth($request->year);
        return response()->json(['data' => $data]);
    }
}
