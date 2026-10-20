{{-- Shared frame for every inbox email (company notifications + customer replies). --}}
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;padding:0;background:#FEFAF3;font-family:Arial,Helvetica,sans-serif;color:#425246;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#FEFAF3;padding:24px 12px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;width:100%;background:#ffffff;border:1px solid #e4e9df;border-radius:12px;overflow:hidden;">
      <tr><td style="background:#84A282;color:#1f2d22;padding:18px 24px;font-size:18px;font-weight:bold;">
        @yield('heading')
      </td></tr>
      <tr><td style="padding:24px;font-size:14px;line-height:1.6;">
        @yield('body')
      </td></tr>
      @hasSection('footer')
      <tr><td style="padding:16px 24px;border-top:1px solid #e4e9df;font-size:12px;color:#7a8a7d;">
        @yield('footer')
      </td></tr>
      @endif
    </table>
  </td></tr>
</table>
</body>
</html>
