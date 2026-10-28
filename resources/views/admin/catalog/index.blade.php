@extends('admin.layouts.app')
@section('title', $def['label'])

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/partners.css')">@endpush

@php
  $imgField = collect($def['fields'])->firstWhere('type', 'image');
  $imgCol   = $imgField ? $imgField['saves'][0] : null;
  $imgDir   = $imgField ? trim($imgField['dir'], '/') : null;
  $title    = $def['title'];
@endphp

@section('content')
  <x-admin.page-header :title="$def['label']" :subtitle="$def['about']">
    <x-slot name="actions"><x-admin.button :href="route($def['route'] . '.create')">Add {{ $def['singular'] }}</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card :flush="true">
    <form method="get" class="adm-filters">
      <input class="adm-input" type="search" name="q" value="{{ \App\Support\Sanitize::query(request('q')) }}" placeholder="Search by name">
      <x-admin.button variant="ghost" size="sm">Search</x-admin.button>
    </form>

    @if($rows->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>{{ ucfirst($def['singular']) }}</th><th>Order</th><th>Status</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($rows as $r)
            <tr>
              <td>
                <div class="adm-newsrow">
                  @if($imgCol)
                    <span class="adm-logo">@if(!empty($r->{$imgCol}))<img src="{{ url('/public/' . $imgDir . '/' . rawurlencode($r->{$imgCol})) }}" alt="" onerror="this.remove()">@endif</span>
                  @endif
                  <span>
                    <span class="adm-table__main">{{ $r->{$title} ?? $r->name ?? ('#' . $r->id) }}</span>
                    @if(!empty($r->slug))<span class="adm-table__sub">/{{ $r->slug }}.html</span>@endif
                  </span>
                </div>
              </td>
              <td class="adm-nowrap">{{ $r->{$def['order']} ?? '' }}</td>
              <td><x-admin.badge :tone="($r->status ?? 'Yes') === 'Yes' ? 'success' : 'warning'">{{ ($r->status ?? 'Yes') === 'Yes' ? 'Shown' : 'Hidden' }}</x-admin.badge></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <x-admin.button :href="route($def['route'] . '.edit', $r->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  <x-admin.delete-form :action="route($def['route'] . '.destroy', $r->id)" message="Delete this {{ $def['singular'] }} for good? To only hide it, use Edit and choose “No, hide it”." />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $rows->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state :icon="$def['icon']" title="Nothing here yet" :text="'Add your first ' . $def['singular'] . '.'">
        <x-admin.button :href="route($def['route'] . '.create')">Add {{ $def['singular'] }}</x-admin.button>
      </x-admin.empty-state>
    @endif
  </x-admin.card>
@stop
