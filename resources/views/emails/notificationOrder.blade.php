<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thông báo có đơn hàng mới</title>
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
        <p>Xin chào, Admin</p>
        <p>Có 1 đơn đặt hàng mới. Vui lòng kiểm tra và xử lý đơn hàng <a href="{{ route('admin.order.index') }}">tại
                đây</a>.</p>
        <div>
            <h3>Thông tin đơn hàng</h3>
            <p>Mã đơn hàng: <span class="text-danger">{{ $order->code }}</span></p>
            <p>Ngày đặt: <span class="text-danger">{{ $order->created_at }}</span></p>
        </div>
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
        <p>Trân trọng,</p>
        <p>Male Fashion</p>
    </div>

</body>

</html>
