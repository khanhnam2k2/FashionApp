<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    /**
     * This is the constructor declaration.
     * @param CartService $cartService
     * @param OrderService $orderService
     */
    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Show checkout page in website
     * @return view checkout page
     */
    public function index()
    {
        $data = $this->cartService->showCartCheckout();
        return view('website.checkout.index', [
            'cartItems' => $data['cartItems'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }

    /**
     * Order
     * @return response data message status
     */
    public function placeOrder(OrderRequest $request)
    {
        $data = $this->orderService->placeOrder($request);
        return response()->json(['data' => $data]);
    }
}
