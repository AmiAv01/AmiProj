<!DOCTYPE html>
<html>
<head>
    <title>АМИ-АВТО Новый заказ</title>
</head>
<body>
<h1>Клиент</h1>
<p>Имя: {{ $order->user->name }}</p>
<p>Email: {{ $order->user->email }}</p>
<p>Детали заказа: </p>
@foreach($order->orderItems as $item)
    <p>{{$item->detail->dt_invoice}} {{ $item->quantity }} * {{ $item->unit_price }}</p>
@endforeach
</body>
</html>
