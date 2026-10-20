@extends('admin.layouts.app')
@section('title', $def['label'])

@push('scripts')<script src="@adminAsset('js/content.js')"></script>@endpush

@section('content')
  @php $isNew = $row === null; @endphp
  <x-admin.page-header :title="($isNew ? 'Add ' : 'Edit ') . $def['singular']" :crumbs="[['Landing content', route('admin.landing.index')], [$def['label'], route('admin.records.index', $def['key'])], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions"><x-admin.button :href="route('admin.records.index', $def['key'])" variant="ghost" icon="arrow-left">Back</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:860px">
    <form method="post" action="{{ $isNew ? route('admin.records.store', $def['key']) : route('admin.records.update', [$def['key'], $row->id]) }}">
      @csrf
      @unless($isNew) @method('PUT') @endunless

      @foreach($def['fields'] as $f)
        @php $val = $isNew ? ($f['type'] === 'status' ? 'Yes' : '') : $row->{$f['name']}; @endphp
        @if($f['type'] === 'rich')
          <x-admin.rich-text :name="$f['name']" :label="$f['label']" :value="$val" />
        @elseif($f['type'] === 'textarea')
          <x-admin.textarea :name="$f['name']" :label="$f['label']" :value="$val" :hint="$f['hint'] ?? null" rows="4" />
        @elseif($f['type'] === 'status')
          <x-admin.select :name="$f['name']" :label="$f['label']" :options="['Yes' => 'Yes, show it', 'No' => 'No, hide it']" :value="$val" />
        @else
          <x-admin.input :name="$f['name']" :label="$f['label']" :type="$f['type'] === 'number' ? 'number' : 'text'" :value="$val" :required="!empty($f['required'])" :hint="$f['hint'] ?? null" />
        @endif
      @endforeach

      <x-admin.button>{{ $isNew ? 'Add' : 'Save changes' }}</x-admin.button>
    </form>
  </x-admin.card>
@stop
