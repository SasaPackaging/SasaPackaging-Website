@extends('admin.layouts.app')
@section('title', 'Message from ' . $item->name)

@push('styles')
<link rel="stylesheet" href="@adminAsset('css/inbox.css')">
@endpush

@section('content')
  <x-admin.page-header :title="$item->subject" :crumbs="[[$inbox['title'], route('admin.contact-messages.index')], ['Message #' . $item->id]]">
    <x-slot name="actions">
      <x-admin.button :href="route('admin.contact-messages.index')" variant="ghost" icon="arrow-left">Back to inbox</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <div class="adm-split">
    <div>
      <x-admin.card title="Message">
        <p class="adm-message">{{ $item->message }}</p>

        <h3 class="adm-section-title">Sender</h3>
        <dl class="adm-dl">
          <x-admin.detail-row label="Name">{{ $item->name }}</x-admin.detail-row>
          <x-admin.detail-row label="Email"><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></x-admin.detail-row>
          <x-admin.detail-row label="Phone"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $item->phone) }}">{{ $item->phone }}</a></x-admin.detail-row>
        </dl>
      </x-admin.card>

      @include('admin.inbox._replies', ['defaultSubject' => 'Re: ' . $item->subject])
    </div>

    <div class="adm-split__side">
      @include('admin.inbox._status-card')
    </div>
  </div>
@stop
