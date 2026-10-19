{{-- <x-admin.status-badge :item="$message" />  — label and colour come from config/admin.php (inbox.statuses) --}}
@props(['item'])
<x-admin.badge :tone="$item->status_tone">{{ $item->status_label }}</x-admin.badge>
