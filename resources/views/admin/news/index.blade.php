@extends('admin.layouts.app')
@section('title', 'News & events')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')">@endpush

@section('content')
  <x-admin.page-header title="News & events" subtitle="Stories and events shown on the home page and the News & events page.">
    <x-slot name="actions"><x-admin.button :href="route('admin.news.create')">Add news / event</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card :flush="true">
    <form method="get" class="adm-filters">
      <input class="adm-input" type="search" name="q" value="{{ request('q') }}" placeholder="Search title">
      <select class="adm-select" name="type" data-adm-autosubmit>
        <option value="">All types</option>
        @foreach($types as $key => $label)<option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach
      </select>
      <x-admin.button variant="ghost" size="sm">Search</x-admin.button>
    </form>

    @if($items->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Item</th><th>Date</th><th>Status</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                <div class="adm-newsrow">
                  <span class="adm-thumb">@if($item->image_url)<img src="{{ $item->image_url }}" alt="">@endif</span>
                  <span>
                    <span class="adm-table__main">{{ $item->title }}</span>
                    <span class="adm-table__sub"><x-admin.badge :tone="$item->is_event ? 'info' : 'neutral'">{{ $item->type_label }}</x-admin.badge>@if($item->location) · {{ $item->location }}@endif</span>
                  </span>
                </div>
              </td>
              <td class="adm-nowrap">{{ $item->shown_date->format('d M Y') }}@if($item->is_event)<span class="adm-table__sub">Event day</span>@endif</td>
              <td><x-admin.badge :tone="$item->is_published ? 'success' : 'warning'">{{ $item->is_published ? 'Live' : 'Hidden' }}</x-admin.badge></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <x-admin.button :href="route('admin.news.edit', $item->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  <x-admin.delete-form :action="route('admin.news.destroy', $item->id)" message="Delete “{{ $item->title }}” for good?" />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state icon="calendar" title="Nothing here yet" text="Add your first news story or event.">
        <x-admin.button :href="route('admin.news.create')">Add news / event</x-admin.button>
      </x-admin.empty-state>
    @endif
  </x-admin.card>
@stop
