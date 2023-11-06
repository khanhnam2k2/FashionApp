<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;

class CheckoutController extends Controller
{
    protected $cartService;

    /**
     * This is the constructor declaration.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
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
        $data = $this->cartService->placeOrder($request);
        return response()->json(['data' => $data]);
    }
}
