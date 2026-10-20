@extends('admin.layouts.app')
@section('title', $group['label'])

@push('scripts')<script src="@adminAsset('js/content.js')"></script>@endpush

@section('content')
  <x-admin.page-header :title="$group['label']" :subtitle="$group['about'] ?? null" :crumbs="[['Landing content', route('admin.landing.index')], [$group['label']]]">
    <x-slot name="actions">
      <x-admin.button :href="url('/')" variant="ghost" target="_blank" rel="noopener">View website ↗</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:860px">
    <form method="post" action="{{ route('admin.landing.update', $group['key']) }}">
      @csrf
      @method('PUT')

      @foreach($group['fields'] as $i => $field)
        @include('admin.content._field', ['i' => $i, 'field' => $field, 'value' => $editor->value($group, $field)])
      @endforeach

      <div class="adm-row">
        <x-admin.button>Save changes</x-admin.button>
        <x-admin.button :href="route('admin.landing.index')" variant="ghost">Back</x-admin.button>
      </div>
    </form>

    @if(!$editor->usesSettingsTable($group))
      <form method="post" action="{{ route('admin.landing.reset', $group['key']) }}" data-adm-confirm="Go back to the original wording for this section? Your edits here will be lost." class="adm-reset">
        @csrf
        @method('DELETE')
        <button type="submit" class="adm-link-danger">Restore original wording</button>
      </form>
    @endif
  </x-admin.card>
@stop
