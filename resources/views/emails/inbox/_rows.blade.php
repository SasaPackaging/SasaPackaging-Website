{{-- Label / value rows.  Pass $rows = ['Label' => 'value', ...]  (empty values are skipped, all values are escaped) --}}
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
@foreach($rows as $label => $value)
  @if(trim((string) $value) !== '')
  <tr>
    <td valign="top" width="150" style="padding:7px 12px 7px 0;font-weight:bold;color:#1f2d22;border-bottom:1px solid #eef2ec;">{{ $label }}</td>
    <td valign="top" style="padding:7px 0;border-bottom:1px solid #eef2ec;">{!! nl2br(e($value)) !!}</td>
  </tr>
  @endif
@endforeach
</table>
