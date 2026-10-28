@extends('admin.layouts.app')
@section('title', $item->exists ? 'Edit review' : 'Add review')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush

@section('content')
  @php $isNew = !$item->exists; @endphp
  <x-admin.page-header :title="$isNew ? 'Add review' : 'Edit review'" :crumbs="[['Reviews', route('admin.reviews.index')], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions"><x-admin.button :href="route('admin.reviews.index')" variant="ghost" icon="arrow-left">Back</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:760px">
    <form method="post" action="{{ $isNew ? route('admin.reviews.store') : route('admin.reviews.update', $item->id) }}">
      @csrf
      @unless($isNew) @method('PUT') @endunless

      @if(!$isNew && $item->invite)<p class="adm-field__hint" style="margin-top:0">Received through a private link sent to {{ $item->invite->email }}.</p>@endif

      <x-admin.input name="name" label="Name" :value="$item->name" :required="true" maxlength="120" />
      <x-admin.input name="company_role" label="Company / role (optional)" :value="$item->company_role" maxlength="150" />
      <x-admin.select name="rating" label="Stars" :options="[5 => '5 stars', 4 => '4 stars', 3 => '3 stars', 2 => '2 stars', 1 => '1 star']" :value="$item->rating ?: 5" :required="true" />
      <x-admin.textarea name="comment" label="Feedback" :value="$item->comment" rows="6" :required="true" maxlength="{{ config('reviews.comment_max') }}" hint="{{ config('reviews.comment_min') }} to {{ config('reviews.comment_max') }} letters." />
      <x-admin.input name="sort_order" type="number" min="0" label="Order (smaller comes first)" :value="$item->sort_order ?? 0" hint="Used when more reviews are switched on than the home page can show." />

      <label class="adm-check">
        <input type="hidden" name="is_visible" value="0">
        <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $item->is_visible ? 1 : 0) ? 'checked' : '' }}>
        <span>Show on the website</span>
      </label>

      <x-admin.button>{{ $isNew ? 'Add review' : 'Save changes' }}</x-admin.button>
    </form>
  </x-admin.card>
@stop
