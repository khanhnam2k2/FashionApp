<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
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
        $data = $this->cartService->showCart();
        return view('website.cart.index', compact('data'));
    }

    public function addToCart(Request $request)
    {
        $data = $this->cartService->handleAddToCart($request);
        return response()->json(['data' => $data]);
    }
}
