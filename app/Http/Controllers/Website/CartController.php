<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
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
     * Show cart page in website
     * @return view cart page
     */
    public function index()
    {
        return view('website.cart.index');
    }

    /**
     * Show cart table in website
     * @return view cart table
     */
    public function search()
    {
        $data = $this->cartService->showCart();
        return view('website.cart.table', [
            'cartItems' => $data['cartItems'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }

    /**
     * Show cart list in website
     * @return view cart list
     */
    public function searchLimit()
    {
        $data = $this->cartService->showCartLimit();
        return view('website.cart.listCart', [
            'cartItemLimit' => $data['cartItemLimit'],
            'countCart' => $data['countCart'],
        ]);
    }

    /**
     * Add product to cart
     * @param CartRequest $request
     * @return response data message status
     */
    public function addToCart(CartRequest $request)
    {
        $data = $this->cartService->handleAddToCart($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Remove product from cart
     * @param Request $request
     * @return response data message status
     */
    public function removeProduct(Request $request)
    {
        $data = $this->cartService->removeProductFromCart($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Update cart
     * @param Request $request
     * @return response data message status
     */
    public function updateCart(CartRequest $request)
    {
        $data = $this->cartService->updateCart($request);
        return response()->json(['data' => $data]);
    }

    public function getTotalProductInCart()
    {
        $data = $this->cartService->getTotalProductInCart();
        return response()->json(['data' => $data]);
    }
}
