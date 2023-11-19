<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    /**
     * This is the constructor declaration.
     * @param CartService $cartService
     * @param OrderService $orderService
     */
    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Show checkout page in website
     * @return view checkout page
     */
    public function index()
    {
        $data = $this->cartService->showCartCheckout();
        return view('website.checkout.index', [
            'cartItems' => $data['cartItems'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }

    /**
     * Order
     * @return response data message status
     */
    public function placeOrder(OrderRequest $request)
    {
        $data = $this->orderService->placeOrder($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Vnpay Payment
     * @param Request $request
     * @return view
     */
    public function vnpayReturn(Request $request)
    {
        $data = $this->orderService->handleVnPayReturn($request);
        if ($data == true) {
            return redirect()->route('home')->with('success', 'Thanh toán bằng ví thành công! Vui lòng kiểm tra đơn mua của bạn để biết thêm thông tin');
        } else {
            return redirect()->route('home')->with('error', 'Thanh toán bằng ví thất bại! Vui lòng thử lại');
        }
    }
}
