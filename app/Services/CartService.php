<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartService
{
    public function handleAddToCart($request)
    {
        try {
            $product = Product::find($request->productId);

            if (!$product) {
                return response()->json(['error' => 'Product not found']);
            }

            if (Auth::check()) {
                $user = Auth::user();

                $cart = Cart::where('user_id', $user->id)->first();

                if (!$cart) {
                    $cart = new Cart();
                    $cart->user_id = $user->id;
                    $cart->save();
                }
            }
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();
            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $request->quantity;

                if ($newQuantity > $product->quantity) {
                    return response()->json(['error' => 'The number of products in the shopping cart exceeds the quantity in stock']);
                }

                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->save();
            } else {
                if ($request->quantity > $product->quantity) {
                    return response()->json(['error' => 'The number of products in the shopping cart exceeds the quantity in stock']);
                }
                $cartItem = new CartItem();
                $cartItem->cart_id = $cart->id;
                $cartItem->product_id = $product->id;
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            }

            return response()->json(['success' => 'Product added successfully']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function showCart()
    {
        try {
            if (Auth::check()) {
                $user  = Auth::user();
                $cart = Cart::where('user_id', $user->id)->first();
            }
            if (!$cart) {
                return redirect()->route('website.cart.index')->with('message', 'Giỏ hàng của bạn đang trống.');
            }
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->select('cart_items.cart_id', 'cart_items.quantity', 'products.name as productName', 'products.price as productPrice', 'products.image as productImage')
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy('cart_items.cart_id', 'cart_items.quantity', 'products.name', 'products.price', 'products.image')
                ->get();

            return $cartItems;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
