@extends('admin.layouts.app')
@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="@adminAsset('css/dashboard.css')">
@endpush

@section('content')
  <x-admin.page-header :title="'Hello, ' . explode(' ', auth('admin')->user()->name)[0]" subtitle="Here is what needs your attention." />

  <div class="adm-grid adm-grid--stats">
    <x-admin.stat-card label="New contact messages" :value="$stats['contact_new']" icon="mail" tone="info" :href="route('admin.contact-messages.index', ['status' => config('admin.inbox.workflow.initial')])" />
    <x-admin.stat-card label="New quote requests" :value="$stats['quote_new']" icon="quote" tone="info" :href="route('admin.quote-requests.index', ['status' => config('admin.inbox.workflow.initial')])" />
    <x-admin.stat-card label="Messages this month" :value="$stats['contact_month']" icon="inbox" />
    <x-admin.stat-card label="Quotes this month" :value="$stats['quote_month']" icon="quote" />
  </div>

  <div class="adm-grid adm-grid--two">
    <x-admin.card title="Latest messages" :flush="true">
      <x-slot name="actions"><x-admin.button :href="route('admin.contact-messages.index')" variant="ghost" size="sm">View all</x-admin.button></x-slot>
      @forelse($latestMessages as $m)
        @if($loop->first)<ul class="adm-latest">@endif
        <li>
          <a href="{{ route('admin.contact-messages.show', $m->id) }}">
            <strong>{{ $m->subject }}</strong>
            <small>{{ $m->name }} · <x-admin.datetime :value="$m->created_at" /></small>
          </a>
          <x-admin.status-badge :item="$m" />
        </li>
        @if($loop->last)</ul>@endif
      @empty
        <x-admin.empty-state icon="mail" title="No messages yet" text="Messages from the Contact us page appear here." />
      @endforelse
    </x-admin.card>

    <x-admin.card title="Latest quote requests" :flush="true">
      <x-slot name="actions"><x-admin.button :href="route('admin.quote-requests.index')" variant="ghost" size="sm">View all</x-admin.button></x-slot>
      @forelse($latestQuotes as $q)
        @if($loop->first)<ul class="adm-latest">@endif
        <li>
          <a href="{{ route('admin.quote-requests.show', $q->id) }}">
            <strong>{{ $q->reference }} · {{ $q->item_name }}</strong>
            <small>{{ $q->full_name }}@if($q->company), {{ $q->company }}@endif · <x-admin.datetime :value="$q->created_at" /></small>
          </a>
          <x-admin.status-badge :item="$q" />
        </li>
        @if($loop->last)</ul>@endif
      @empty
        <x-admin.empty-state icon="quote" title="No quote requests yet" text="Requests from the Get a Quote page appear here." />
      @endforelse
    </x-admin.card>
  </div>
@stop
