<?php

namespace App\Http\Controllers\Website;

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
     * Show order user page in website
     * @return view order user page
     */
    public function index()
    {
        return view('website.order.index');
    }

    /**
     * Show order list 
     * @param Request $request
     * @return view order list
     */
    public function search(Request $request)
    {
        $data = $this->orderService->searchOrderUser(null, $request->statusOrder);
        return view('website.order.orderList', ['data' => $data]);
    }

    /**
     * Show order details list 
     * @param Request $request
     * @return view order details list
     */
    public function searchDetails(Request $request)
    {
        $data = $this->orderService->searchDetailsOrder($request->orderId, null, $request->paginate);
        return view('website.order.orderDetailsList', ['data' => $data]);
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
}
