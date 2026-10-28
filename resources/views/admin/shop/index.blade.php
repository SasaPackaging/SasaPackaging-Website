@extends('admin.layouts.app')
@section('title', 'Shop products')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush

@section('content')
  <x-admin.page-header title="Shop products" subtitle="Products shown in the picture grid of the Shop Now page.">
    <x-slot name="actions">
      <x-admin.button :href="url(config('shop.url'))" variant="ghost" target="_blank" rel="noopener">View shop ↗</x-admin.button>
      <x-admin.button :href="route('admin.shop-products.create')">Add product</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <x-admin.card :flush="true">
    <form method="get" class="adm-filters">
      <input class="adm-input" type="search" name="q" value="{{ $search }}" placeholder="Search product name">
      <select class="adm-select" name="category" data-adm-autosubmit>
        <option value="">All categories</option>
        @foreach($categories as $name)<option value="{{ $name }}" {{ $category === $name ? 'selected' : '' }}>{{ $name }}</option>@endforeach
      </select>
      <select class="adm-select" name="state" data-adm-autosubmit>
        <option value="">Live and hidden</option>
        <option value="live" {{ $state === 'live' ? 'selected' : '' }}>Live only</option>
        <option value="hidden" {{ $state === 'hidden' ? 'selected' : '' }}>Hidden only</option>
      </select>
      <x-admin.button variant="ghost" size="sm">Search</x-admin.button>
    </form>

    @if($items->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Product</th><th>Price</th><th>Order</th><th>Status</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                <div class="adm-newsrow">
                  <span class="adm-thumb">@if($item->image_url)<img src="{{ $item->image_url }}" alt="">@endif</span>
                  <span>
                    <span class="adm-table__main">{{ $item->name }}</span>
                    <span class="adm-table__sub">
                      @if($item->category){{ $item->category }} · @endif{{ count((array) $item->images) }} picture(s)
                      @if($item->is_featured) · <x-admin.badge tone="info">Featured</x-admin.badge>@endif
                    </span>
                  </span>
                </div>
              </td>
              <td class="adm-nowrap">{{ $item->price_label }}</td>
              <td class="adm-nowrap">{{ $item->sort_order }}</td>
              <td><x-admin.badge :tone="$item->is_active ? 'success' : 'warning'">{{ $item->is_active ? 'Live' : 'Hidden' }}</x-admin.badge></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <form method="post" action="{{ route('admin.shop-products.toggle', $item->id) }}" class="adm-inline-form">@csrf @method('PATCH')
                    <x-admin.button variant="ghost" size="sm">{{ $item->is_active ? 'Hide' : 'Show' }}</x-admin.button>
                  </form>
                  <x-admin.button :href="route('admin.shop-products.edit', $item->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  <x-admin.delete-form :action="route('admin.shop-products.destroy', $item->id)" message="Delete “{{ $item->name }}” and its pictures for good?" />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state icon="box" title="No products yet" text="Add your first product. It appears in the Shop Now grid.">
        <x-admin.button :href="route('admin.shop-products.create')">Add product</x-admin.button>
      </x-admin.empty-state>
    @endif
  </x-admin.card>
@stop
