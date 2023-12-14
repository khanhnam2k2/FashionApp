<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Xác nhận giao hàng thành công</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            margin-top: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-danger {
            color: rgb(215, 75, 75);
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Xin chào, {{ $order->full_name }}</p>
        <p>Đơn hàng #{{ $order->code }} của bạn đã được giao thành công ngày
            {{ $order->updated_at->format('d/m/y') }}
        </p>
        <hr>
        <h4>Thông tin đơn hàng - Dành cho người mua</h4>
        <p>Mã đơn hàng: <span class="text-danger">{{ $order->code }}</span></p>
        <p>Ngày đặt hàng: <span class="text-danger">{{ $order->created_at }}</span></p>

        <div>
            <table>
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $item)
                        <tr>
                            <td>{{ $item->productName }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <p>Tổng tiền đơn hàng: <span>{{ number_format($order->total_order, 0, ',', '.') }}đ</span></p>
            <p>Phí vận chuyển: <span>0đ</span></p>
            <p>Tổng tiền thanh toán: <span>{{ number_format($order->total_order, 0, ',', '.') }}đ</span></p>
        </div>

        <p>Cảm ơn bạn đã tin tưởng Male Fashion.</p>
        <p>Vui lòng liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi hoặc mối quan tâm nào.</p>
        <ul>
            <li>SDT: 📞+0947837222</li>
            <li>Gmail: 📧 MailFashion@gmail.com</li>
        </ul>

        <p>Trân trọng,</p>
        <p>Male Fashion</p>
    </div>

</body>

</html>
