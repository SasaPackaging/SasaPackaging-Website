@extends('admin.layouts.app')
@section('title', $inbox['title'])

@push('styles')
<link rel="stylesheet" href="@adminAsset('css/inbox.css')">
@endpush

@section('content')
  <x-admin.page-header :title="$inbox['title']" subtitle="Messages sent from the Contact us page." />

  <x-admin.card :flush="true">
    @include('admin.inbox._list-head')

    @if($items->isEmpty())
      @include('admin.inbox._empty')
    @else
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead>
            <tr><th>From</th><th>Subject</th><th>Received</th><th>Status</th><th class="is-right">Action</th></tr>
          </thead>
          <tbody>
          @foreach($items as $m)
            <tr class="{{ $m->isNew() ? 'is-unread' : '' }}">
              <td>
                <a class="adm-table__main" href="{{ route('admin.contact-messages.show', $m->id) }}">{{ $m->name }}</a>
                <span class="adm-table__sub">{{ $m->email }}</span>
              </td>
              <td><span class="adm-truncate">{{ $m->subject }}</span><span class="adm-table__sub adm-truncate">{{ \Illuminate\Support\Str::limit($m->message, 80) }}</span></td>
              <td class="adm-nowrap"><x-admin.datetime :value="$m->created_at" /></td>
              <td><x-admin.status-badge :item="$m" /></td>
              <td class="is-right"><x-admin.button :href="route('admin.contact-messages.show', $m->id)" variant="ghost" size="sm" icon="eye">Open</x-admin.button></td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @endif
  </x-admin.card>
@stop
