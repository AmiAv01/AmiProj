<!DOCTYPE html>
<html>
<head>
    <title>АМИ-АВТО Новый заказ</title>
</head>
<body>
<h1>Клиент</h1>
<p>Имя: {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>
<p>Детали заказа: </p>
@foreach($orderItems as $item)
    <p>{{$item->}}</p>
@endforeach
</body>
</html>
