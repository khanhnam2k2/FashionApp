<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $data = $this->cartService->showCart();
        return view('website.checkout.index', [
            'cartItems' => $data['cartItems'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }

    public function placeOrder(OrderRequest $request)
    {
        $this->cartService->placeOrder($request);
        return response()->json('ok');
    }
}
