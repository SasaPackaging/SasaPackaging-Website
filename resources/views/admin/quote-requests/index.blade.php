@extends('admin.layouts.app')
@section('title', $inbox['title'])

@push('styles')
<link rel="stylesheet" href="@adminAsset('css/inbox.css')">
@endpush

@section('content')
  <x-admin.page-header :title="$inbox['title']" subtitle="Requests sent from the Get a Quote page." />

  <x-admin.card :flush="true">
    @include('admin.inbox._list-head')

    @if($items->isEmpty())
      @include('admin.inbox._empty')
    @else
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead>
            <tr><th>Reference</th><th>Customer</th><th>Request</th><th>Quantity</th><th>Received</th><th>Status</th><th class="is-right">Action</th></tr>
          </thead>
          <tbody>
          @foreach($items as $q)
            <tr class="{{ $q->isNew() ? 'is-unread' : '' }}">
              <td class="adm-nowrap">
                <a class="adm-table__main" href="{{ route('admin.quote-requests.show', $q->id) }}">{{ $q->reference }}</a>
                @if($q->hasFile())<span class="adm-table__sub"><x-admin.icon name="paperclip" :size="13" /> file</span>@endif
              </td>
              <td><span class="adm-table__main">{{ $q->full_name }}</span><span class="adm-table__sub">{{ $q->company }}</span></td>
              <td>
                <span class="adm-truncate">{{ $q->item_name }}</span>
                <span class="adm-table__sub adm-truncate">{{ collect([$q->product_label, $q->service_label])->filter()->implode(' · ') ?: $q->size_text }}</span>
              </td>
              <td class="adm-nowrap">{{ number_format($q->quantity_1) }} / {{ number_format($q->quantity_2) }}</td>
              <td class="adm-nowrap"><x-admin.datetime :value="$q->created_at" /></td>
              <td><x-admin.status-badge :item="$q" /></td>
              <td class="is-right"><x-admin.button :href="route('admin.quote-requests.show', $q->id)" variant="ghost" size="sm" icon="eye">Open</x-admin.button></td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @endif
  </x-admin.card>
@stop
