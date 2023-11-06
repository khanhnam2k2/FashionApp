<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
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
     * Show order page admin
     * @return view order list management page
     */
    public function index()
    {
        return view('admin.order.index');
    }

    /**
     * Show order table admin
     * @param Request $request
     * @return view order table
     */
    public function search(Request $request)
    {
        $data = $this->orderService->searchOrder($request->searchName);
        return view('admin.order.table', ['data' => $data]);
    }

    /**
     * Update status order
     * @param Request $request
     * @return response data message status
     */
    public function updateStatus(Request $request)
    {
        $data = $this->orderService->updateStatusOrder($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Delete order 
     * @param number $id id of order 
     * @return response ok
     */
    public function delete($id)
    {
        $this->orderService->deleteOrder($id);
        return response()->json('ok');
    }

    /**
     * Show order details page admin
     * @return view order details list management page
     */
    public function details($id)
    {
        return view('admin.orderDetails.details', compact('id'));
    }

    /**
     * Show order details table admin
     * @param Request $request
     * @return view order details table
     */
    public function searchDetails(Request $request)
    {
        $data = $this->orderService->searchDetailsOrder($request->orderId, $request->searchName);
        return view('admin.orderDetails.table', ['data' => $data]);
    }
}
