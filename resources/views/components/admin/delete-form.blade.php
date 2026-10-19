{{-- <x-admin.delete-form :action="route('...destroy', $id)" message="Delete this message?" label="Delete" />
     Asks for confirmation (admin.js) and sends DELETE with the CSRF token. --}}
@props(['action', 'message' => 'Are you sure? This cannot be undone.', 'label' => 'Delete', 'block' => false])
<form method="post" action="{{ $action }}" data-adm-confirm="{{ $message }}" class="adm-inline-form">
  @csrf
  @method('DELETE')
  <x-admin.button variant="danger" icon="trash" :size="$block ? null : 'sm'" :block="$block">{{ $label }}</x-admin.button>
</form>
