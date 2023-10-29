<?php

namespace App\Services;

use App\Models\Order;
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

            if ($newStatus >= $currentStatus && $newStatus <= ($currentStatus + 1)) {
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
}
