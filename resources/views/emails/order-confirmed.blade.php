<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Užsakymo patvirtinimas</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f5;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5;padding:40px 0;">
  <tr>
    <td align="center">
      <table width="580" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:4px;overflow:hidden;">

        <!-- Logo -->
        <tr>
          <td align="center" style="padding:36px 40px 24px;">
            <img src="{{ env('APP_URL') }}/images/logo.png" alt="ABAS Home Smokehouse" style="height:60px;width:auto;">
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="height:1px;background:#e8e8e8;"></td></tr>

        <!-- Header -->
        <tr>
          <td style="padding:36px 40px 8px;">
            <p style="margin:0 0 6px;font-size:13px;color:#888;letter-spacing:1px;text-transform:uppercase;">Užsakymas #{{ $order->id }}</p>
            <h1 style="margin:0;font-size:26px;font-weight:700;color:#1a1a1a;">Ačiū už jūsų užsakymą!</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 40px 28px;">
            <p style="margin:0;font-size:15px;color:#555;line-height:1.6;">Sveiki, {{ $order->first_name }} {{ $order->last_name }}! Jūsų užsakymas apmokėtas ir bus ruošiamas išsiuntimui. Susisieksime su jumis dėl pristatymo detalių.</p>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="height:1px;background:#e8e8e8;margin:0 40px;"></td></tr>

        <!-- Order summary -->
        <tr>
          <td style="padding:28px 40px 8px;">
            <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#888;letter-spacing:1.5px;text-transform:uppercase;">Užsakymo suvestinė</p>
          </td>
        </tr>

        @foreach($order->items as $item)
        @php $product = \App\Models\Product::find($item['id']); $img = $product?->mainImage?->url; @endphp
        <tr>
          <td style="padding:8px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                @if($img)
                <td width="52" style="vertical-align:middle;padding-right:12px;">
                  <img src="{{ env('APP_URL') }}{{ $img }}" width="48" height="48" style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:1px solid #eee;">
                </td>
                @endif
                <td style="font-size:14px;color:#1a1a1a;vertical-align:middle;">{{ $item['name'] }} &times; {{ $item['qty'] }}</td>
                <td align="right" style="font-size:14px;color:#1a1a1a;white-space:nowrap;vertical-align:middle;">{{ number_format($item['price'] * $item['qty'], 2) }} €</td>
              </tr>
            </table>
          </td>
        </tr>
        @endforeach

        <!-- Subtotal / shipping / total -->
        <tr>
          <td style="padding:20px 40px 0;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding:6px 0;font-size:14px;color:#888;border-top:1px solid #e8e8e8;">Tarpinė suma</td>
                <td align="right" style="padding:6px 0;font-size:14px;color:#888;border-top:1px solid #e8e8e8;">{{ number_format($order->total, 2) }} €</td>
              </tr>
              <tr>
                <td style="padding:6px 0;font-size:14px;color:#888;">Pristatymas</td>
                <td align="right" style="padding:6px 0;font-size:14px;color:#2a9d5c;font-weight:600;">Nemokamas</td>
              </tr>
              <tr>
                <td style="padding:12px 0 8px;font-size:16px;font-weight:700;color:#1a1a1a;border-top:2px solid #1a1a1a;">Viso</td>
                <td align="right" style="padding:12px 0 8px;font-size:16px;font-weight:700;color:#1a1a1a;border-top:2px solid #1a1a1a;">{{ number_format($order->total, 2) }} €</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="height:1px;background:#e8e8e8;margin:0 40px;"></td></tr>

        <!-- Customer info -->
        <tr>
          <td style="padding:28px 40px 8px;">
            <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#888;letter-spacing:1.5px;text-transform:uppercase;">Kliento informacija</p>
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="50%" style="vertical-align:top;padding-right:20px;">
                  <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#888;letter-spacing:1px;text-transform:uppercase;">Pristatymo adresas</p>
                  <p style="margin:0;font-size:14px;color:#444;line-height:1.7;">
                    {{ $order->first_name }} {{ $order->last_name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->city }}, {{ $order->postal_code }}<br>
                    {{ $order->country }}
                  </p>
                </td>
                <td width="50%" style="vertical-align:top;">
                  <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#888;letter-spacing:1px;text-transform:uppercase;">Kontaktai</p>
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
            <p style="margin:0;font-size:13px;color:#999;text-align:center;line-height:1.6;">
              Jei turite klausimų, susisiekite su mumis:<br>
              <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" style="color:#c0392b;text-decoration:none;">{{ env('MAIL_FROM_ADDRESS') }}</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
