<!DOCTYPE html>
<html lang="et">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Personal data deleted</title>
</head>
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

        <tr><td style="height:1px;background:#e8e8e8;"></td></tr>

        <!-- ET Content -->
        <tr>
          <td style="padding:36px 40px 8px;">
            <h1 style="margin:0;font-size:22px;font-weight:700;color:#1a1a1a;">Teie isikuandmed on kustutatud</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:16px 40px 28px;font-size:14px;color:#444;line-height:1.7;">
            <p style="margin:0 0 12px;">Oleme töödelnud teie andmete kustutamise taotluse. Kõik teie isikuandmed ({{ $orderCount }} {{ $orderCount === 1 ? 'tellimus' : 'tellimust' }}) on anonüümitud ega ole enam teiega seostatavad.</p>
            <p style="margin:0 0 12px;">Finantsandmed (summad, kaubad, maksete ID-d) säilitatakse raamatupidamislikel eesmärkidel vastavalt seaduse nõuetele, kuid ei sisalda enam isikuandmeid.</p>
            <p style="margin:0;">Kui teil on küsimusi, võtke meiega ühendust: <a href="mailto:info@abas.lv" style="color:#c0392b;">info@abas.lv</a></p>
          </td>
        </tr>

        <tr><td style="height:1px;background:#e8e8e8;margin:0 40px;"></td></tr>

        <!-- EN Content -->
        <tr>
          <td style="padding:28px 40px 8px;">
            <h1 style="margin:0;font-size:22px;font-weight:700;color:#1a1a1a;">Your personal data has been deleted</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:16px 40px 36px;font-size:14px;color:#444;line-height:1.7;">
            <p style="margin:0 0 12px;">We have processed your data deletion request. All personal information from your {{ $orderCount }} {{ $orderCount === 1 ? 'order' : 'orders' }} has been anonymised and can no longer be linked to you.</p>
            <p style="margin:0 0 12px;">Financial records (amounts, items, payment IDs) are retained for accounting purposes as required by law, but no longer contain any personal information.</p>
            <p style="margin:0;">If you have any questions, contact us at: <a href="mailto:info@abas.lv" style="color:#c0392b;">info@abas.lv</a></p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#1a1a1a;padding:20px 40px;text-align:center;">
            <p style="margin:0;font-size:11px;color:#888;">© {{ date('Y') }} SIA Linda-1 · abas.lv</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
