<!DOCTYPE html>
<html>
<head>
    <title>{{ __('ami_auto_new_order') }}</title>
</head>
<body>
<h1>{{ __('client') }}</h1>
<p>{{ __('name') }}: {{ $order->user->name }}</p>
<p>{{ __('email') }}: {{ $order->user->email }}</p>
<p>{{ __('order_details') }}</p>
@foreach($order->orderItems as $item)
    <p>{{$item->detail->dt_invoice}} {{ $item->quantity }} * {{ $item->unit_price }}</p>
@endforeach
</body>
</html>
