<?php

namespace App\Services;

use App\Enums\StatusOrder;
use App\Mail\OrderCancel;
use App\Mail\OrderSuccess;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderService extends BaseService
{

    public function searchOrder($searchName = null)
    {
        try {
            $orders = Order::select('orders.*', 'users.name as username')
                ->join('users', 'users.id', '=', 'orders.user_id');
            if ($searchName != null && $searchName != '') {
                $orders->where('orders.full_name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('orders.code', 'LIKE', '%' . $searchName . '%');
            }
            $orders = $orders->latest()->paginate(6);
            return $orders;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updateStatusOrder($request)
    {
        try {
            $newStatus = $request->status;
            $order = Order::findOrFail($request->orderId);
            $orderItems = $this->getOrderDetails($order->id);
            $currentStatus = $order->status;
            if ($newStatus == StatusOrder::cancelOrder) {
                $order->status = $newStatus;
                $order->save();
                Mail::to($order->email)->send(new OrderCancel($order, $orderItems));
                return response()->json(['success' => 'Cancel order successfully']);
            } elseif ($newStatus >= $currentStatus && $newStatus <= ($currentStatus + 1)) {
                $order->status = $newStatus;
                $order->save();
                if ($newStatus == StatusOrder::successfulDelivery) {
                    Mail::to($order->email)->send(new OrderSuccess($order, $orderItems));
                }
                return response()->json(['success' => 'Update order status successfully']);
            } else {
                return response()->json(['error' => 'Unable to update status']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

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

    public function getTotalOrderInMonth($year = null)
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
