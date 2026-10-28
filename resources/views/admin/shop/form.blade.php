@extends('admin.layouts.app')
@section('title', $item->exists ? 'Edit product' : 'Add product')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush
@push('scripts')<script src="@adminAsset('js/content.js')"></script>@endpush

@section('content')
  @php
    $isNew = !$item->exists;
    $image = config('shop.image');
  @endphp
  <x-admin.page-header :title="$isNew ? 'Add product' : 'Edit product'" :crumbs="[['Shop products', route('admin.shop-products.index')], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions">
      @if(!$isNew && $item->is_active)<x-admin.button :href="$item->url" variant="ghost" target="_blank" rel="noopener">View on website ↗</x-admin.button>@endif
      <x-admin.button :href="route('admin.shop-products.index')" variant="ghost" icon="arrow-left">Back</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.shop-products.store') : route('admin.shop-products.update', $item->id) }}">
    @csrf
    @unless($isNew) @method('PUT') @endunless

    <div class="adm-split">
      <div>
        <x-admin.card title="Product details">
          <x-admin.input name="name" label="Product name" :value="$item->name" :required="true" maxlength="160" />
          <x-admin.textarea name="summary" label="Short description (shown on the card)" :value="$item->summary" rows="2" maxlength="220" hint="One or two short lines. Maximum 220 letters." />
          <x-admin.rich-text name="description" label="Full description (shown on the product page)" :value="$item->description" />
        </x-admin.card>

        <x-admin.card title="Pictures">
          @if($item->image_urls)
            <div class="adm-gallery">
              @foreach($item->image_urls as $gi => $gurl)
                <label class="adm-gallery__item">
                  <img src="{{ $gurl }}" alt="">
                  @if($gi === 0)<em>Cover</em>@endif
                  <span><input type="checkbox" name="remove_images[]" value="{{ $item->images[$gi] }}"> Remove</span>
                </label>
              @endforeach
            </div>
          @endif
          <div class="adm-field {{ $errors->has('images') || $errors->has('images.*') ? 'has-error' : '' }}">
            <label for="f_images">{{ $item->image_urls ? 'Add more pictures' : 'Upload pictures' }}{!! $isNew ? ' <em>*</em>' : '' !!}</label>
            <input class="adm-input" id="f_images" type="file" name="images[]" multiple accept=".{{ implode(',.', $image['extensions']) }}">
            <p class="adm-field__hint">Up to {{ $image['max_count'] }} pictures ({{ strtoupper(implode(', ', $image['extensions'])) }}, {{ round($image['max_kb'] / 1024) }} MB each). The first one is the cover shown in the grid. Tall or square pictures look great in the Pinterest grid.</p>
            @error('images')<p class="adm-field__error">{{ $message }}</p>@enderror
            @foreach($errors->get('images.*') as $msgs)<p class="adm-field__error">{{ $msgs[0] }}</p>@endforeach
          </div>
        </x-admin.card>
      </div>

      <div class="adm-split__side">
        <x-admin.card title="Publish">
          <label class="adm-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active ? 1 : 0) ? 'checked' : '' }}>
            <span>Show on the Shop page</span>
          </label>
          <label class="adm-check">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured ? 1 : 0) ? 'checked' : '' }}>
            <span>Featured (gets a “Featured” tag)</span>
          </label>
          <x-admin.input name="sort_order" type="number" min="0" label="Order (smaller comes first)" :value="$item->sort_order ?? 0" hint="0 = newest first. Use 1, 2, 3 to pin products on top." />
          <x-admin.button :block="true">{{ $isNew ? 'Add product' : 'Save changes' }}</x-admin.button>
        </x-admin.card>

        <x-admin.card title="Price and order">
          <x-admin.input name="price" type="number" step="0.01" min="0" label="Price ({{ config('shop.currency') }})" :value="$item->price" hint="Leave empty to show “{{ config('shop.page.no_price') }}”." />
          <x-admin.input name="min_order" label="Minimum order (optional)" :value="$item->min_order" maxlength="60" placeholder="e.g. Min. order 500 pcs" />
        </x-admin.card>

        <x-admin.card title="Category">
          <x-admin.input name="category" label="Category (optional)" :value="$item->category" maxlength="80" list="shop-categories" hint="Used for the filter buttons on the Shop page. Pick one or type a new one." />
          <datalist id="shop-categories">@foreach($categories as $name)<option value="{{ $name }}">@endforeach</datalist>
        </x-admin.card>
      </div>
    </div>
  </form>
@stop
