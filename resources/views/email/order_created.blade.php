<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{{ __('ami_auto_new_order') }}</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fa;color:#262626;font-family:Arial,Helvetica,sans-serif;">
<div style="max-width:760px;margin:0 auto;background:#ffffff;padding:32px;border-radius:8px;">
    <p style="margin:0 0 8px;color:#666666;font-size:14px;">Новый заказ №{{ $order->id }}</p>
    <h1 style="margin:0 0 20px;font-size:24px;line-height:1.3;">{{ $order->user->name }}</h1>

    <p style="margin:0 0 6px;"><strong>Email:</strong> {{ $order->user->email }}</p>
    @if($order->user->phone_number)
        <p style="margin:0 0 6px;"><strong>Телефон:</strong> {{ $order->user->phone_number }}</p>
    @endif
    <p style="margin:0 0 20px;"><strong>Дата заказа:</strong> {{ $order->created_at?->format('d.m.Y H:i') }}</p>

    @if($currencyRate !== null)
        <h2 style="margin:24px 0 16px;font-size:20px;">Курс пересчёта {{ number_format((float) $currencyRate, 2, ',', ' ') }}</h2>
    @endif

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
        <tr style="background:#2176e8;color:#ffffff;">
            <th align="left" style="padding:13px 15px;">Наименование</th>
            <th align="center" style="padding:13px 15px;white-space:nowrap;">Кол-во</th>
            <th align="right" style="padding:13px 15px;white-space:nowrap;">Цена {{ $currencyCode }}</th>
            <th align="center" style="padding:13px 15px;">Наличие</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $item)
            <tr style="background:{{ $loop->even ? '#f4f5f7' : '#ffffff' }};border-bottom:1px solid #d9dce1;">
                <td style="padding:13px 15px;line-height:1.45;">
                    <strong>{{ $item->detail?->dt_invoice ?? 'Товар #'.$item->detail_id }}</strong>
                    @if($item->detail?->dt_typec)
                        <br>{{ $item->detail->dt_typec }}
                    @endif
                </td>
                <td align="center" style="padding:13px 15px;">{{ $item->quantity }}</td>
                <td align="right" style="padding:13px 15px;font-weight:700;white-space:nowrap;">
                    {{ number_format((float) $item->unit_price, 2, ',', ' ') }}
                </td>
                <td align="center" style="padding:13px 15px;color:#238636;font-weight:700;">
                    {{ $item->detail?->stock?->ostc ?? '—' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p style="margin:20px 0 0;text-align:right;font-size:18px;">
        <strong>Итого: {{ number_format((float) $order->total_price, 2, ',', ' ') }} {{ $currencyCode }}</strong>
    </p>

    @if($order->comment)
        <div style="margin-top:24px;padding:16px;background:#f4f5f7;border-left:4px solid #2176e8;">
            <strong>Комментарий к заказу:</strong>
            <div style="margin-top:8px;white-space:pre-line;">{{ $order->comment }}</div>
        </div>
    @endif
</div>
</body>
</html>
