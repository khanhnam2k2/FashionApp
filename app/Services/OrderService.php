<?php

namespace App\Services;

use App\Enums\StatusOrder;
use App\Enums\UserRole;
use App\Mail\OrderCancel;
use App\Mail\OrderNotification;
use App\Mail\OrderSuccess;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSizeQuantity;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OrderService extends BaseService
{
    /**
     * Get order list by status order
     * @param number $statusOrder status order
     * @return Array order list
     */
    public function getOrderByStatus($statusOrder = null)
    {
        try {
            $user = Auth::user();

            $userOrders = Order::select(
                'orders.*',
                'order_items.*',
                'products.name as productName',
                'products.images as productImages'
            )
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('user_id', $user->id);

            if ($statusOrder != null && $statusOrder != '') {
                $userOrders->where('orders.status', '=', $statusOrder);
            }
            $userOrders = $userOrders->orderBy('orders.created_at', 'desc')->paginate(4);

            return $userOrders;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
    /**
     * Get order list paginate
     * @param String $searchName keyword search
     * @param number $statusOrder status order
     * @return Array order list
     */
    public function searchOrder($searchName = null, $statusOrder = null)
    {
        try {
            $orders = Order::select('orders.*', 'users.name as username')
                ->join('users', 'users.id', '=', 'orders.user_id');

            if ($searchName != null && $searchName != '') {
                $orders = $orders->where('orders.full_name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('orders.code', 'LIKE', '%' . $searchName . '%');
            }

            if ($statusOrder != null && $statusOrder != '') {
                $orders->where('orders.status', '=', $statusOrder);
            }

            $orders = $orders->latest()->paginate(6);

            return $orders;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }


    /**
     * Update status order
     * @param @request
     * @return response message
     */
    public function updateStatusOrder($request)
    {
        try {
            $user = Auth::user();
            $newStatus = $request->status;
            $order = Order::findOrFail($request->orderId);
            $orderItems = $this->getOrderDetails($order->id);
            $currentStatus = $order->status;
            if ($newStatus == StatusOrder::cancelOrder) {
                $cancellationReason = $request->cancellationReason;
                if ($cancellationReason == null || $cancellationReason == '') {
                    return response()->json(['error' => 'Vui lòng nhập lý do hủy đơn hàng']);
                } else {
                    if ($user->role == UserRole::ADMIN || $order->status == StatusOrder::orderPlaced) {
                        $order->status = $newStatus;
                        $order->cancellationReason = $cancellationReason;
                        $order->save();
                        Mail::to($order->email)->send(new OrderCancel($order, $orderItems));
                        return response()->json(['success' => 'Hủy bỏ đơn hàng thành công']);
                    } else {
                        return response()->json(['error' => 'Bạn không thể hủy đơn hàng này khi đã đơn hàng đã được xác nhận!']);
                    }
                }
            } elseif ($newStatus >= $currentStatus && $newStatus <= ($currentStatus + 1)) {
                $order->status = $newStatus;
                $order->save();

                if ($newStatus == StatusOrder::successfulDelivery) {
                    Mail::to($order->email)->send(new OrderSuccess($order, $orderItems));
                }
                return response()->json(['success' => 'Cập nhật trạng thái đơn hàng thành công']);
            } else {
                return response()->json(['error' => 'Vui lòng cập nhật theo thứ tự']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Delete order
     * @param number $id id of order
     * @return true
     */
    public function deleteOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            $orderDetail = OrderItem::where('order_id', $order->id);

            $order->delete();
            $orderDetail->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Get order details list paginate
     * @return Array order details list
     */
    public function searchDetailsOrder($orderId, $searchName = null, $paginate = 3)
    {
        try {
            $order = Order::findOrFail($orderId);

            $orderDetail = OrderItem::select(
                'order_items.order_id',
                'order_items.quantity',
                'order_items.price',
                'order_items.size',
                'products.id as productId',
                'products.name as productName',
                'products.images as productImages'
            )
                ->selectRaw('SUM(order_items.quantity * order_items.price) as total')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('order_id', $order->id)
                ->groupBy(
                    'order_items.order_id',
                    'order_items.quantity',
                    'order_items.price',
                    'order_items.size',
                    'products.id',
                    'products.name',
                    'products.images'
                );

            if ($searchName != null && $searchName != '') {
                $orderDetail = $orderDetail->where('products.name', 'LIKE', '%' . $searchName . '%');
            }

            if ($paginate != null && $paginate != '') {
                $orderDetail = $orderDetail->paginate($paginate);
            } else {
                $orderDetail = $orderDetail->get();
            }
            return $orderDetail;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * get total order in year
     * @param date year
     * @return Array array total order in year
     */
    public function getTotalOrderInYear($year = null)
    {
        try {
            $currentYear = Carbon::now()->year;

            $totalOrdersByMonth = DB::table('orders')
                ->selectRaw('MONTH(created_at) as month, SUM(total_order) as total_order')
                ->whereYear('created_at', $year ?? $currentYear)
                ->where('status', '=', 4)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total_order', 'month')
                ->all();

            $monthlyTotalOrders = array_fill(1, 12, 0);

            foreach ($totalOrdersByMonth as $month => $totalOrder) {
                $monthlyTotalOrders[$month] = $totalOrder;
            }

            return $monthlyTotalOrders;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * process order
     * @param Obecjt $order data
     * @return Array array total order in year
     */
    public function processOrder($order)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        $cartItems = CartItem::select('cart_items.*', 'products.price as productPrice')
            ->where('cart_id', $cart->id)
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->get();

        $orderItemData = [];
        foreach ($cartItems as $item) {
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
            $orderCode = $this->generateRandomCode();

            $orderData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address_details' => $request->address_details,
                'city' => $request->city,
                'district' => $request->district,
                'ward' => $request->ward,
                'message' => $request->message,
                'user_id' => $user->id,
                'code' => $orderCode,
                'total_order' => $request->total_order
            ];
            $order = Order::create($orderData);

            $this->processOrder($order, $request);

            DB::commit();
            return response()->json(['success' => 'Đặt hàng thành công! Vui lòng kiểm tra đơn mua của bạn']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
