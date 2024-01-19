<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Đơn hàng bị hủy</title>
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

        .text-danger {
            color: rgb(215, 75, 75);
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Xin chào, {{ $order->full_name }}</p>
        <p>Đơn hàng #{{ $order->code }} của bạn được hủy vào {{ $order->updated_at->format('d/m/y') }}</p>
        <div>
            <h3>Thông tin đơn hàng</h3>
            <p>Mã đơn hàng: <span class="text-danger">{{ $order->code }}</span></p>
            <p>Ngày đặt: <span class="text-danger">{{ $order->created_at }}</span></p>
            <p>Lý do hủy: <span class="text-danger">{{ $order->cancellationReason }}</span></p>
        </div>

        <p>Vui lòng liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi hoặc mối quan tâm nào.</p>
        <ul>
            <li>Phone: 📞+0947837222</li>
            <li>Gmail: 📧 MailFashion@gmail.com</li>
        </ul>

        <p>Trân trọng,</p>
        <p>Male Fashion</p>
    </div>

</body>

</html>
