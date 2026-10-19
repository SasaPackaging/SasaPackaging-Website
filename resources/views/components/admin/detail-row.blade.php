{{-- <x-admin.detail-row label="Phone">{{ $x }}</x-admin.detail-row> — row is hidden when the value is empty --}}
@props(['label'])
@if(trim((string) $slot) !== '')
<div class="adm-dl__row"><dt>{{ $label }}</dt><dd>{{ $slot }}</dd></div>
@endif
