{{-- <x-admin.alert tone="success">Saved.</x-admin.alert>   tone: success | error | info --}}
@props(['tone' => 'info', 'dismissible' => true])
<div class="adm-alert adm-alert--{{ $tone }}" role="{{ $tone === 'error' ? 'alert' : 'status' }}">
  <p>{{ $slot }}</p>
  @if($dismissible)<button type="button" class="adm-alert__close" data-adm-dismiss aria-label="Dismiss">&times;</button>@endif
</div>
