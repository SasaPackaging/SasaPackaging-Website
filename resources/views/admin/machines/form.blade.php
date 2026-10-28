@extends('admin.layouts.app')
@section('title', $item->exists ? 'Edit machine' : 'Add machine')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush

@section('content')
  @php
    $isNew = !$item->exists;
    $image = config('machines.image');
  @endphp
  <x-admin.page-header :title="$isNew ? 'Add machine' : 'Edit machine'" :crumbs="[['Machines', route('admin.machines.index')], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions"><x-admin.button :href="route('admin.machines.index')" variant="ghost" icon="arrow-left">Back</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:760px">
    <form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.machines.store') : route('admin.machines.update', $item->id) }}">
      @csrf
      @unless($isNew) @method('PUT') @endunless

      <x-admin.input name="name" label="Machine name" :value="$item->name" :required="true" maxlength="160" />
      <x-admin.textarea name="description" label="Description" :value="$item->description" rows="5" maxlength="1500" hint="A few lines about what the machine does." />

      <div class="adm-field {{ $errors->has('image') ? 'has-error' : '' }}">
        <label for="f_image">Picture</label>
        @if($item->image_url)
          <img class="adm-news-preview" src="{{ $item->image_url }}" alt="">
          <label class="adm-check"><input type="hidden" name="remove_image" value="0"><input type="checkbox" name="remove_image" value="1"><span>Remove the current picture</span></label>
        @endif
        <input class="adm-input" id="f_image" type="file" name="image" accept=".{{ implode(',.', $image['extensions']) }}">
        <p class="adm-field__hint">{{ strtoupper(implode(', ', $image['extensions'])) }}, up to {{ round($image['max_kb'] / 1024) }} MB.{{ $item->image_url ? ' Choose a file only to replace the current picture.' : ' Without a picture the website shows a soft placeholder.' }}</p>
        @error('image')<p class="adm-field__error">{{ $message }}</p>@enderror
      </div>

      <x-admin.input name="sort_order" type="number" min="0" label="Order (1 comes first)" :value="$item->sort_order ?? 0" />
      <label class="adm-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active ? 1 : 0) ? 'checked' : '' }}>
        <span>Show on the website</span>
      </label>

      <x-admin.button>{{ $isNew ? 'Add machine' : 'Save changes' }}</x-admin.button>
    </form>
  </x-admin.card>
@stop
