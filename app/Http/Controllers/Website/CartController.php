<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return view('website.cart.index');
    }

    public function search()
    {
        $data = $this->cartService->showCart();
        return view('website.cart.table', [
            'cartItems' => $data['cartItems'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }
    public function addToCart(CartRequest $request)
    {
        $data = $this->cartService->handleAddToCart($request);
        return response()->json(['data' => $data]);
    }

    public function removeProduct(Request $request)
    {
        $this->cartService->removeProductFromCart($request);
        return response()->json('ok');
    }
}
