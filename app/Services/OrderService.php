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
                if ($user->role == UserRole::ADMIN || $order->status == StatusOrder::orderPlaced) {
                    $order->status = $newStatus;
                    $order->save();
                    Mail::to($order->email)->send(new OrderCancel($order, $orderItems));
                    return response()->json(['success' => 'Hủy bỏ đơn hàng thành công']);
                } else {
                    return response()->json(['error' => 'Bạn không thể hủy đơn hàng này khi đã đơn hàng đã được xác nhận!']);
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
     * url payment vnpay
     * @param String $code code order
     * @param String $total total amount order
     * @return String url payment vnpay
     */
    public function vnPayUrlAction($code, $total)
    {
        try {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "http://127.0.0.1:8000/vnpay-return";
            $vnp_TmnCode = "7W9NWV1X"; //Mã website tại VNPAY 
            $vnp_HashSecret = "OJKLRBTXCAVUZECPJSFIZZUUFMTOQYPI"; //Chuỗi bí mật

            $vnp_TxnRef = $code; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = "Thanh toán hóa đơn";
            $vnp_OrderType = "Male Shop";
            $vnp_Amount = $total * 100;
            $vnp_Locale = "vn";
            $vnp_BankCode = "NCB";
            $vnp_IpAddr = "http://127.0.0.1";

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            return $vnp_Url;
        } catch (Exception $e) {
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
            $orderCode = $this->generateRandomCode();

            $orderData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'message' => $request->message,
                'user_id' => $user->id,
                'code' => $orderCode,
                'total_order' => $request->total_order
            ];

            // Check payment method
            if ($request->paymet_method == 1) {

                Session::put('orders', $orderData); //Save order data into session

                $vnp_Url = $this->vnPayUrlAction($orderCode, $request->total_order);

                // Redirect users to VNPay payment page
                return response()->json(['redirect_url' => $vnp_Url]);
            } else {
                $order = Order::create($orderData);
                $this->processOrder($order, $request);
                DB::commit();
                return response()->json(['success' => 'Đặt hàng thành công! Vui lòng kiểm tra đơn mua của bạn']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * handle Vnpay return 
     * @param $request
     * @return boolean
     */
    public function handleVnPayReturn($request)
    {
        try {
            $vnp_ResponseCode = $request->get('vnp_ResponseCode');
            $vnp_TransactionNo  = $request->get('vnp_TransactionNo');
            if ($vnp_ResponseCode == '00') {
                // Successful payment by vnpay
                $orderData = Session::get('orders'); // get order data from session
                $order = Order::create($orderData);
                if ($order) {
                    $order->payment_method = 'vnpay';
                    $order->payment_status = 'success';
                    $order->transaction = $vnp_TransactionNo;
                    $order->save();
                    $this->processOrder($order);

                    Session::forget('orders'); // clear session

                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
