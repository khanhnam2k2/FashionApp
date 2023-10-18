<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductSizeQuantity;
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
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();


            // check cart exists
            if (!$cart) {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->save();
            }

            $productSizeQuantity = ProductSizeQuantity::where('product_id', $product->id)
                ->where('size', $request->size)
                ->first();
            if (!$productSizeQuantity) {
                return response()->json(['error' => 'Product size not found']);
            }

            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('size', $request->size)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $request->quantity;

                if ($request->quantity > $productSizeQuantity->quantity) {
                    return response()->json(['error' => 'The number of products in the shopping cart exceeds the quantity in stock']);
                }
                $existingCartItem->quantity = $newQuantity;
                $existingCartItem->save();
            } else {
                if ($request->quantity > $productSizeQuantity->quantity) {
                    return response()->json(['error' => 'The number of products in the shopping cart exceeds the quantity in stock']);
                }
                $cartItem = new CartItem();
                $cartItem->cart_id = $cart->id;
                $cartItem->product_id = $product->id;
                $cartItem->size = $request->size;
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            }

            //Subtract the number of products in the Product_Size_quantities
            $productSizeQuantity->quantity -= $request->quantity;
            $productSizeQuantity->save();

            return response()->json(['success' => 'Product added successfully', 'quantityAvailable' => $product->quantity]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function showCart()
    {
        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->save();
            }
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->select('cart_items.cart_id', 'cart_items.size', 'cart_items.quantity', 'products.id as productId', 'products.name as productName', 'products.price as productPrice', 'products.image as productImage',)
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy('cart_items.cart_id', 'cart_items.size', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.image')
                ->get();

            $totalCarts = 0;
            foreach ($cartItems as $item) {
                $totalCarts += $item->total;
            }
            return [
                'cartItems' => $cartItems,
                'totalCarts' => $totalCarts
            ];
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function removeProductFromCart($request)
    {
        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            // cart item delete
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->productId)
                ->where('size', $request->size)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Product not found in the cart']);
            }

            // delete cart item
            $cartItem->delete();

            $productSizeQuantity = ProductSizeQuantity::where('product_id', $request->productId)
                ->where('size', $request->size)
                ->first();

            if (!$productSizeQuantity) {
                return response()->json(['error' => 'Product size not found']);
            }

            $productSizeQuantity->quantity += $cartItem->quantity;
            $productSizeQuantity->save();

            return response()->json(['success' => 'Successfully removed the product from the cart']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updateCart($request)
    {
        try {
            $newQuantity = $request->quantity;
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return false;
            }
            // cart item update
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->productId)
                ->where('size', $request->size)
                ->first();

            if ($cartItem) {
                $product = Product::findOrFail($request->productId);
                if ($newQuantity <= $product->quantity) {
                    $product->quantity += $cartItem->quantity - $newQuantity;
                    $product->save();

                    // update size and quantity for cart
                    $cartItem->size = $request->size;
                    $cartItem->quantity = $newQuantity;
                    $cartItem->save();

                    return response()->json(['success' => 'Cart updated successfully']);
                } else {
                    return response()->json(['error' => 'New quantity exceeds available quantity']);
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
