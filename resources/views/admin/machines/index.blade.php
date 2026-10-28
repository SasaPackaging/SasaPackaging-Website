@extends('admin.layouts.app')
@section('title', 'Machines')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush

@section('content')
  <x-admin.page-header title="Machines" subtitle="The machines shown on the Automation / Machines page. The heading and intro text are edited in Landing content.">
    <x-slot name="actions">
      <x-admin.button :href="url('/machines.html')" variant="ghost" target="_blank" rel="noopener">View page ↗</x-admin.button>
      <x-admin.button :href="route('admin.machines.create')">Add machine</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <x-admin.card :flush="true">
    @if($items->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Machine</th><th>Order</th><th>Status</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                <div class="adm-newsrow">
                  <span class="adm-thumb">@if($item->image_url)<img src="{{ $item->image_url }}" alt="">@endif</span>
                  <span>
                    <span class="adm-table__main">{{ $item->name }}</span>
                    @if($item->description)<span class="adm-table__sub">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</span>@endif
                  </span>
                </div>
              </td>
              <td class="adm-nowrap">{{ $item->sort_order }}</td>
              <td><x-admin.badge :tone="$item->is_active ? 'success' : 'warning'">{{ $item->is_active ? 'Live' : 'Hidden' }}</x-admin.badge></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <form method="post" action="{{ route('admin.machines.toggle', $item->id) }}" class="adm-inline-form">@csrf @method('PATCH')
                    <x-admin.button variant="ghost" size="sm">{{ $item->is_active ? 'Hide' : 'Show' }}</x-admin.button>
                  </form>
                  <x-admin.button :href="route('admin.machines.edit', $item->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  <x-admin.delete-form :action="route('admin.machines.destroy', $item->id)" message="Delete “{{ $item->name }}” and its picture for good?" />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state icon="layers" title="No machines yet" text="Add your first machine. Until then the website shows its old placeholder cards.">
        <x-admin.button :href="route('admin.machines.create')">Add machine</x-admin.button>
      </x-admin.empty-state>
    @endif
  </x-admin.card>
@stop
