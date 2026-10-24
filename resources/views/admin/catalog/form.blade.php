@extends('admin.layouts.app')
@section('title', $def['label'])

@push('styles')<link rel="stylesheet" href="@adminAsset('css/partners.css')">@endpush
@push('scripts')<script src="@adminAsset('js/content.js')"></script>@endpush

@section('content')
  @php $isNew = $row === null; $ext = config('catalog.images.extensions'); @endphp
  <x-admin.page-header :title="($isNew ? 'Add ' : 'Edit ') . $def['singular']" :crumbs="[[$def['label'], route($def['route'] . '.index')], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions"><x-admin.button :href="route($def['route'] . '.index')" variant="ghost" icon="arrow-left">Back</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:860px">
    <form method="post" enctype="multipart/form-data" action="{{ $isNew ? route($def['route'] . '.store') : route($def['route'] . '.update', $row->id) }}">
      @csrf
      @unless($isNew) @method('PUT') @endunless

      @foreach($def['fields'] as $f)
        @php
          $n = $f['name'];
          $val = $f['type'] === 'image' ? null : ($isNew ? ($f['type'] === 'status' ? 'Yes' : '') : ($row->{$n} ?? ''));
        @endphp
        @if($f['type'] === 'image')
          @php $cur = $isNew ? '' : ($row->{$f['saves'][0]} ?? ''); @endphp
          <div class="adm-field {{ $errors->has($n) ? 'has-error' : '' }}">
            <label for="f_{{ $n }}">{{ $f['label'] }}</label>
            @if($cur)<span class="adm-logo adm-logo--lg"><img src="{{ url('/public/' . trim($f['dir'], '/') . '/' . rawurlencode($cur)) }}" alt="" onerror="this.parentNode.remove()"></span>@endif
            <input class="adm-input" id="f_{{ $n }}" type="file" name="{{ $n }}" accept=".{{ implode(',.', $ext) }}">
            <p class="adm-field__hint">{{ $f['hint'] ?? '' }} {{ strtoupper(implode(', ', $ext)) }}, up to {{ round(config('catalog.images.max_kb') / 1024) }} MB.{{ $cur ? ' Leave empty to keep the current picture.' : '' }}</p>
            @error($n)<p class="adm-field__error">{{ $message }}</p>@enderror
          </div>
        @elseif($f['type'] === 'rich')
          <x-admin.rich-text :name="$n" :label="$f['label']" :value="$val" />
        @elseif($f['type'] === 'textarea')
          <x-admin.textarea :name="$n" :label="$f['label']" :value="$val" :hint="$f['hint'] ?? null" rows="4" />
        @elseif($f['type'] === 'select')
          <x-admin.select :name="$n" :label="$f['label']" :options="$f['options']" :value="$val" :required="!empty($f['required'])" :hint="$f['hint'] ?? null" />
        @elseif($f['type'] === 'status')
          <x-admin.select :name="$n" :label="$f['label']" :options="['Yes' => 'Yes, show it', 'No' => 'No, hide it']" :value="$val" />
        @else
          <x-admin.input :name="$n" :label="$f['label']" :type="$f['type'] === 'number' ? 'number' : 'text'" :value="$val" :required="!empty($f['required'])" :hint="$f['hint'] ?? ($f['type'] === 'number' ? 'Leave empty to put it at the end.' : null)" />
        @endif
      @endforeach

      <x-admin.button>{{ $isNew ? 'Add' : 'Save changes' }}</x-admin.button>
    </form>
  </x-admin.card>
@stop
