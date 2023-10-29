<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function index()
    {
        return view('admin.order.index');
    }

    public function search(Request $request)
    {
        $data = $this->orderService->searchOrder($request->searchName);
        return view('admin.order.table', ['data' => $data]);
    }

    public function updateStatus(Request $request)
    {
        $data = $this->orderService->updateStatusOrder($request);
        return response()->json(['data' => $data]);
    }
}
