{{-- Shows ->with('success'|'error'|'info', '...') messages and validation summary. --}}
@foreach(['success' => 'success', 'error' => 'error', 'info' => 'info'] as $key => $tone)
  @if(session($key))
    <x-admin.alert :tone="$tone">{{ session($key) }}</x-admin.alert>
  @endif
@endforeach
