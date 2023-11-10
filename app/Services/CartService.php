<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Mail\OrderNotification;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSizeQuantity;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            $newQuantity = $request->quantity;
            if ($existingCartItem) {
                if ($existingCartItem->quantity >= $productSizeQuantity->quantity) {
                    return response()->json(['error' => 'Số lượng sản phẩm trong giỏ hàng vượt quá số lượng trong kho. Xin lỗi vì sự bất tiện này']);
                }
                $existingCartItem->quantity += $newQuantity;
                $existingCartItem->save();
            } else {
                if ($newQuantity > $productSizeQuantity->quantity) {
                    return response()->json(['error' => 'Số lượng sản phẩm trong giỏ hàng vượt quá số lượng trong kho. Xin lỗi vì sự bất tiện này']);
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

    /**
     * Order
     * @param $request
     */
    public function placeOrder($request)
    {
        DB::beginTransaction();

        try {
            $user  = Auth::user();

            $orderData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'message' => $request->message,
                'user_id' => $user->id,
                'code' => $this->generateRandomCode(),
                'total_order' => $request->total_order
            ];

            $order = Order::create($orderData);

            $cart = Cart::where('user_id', $user->id)->first();

            $cartItems = CartItem::select('cart_items.*', 'products.price as productPrice')
                ->where('cart_id', $cart->id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->get();

            $orderItemData = [];
            foreach ($cartItems as  $item) {
                $productSizeQuantity = ProductSizeQuantity::where('product_id', $item->product_id)
                    ->where('size', $item->size)
                    ->first();
                if (!$productSizeQuantity || $productSizeQuantity->quantity < $item->quantity) {
                    DB::rollBack();
                    return response()->json(['error' => 'Không đủ số lượng sản phẩm cho một số sản phẩm. Vui lòng kiểm tra lại']);
                }

                $orderItemData[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->productPrice,
                    'size' => $item->size,
                    'quantity' => $item->quantity
                ];

                $productSizeQuantity->quantity -= $item->quantity;
                $productSizeQuantity->save();
            }

            DB::table('order_items')->insert($orderItemData);
            CartItem::where('cart_id', $cart->id)->delete();

            $adminEmails = User::where('role', UserRole::ADMIN)->pluck('email')->toArray();
            $orderItems = $this->getOrderDetails($order->id);

            Mail::to($adminEmails)->send(new OrderNotification($order, $orderItems));
            DB::commit();

            return response()->json(['success' => 'Đặt hàng thành công! Vui lòng kiểm tra đơn mua của bạn']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
