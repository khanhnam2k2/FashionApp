<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <h2>Order #{{ $order->code }} has been successfully canceled</h2>

    <div style="padding:50px">
        <p>Hello, {{ $order->full_name }}</p>
        <p>Your order #{{ $order->code }} was canceled on {{ $order->updated_at }}</p>
        <div>
            <h3>Your order information</h3>
            <ul>
                <li>Code orders: <span>{{ $order->code }}</span></li>
                <li>Order date:: <span>{{ $order->created_at }}</span></li>
            </ul>
        </div>
        <div class="">
            <a href="{{ route('shop.index') }}" style="padding:10px 20px">Continue Shopping</a>
        </div>

        <p>Please contact us if you have any questions or concerns.</p>
        <ul>
            <li>Phone: 📞+0947837222</li>
            <li>Gmail: 📧 MailFashion@gmail.com</li>
        </ul>

        <p>Best regards,</p>
        <p>Male Fashion Team</p>
        <p>Have questions? Contact us <a href="{{ route('contact.show') }}">here</a></p>

    </div>

</body>

</html>
