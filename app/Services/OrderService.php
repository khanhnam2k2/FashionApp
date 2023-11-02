<?php

namespace App\Services;

use App\Enums\StatusOrder;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderService
{

    public function searchOrder($searchName = null)
    {
        try {
            $orders = Order::select('orders.*');
            if ($searchName != null && $searchName != '') {
                $orders->where('orders.full_name', 'LIKE', '%' . $searchName . '%');
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
            $currentStatus = $order->status;
            if ($newStatus == StatusOrder::cancelOrder) {
                $order->status = $newStatus;
                $order->save();
                return response()->json(['success' => 'Cancel order successfully']);
            } elseif ($newStatus >= $currentStatus && $newStatus <= ($currentStatus + 1)) {
                $order->status = $newStatus;
                $order->save();

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
}
