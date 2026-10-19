@extends('admin.layouts.app')
@section('title', 'Quote ' . $item->reference)

@push('styles')
<link rel="stylesheet" href="@adminAsset('css/inbox.css')">
@endpush

@section('content')
  <x-admin.page-header :title="'Quote ' . $item->reference" :subtitle="$item->item_name" :crumbs="[[$inbox['title'], route('admin.quote-requests.index')], [$item->reference]]">
    <x-slot name="actions">
      <x-admin.button :href="route('admin.quote-requests.index')" variant="ghost" icon="arrow-left">Back to inbox</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <div class="adm-split">
    <div>
      <x-admin.card title="Request">
        <h3 class="adm-section-title">What they want</h3>
        <dl class="adm-dl">
          <x-admin.detail-row label="Product (catalogue)">{{ $item->product_label }}</x-admin.detail-row>
          <x-admin.detail-row label="Service">{{ $item->service_label }}</x-admin.detail-row>
          <x-admin.detail-row label="Product name">{{ $item->item_name }}</x-admin.detail-row>
        </dl>

        <h3 class="adm-section-title">Size &amp; specification</h3>
        <dl class="adm-dl">
          <x-admin.detail-row label="Size (L × W × H)">{{ $item->size_text }}</x-admin.detail-row>
          <x-admin.detail-row label="Stock">{{ $item->stock }}</x-admin.detail-row>
          <x-admin.detail-row label="Coating">{{ config('quote.coatings.' . $item->coating, $item->coating) }}</x-admin.detail-row>
          <x-admin.detail-row label="Add ons">{{ $item->add_ons }}</x-admin.detail-row>
          <x-admin.detail-row label="Colours">{{ $item->colours }}</x-admin.detail-row>
          <x-admin.detail-row label="Packing">{{ $item->packing }}</x-admin.detail-row>
          <x-admin.detail-row label="Quantity 1">{{ number_format($item->quantity_1) }}</x-admin.detail-row>
          <x-admin.detail-row label="Quantity 2">{{ number_format($item->quantity_2) }}</x-admin.detail-row>
        </dl>

        @if($item->hasFile())
        <h3 class="adm-section-title">Attached file</h3>
        <div class="adm-filebox">
          <x-admin.icon name="paperclip" />
          <span class="adm-filebox__name">{{ $item->file_original_name }}</span>
          @if($item->file_size)<span class="adm-muted adm-small">{{ number_format($item->file_size / 1024, 0) }} KB</span>@endif
          <x-admin.button :href="route('admin.quote-requests.file', $item->id)" variant="sage" size="sm" icon="download">Download</x-admin.button>
        </div>
        @endif

        @if($item->message)
        <h3 class="adm-section-title">Customer note</h3>
        <p class="adm-message">{{ $item->message }}</p>
        @endif
      </x-admin.card>

      <x-admin.card title="Customer">
        <dl class="adm-dl">
          <x-admin.detail-row label="Name">{{ $item->full_name }}</x-admin.detail-row>
          <x-admin.detail-row label="Company">{{ $item->company }}</x-admin.detail-row>
          <x-admin.detail-row label="Email"><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></x-admin.detail-row>
          <x-admin.detail-row label="Phone"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $item->phone) }}">{{ $item->phone }}</a></x-admin.detail-row>
        </dl>
      </x-admin.card>

      @include('admin.inbox._replies', ['defaultSubject' => 'Your quote request ' . $item->reference])
    </div>

    <div class="adm-split__side">
      @include('admin.inbox._status-card')
    </div>
  </div>
@stop
