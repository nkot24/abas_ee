<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Order #{{ $order->id }}</title>
</head>
@php
$isLocker = !empty($order->parcel_locker_id);
$isPickup = !$isLocker && !empty($order->parcel_locker_name);
$delivery = $isLocker ? 'Latvijas Pasts locker' : ($isPickup ? 'Pickup on site' : 'Courier');
@endphp
<body style="margin:0;padding:0;background:#f5f5f5;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5;padding:40px 0;">
  <tr>
    <td align="center">
      <table width="580" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:4px;overflow:hidden;">

        <!-- Logo -->
        <tr>
          <td align="center" style="padding:36px 40px 24px;">
            <img src="{{ config('app.url') }}/images/logo.png" alt="ABAS Home Smokehouse" style="height:60px;width:auto;">
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="height:1px;background:#e8e8e8;"></td></tr>

        <!-- Header -->
        <tr>
          <td style="padding:36px 40px 8px;">
            <p style="margin:0 0 6px;font-size:13px;color:#888;letter-spacing:1px;text-transform:uppercase;">Order #{{ $order->id }}</p>
            <h1 style="margin:0;font-size:26px;font-weight:700;color:#1a1a1a;">New order received!</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 40px 28px;">
            <p style="margin:0;font-size:15px;color:#555;line-height:1.6;">Payment confirmed. Order details below.</p>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="height:1px;background:#e8e8e8;"></td></tr>

        <!-- Order summary -->
        <tr>
          <td style="padding:28px 40px 8px;">
            <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#888;letter-spacing:1.5px;text-transform:uppercase;">Order Summary</p>
          </td>
        </tr>

        @foreach($order->items as $item)
        <tr>
          <td style="padding:8px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="font-size:14px;color:#1a1a1a;">{{ $item['name'] }} &times; {{ $item['qty'] }}</td>
                <td align="right" style="font-size:14px;color:#1a1a1a;white-space:nowrap;">{{ number_format($item['price'] * $item['qty'], 2) }} €</td>
              </tr>
            </table>
          </td>
        </tr>
        @endforeach

        <tr>
          <td style="padding:20px 40px 0;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding:6px 0;font-size:14px;color:#888;border-top:1px solid #e8e8e8;">Subtotal</td>
                <td align="right" style="padding:6px 0;font-size:14px;color:#888;border-top:1px solid #e8e8e8;">{{ number_format($order->total, 2) }} €</td>
              </tr>
              <tr>
                <td style="padding:6px 0;font-size:14px;color:#888;">Shipping ({{ $delivery }})</td>
                <td align="right" style="padding:6px 0;font-size:14px;color:#888;">
                  @if($isPickup)
                    <span style="color:#2a9d5c;font-weight:600;">Free</span>
                  @else
                    {{ number_format($order->shipping_cost, 2) }} €
                  @endif
                </td>
              </tr>
              <tr>
                <td style="padding:12px 0 8px;font-size:16px;font-weight:700;color:#1a1a1a;border-top:2px solid #1a1a1a;">Total</td>
                <td align="right" style="padding:12px 0 8px;font-size:16px;font-weight:700;color:#1a1a1a;border-top:2px solid #1a1a1a;">{{ number_format($order->total + $order->shipping_cost, 2) }} €</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="height:1px;background:#e8e8e8;"></td></tr>

        <!-- Customer info -->
        <tr>
          <td style="padding:28px 40px 8px;">
            <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#888;letter-spacing:1.5px;text-transform:uppercase;">Customer Information</p>
            <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#888;letter-spacing:1px;text-transform:uppercase;">Customer</p>
            <p style="margin:0 0 16px;font-size:14px;color:#1a1a1a;font-weight:600;">
              {{ $order->first_name }} {{ $order->last_name }}
            </p>
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="50%" style="vertical-align:top;padding-right:20px;">
                  <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#888;letter-spacing:1px;text-transform:uppercase;">Delivery address</p>
                  <p style="margin:0;font-size:14px;color:#444;line-height:1.7;">
                    {{ $order->address }}<br>
                    {{ $order->city }}, {{ $order->postal_code }}<br>
                    {{ $order->country }}
                  </p>
                  @if($isLocker)
                  <p style="margin:8px 0 0;font-size:13px;color:#555;">
                    📦 <strong>Latvijas Pasts locker:</strong><br>{{ $order->parcel_locker_name }}
                  </p>
                  @elseif($isPickup)
                  <p style="margin:8px 0 0;font-size:13px;color:#555;">
                    🏭 <strong>Pickup address:</strong><br>{{ $order->parcel_locker_name }}
                  </p>
                  @endif
                </td>
                <td width="50%" style="vertical-align:top;">
                  <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#888;letter-spacing:1px;text-transform:uppercase;">Contact</p>
                  <p style="margin:0;font-size:14px;color:#444;line-height:1.7;">
                    {{ $order->email }}<br>
                    {{ $order->phone }}
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding:32px 40px;border-top:1px solid #e8e8e8;margin-top:28px;">
            <p style="margin:0;font-size:13px;color:#999;text-align:center;">ABAS Home Smokehouse admin</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
