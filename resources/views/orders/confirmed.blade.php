<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заказ подтверждён</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fa;color:#262626;font-family:Arial,Helvetica,sans-serif;">
<main style="max-width:640px;margin:80px auto;background:#ffffff;padding:40px;border-radius:10px;text-align:center;box-shadow:0 8px 24px rgba(0,0,0,.08);">
    <h1 style="margin:0 0 16px;font-size:28px;">Заказ №{{ $order->order_number }}</h1>
    @if($confirmedNow)
        <p style="margin:0;font-size:18px;line-height:1.5;">Заказ подтверждён. Клиенту отправлено уведомление.</p>
    @else
        <p style="margin:0;font-size:18px;line-height:1.5;">Этот заказ уже был подтверждён.</p>
    @endif
</main>
</body>
</html>
