<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $orderService;

    /**
     * This is the constructor declaration.
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Show dashboard page
     * @return view dashboard page
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getTotalOrderInYear(Request $request)
    {
        $data = $this->orderService->getTotalOrderInYear($request->year);
        return response()->json(['data' => $data]);
    }
}
