<?php

namespace App\Services;

use App\Enums\StatusOrder;
use App\Enums\UserRole;
use App\Mail\OrderCancel;
use App\Mail\OrderSuccess;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                $orders->where('orders.full_name', 'LIKE', '%' . $searchName . '%')
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
                if ($user->role == UserRole::ADMIN || $order->status == StatusOrder::orderPlaced) {
                    $order->status = $newStatus;
                    $order->save();
                    Mail::to($order->email)->send(new OrderCancel($order, $orderItems));
                    return response()->json(['success' => 'Hủy bỏ đơn hàng thành công']);
                } else {
                    return response()->json(['error' => 'Bạn không có quyền cập nhật trạng thái cho đơn đặt hàng này!']);
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
    public function searchDetailsOrder($orderId, $searchName = null)
    {
        try {
            $order = Order::findOrFail($orderId);

            $orderDetail = OrderItem::select('order_items.*', 'products.name as productName', 'products.images as productImages')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('order_id', $order->id);

            if ($searchName != null && $searchName != '') {
                $orderDetail->where('products.name', 'LIKE', '%' . $searchName . '%');
            }

            $orderDetail = $orderDetail->paginate(3);

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
}
