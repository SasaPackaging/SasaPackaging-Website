@extends('admin.layouts.app')
@section('title', 'Partners')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/partners.css')">@endpush

@section('content')
  <x-admin.page-header title="Partners" subtitle="Logos of partners and collaborations. They slide across the home page automatically.">
    <x-slot name="actions"><x-admin.button :href="route('admin.partners.create')">Add partner</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card :flush="true">
    @if($items->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Partner</th><th>Website</th><th>Order</th><th>Status</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                <div class="adm-newsrow">
                  <span class="adm-logo">@if($item->logo_url)<img src="{{ $item->logo_url }}" alt="">@endif</span>
                  <span>
                    <span class="adm-table__main">{{ $item->name }}</span>
                    @unless($item->logo_url)<span class="adm-table__sub">No logo yet, so it is not shown on the website</span>@endunless
                  </span>
                </div>
              </td>
              <td>@if($item->slug)<a href="{{ $item->slug }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::limit(preg_replace('~^https?://~', '', $item->slug), 32) }}</a>@else<span class="adm-table__sub">—</span>@endif</td>
              <td class="adm-nowrap">{{ $item->sort_order }}</td>
              <td><x-admin.badge :tone="$item->is_shown ? 'success' : 'warning'">{{ $item->is_shown ? 'Shown' : 'Hidden' }}</x-admin.badge></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <x-admin.button :href="route('admin.partners.edit', $item->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  <x-admin.delete-form :action="route('admin.partners.destroy', $item->id)" message="Remove “{{ $item->name }}” from the website for good?" />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state icon="handshake" title="No partners yet" text="Add the first partner logo. It will slide on the home page.">
        <x-admin.button :href="route('admin.partners.create')">Add partner</x-admin.button>
      </x-admin.empty-state>
    @endif
  </x-admin.card>
@stop
