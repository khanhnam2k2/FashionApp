<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function delete($id)
    {
        $this->orderService->deleteOrder($id);
        return response()->json('ok');
    }

    public function details($id)
    {
        return view('admin.orderDetails.details', compact('id'));
    }

    public function searchDetails(Request $request)
    {
        $data = $this->orderService->searchDetailsOrder($request->orderId, $request->searchName);
        return view('admin.orderDetails.table', ['data' => $data]);
    }
}
