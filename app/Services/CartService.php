<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductSizeQuantity;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartService extends BaseService
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
     * Handle add product from cart
     * @param $request
     */
    public function handleAddToCart($request)
    {
        DB::beginTransaction();

        try {
            $product = Product::find($request->productId);

            if (!$product) {
                return response()->json(['error' => 'Sản phẩm không có']);
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
                ->where('quantity', '>', 0)
                ->first();

            if (!$productSizeQuantity) {
                return response()->json(['error' => 'Kích thước này đã hết hàng']);
            }

            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('size', $request->size)
                ->first();
            $newQuantity = intval($request->quantity);
            if ($existingCartItem) {
                if ($existingCartItem->quantity + $newQuantity >= $productSizeQuantity->quantity) {
                    return response()->json(['error' => 'Số lượng sản phẩm trong giỏ hàng của bạn vượt quá số lượng trong kho. Xin lỗi vì sự bất tiện này']);
                }
                $existingCartItem->quantity += $newQuantity;
                $existingCartItem->save();
            } else {
                if ($newQuantity > $productSizeQuantity->quantity) {
                    return response()->json(['error' => 'Số lượng sản phẩm trong giỏ hàng của bạn vượt quá số lượng trong kho. Xin lỗi vì sự bất tiện này']);
                }

                $cartItem = new CartItem();
                $cartItem->cart_id = $cart->id;
                $cartItem->product_id = $product->id;
                $cartItem->size = $request->size;
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            }

            DB::commit();

            return response()->json(['success' => 'Đã thêm sản phẩm thành công', 'quantityAvailable' => $productSizeQuantity->quantity]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Show cart 
     * @return Array data cart
     */
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
                ->join('product_size_quantities', function ($join) {
                    $join->on('products.id', '=', 'product_size_quantities.product_id');
                    $join->on('cart_items.size', '=', 'product_size_quantities.size');
                })
                ->select(
                    'cart_items.cart_id',
                    'cart_items.size',
                    'cart_items.quantity',
                    'products.id as productId',
                    'products.name as productName',
                    'products.price as productPrice',
                    'products.images as productImage',
                    'product_size_quantities.quantity as quantityAvailable'
                )
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy('cart_items.cart_id', 'cart_items.size', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.images', 'product_size_quantities.quantity')
                ->orderBy('cart_items.created_at', 'desc')
                ->get();
            $totalCarts = 0;
            foreach ($cartItems as $item) {
                if ($item->quantity <= $item->quantityAvailable) {
                    $totalCarts += $item->total;
                }
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

    /**
     * Show cart list limit
     * @return Array data cart
     */
    public function showCartLimit()
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
                ->join('product_size_quantities', function ($join) {
                    $join->on('products.id', '=', 'product_size_quantities.product_id');
                    $join->on('cart_items.size', '=', 'product_size_quantities.size');
                })
                ->select(
                    'cart_items.cart_id',
                    'cart_items.size',
                    'cart_items.quantity',
                    'products.id as productId',
                    'products.name as productName',
                    'products.price as productPrice',
                    'products.images as productImage',
                    'product_size_quantities.quantity as quantityAvailable'
                )
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy('cart_items.cart_id', 'cart_items.size', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.images', 'product_size_quantities.quantity')
                ->orderBy('cart_items.created_at', 'desc');

            $countCart = $cartItems->get()->count();
            $cartItemLimit = $cartItems->take(4)->get();


            return [
                'cartItemLimit' => $cartItemLimit,
                'countCart' => $countCart,
            ];
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Show cart in checkout page
     * @return Array data cart
     */
    public function showCartCheckout()
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
                ->join('product_size_quantities', function ($join) {
                    $join->on('products.id', '=', 'product_size_quantities.product_id');
                    $join->on('cart_items.size', '=', 'product_size_quantities.size');
                })
                ->select(
                    'cart_items.cart_id',
                    'cart_items.size',
                    'cart_items.quantity',
                    'products.id as productId',
                    'products.name as productName',
                    'products.price as productPrice',
                    'products.images as productImage',
                    'product_size_quantities.quantity as quantityAvailable'
                )
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy(
                    'cart_items.cart_id',
                    'cart_items.size',
                    'cart_items.quantity',
                    'products.id',
                    'products.name',
                    'products.price',
                    'products.images',
                    'product_size_quantities.quantity'
                )
                ->orderBy('cart_items.created_at', 'desc')
                ->get();
            foreach ($cartItems as $key => $item) {
                if ($item->quantity > $item->quantityAvailable) {
                    CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $item->productId)
                        ->where('size', $item->size)
                        ->delete();

                    unset($cartItems[$key]);
                }
            }

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

    /**
     * Remove products from cart
     * @param @request
     * @return response message 
     */
    public function removeProductFromCart($request)
    {
        DB::beginTransaction();

        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            // cart item delete
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->productId)
                ->where('size', $request->size)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
            }

            // delete cart item
            $cartItem->delete();

            DB::commit();

            return response()->json(['success' => 'Đã xóa thành công sản phẩm khỏi giỏ hàng']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Update cart
     * @param @request
     * @return response message
     */
    public function updateCart($request)
    {
        DB::beginTransaction();

        try {
            $newQuantity = $request->quantity;
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json(['error' => 'Không tìm thấy giỏ hàng']);
            }
            // cart item update
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->productId)
                ->where('size', $request->size)
                ->first();

            if ($cartItem) {
                $productSizeQuantity = ProductSizeQuantity::where('product_id', $request->productId)
                    ->where('size', $request->size)
                    ->first();
                if ($newQuantity <= $productSizeQuantity->quantity) {

                    // Update size and quantity for cart
                    $cartItem->size = $request->size;
                    $cartItem->quantity = $newQuantity;
                    $cartItem->save();
                    DB::commit();
                    return response()->json(['success' => 'Giỏ hàng được cập nhật thành công']);
                } else {
                    return response()->json(['error' => 'Số lượng mới vượt quá số lượng sản phẩm trong kho. Vui lòng chọn lại']);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
