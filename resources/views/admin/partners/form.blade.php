@extends('admin.layouts.app')
@section('title', $item->exists ? 'Edit partner' : 'Add partner')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/partners.css')">@endpush

@section('content')
  @php
    $isNew = !$item->exists;
    $image = config('partners.image');
  @endphp
  <x-admin.page-header :title="$isNew ? 'Add partner' : 'Edit partner'" :crumbs="[['Partners', route('admin.partners.index')], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions"><x-admin.button :href="route('admin.partners.index')" variant="ghost" icon="arrow-left">Back</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:760px">
    <form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.partners.store') : route('admin.partners.update', $item->id) }}">
      @csrf
      @unless($isNew) @method('PUT') @endunless

      <x-admin.input name="name" label="Company name" :value="$item->name" :required="true" maxlength="150" />
      <x-admin.input name="slug" label="Website link (optional)" :value="$item->slug" placeholder="https://www.example.com" hint="If filled, the logo opens this website in a new tab." />

      <div class="adm-field {{ $errors->has('image') ? 'has-error' : '' }}">
        <label for="f_image">Logo{{ $isNew ? ' *' : '' }}</label>
        @if($item->logo_url)<span class="adm-logo adm-logo--lg"><img src="{{ $item->logo_url }}" alt=""></span>@endif
        <input class="adm-input" id="f_image" type="file" name="image" accept=".{{ implode(',.', $image['extensions']) }}" {{ $isNew ? 'required' : '' }}>
        <p class="adm-field__hint">{{ strtoupper(implode(', ', $image['extensions'])) }}, up to {{ round($image['max_kb'] / 1024) }} MB. A wide logo on a plain or transparent background looks best.{{ $isNew ? '' : ' Leave empty to keep the current logo.' }}</p>
        @error('image')<p class="adm-field__error">{{ $message }}</p>@enderror
      </div>

      <x-admin.select name="status" label="Show on website" :options="['Yes' => 'Yes, show it', 'No' => 'No, hide it']" :value="$item->status ?: 'Yes'" />
      <x-admin.input name="sort_order" type="number" label="Order (1 comes first)" :value="$item->sort_order" hint="Leave empty to put it at the end." />

      <x-admin.button>{{ $isNew ? 'Add partner' : 'Save changes' }}</x-admin.button>
    </form>
  </x-admin.card>
@stop
