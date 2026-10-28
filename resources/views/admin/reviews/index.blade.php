@extends('admin.layouts.app')
@section('title', 'Reviews')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush

@section('content')
  @php
    $limit   = (int) config('reviews.site_limit');
    $visible = \App\Models\Review::visible()->count();
  @endphp
  <x-admin.page-header title="Reviews" subtitle="Every review stays here. Only the ones you switch on appear on the home page (best {{ $limit }}).">
    <x-slot name="actions">
      <x-admin.button :href="route('admin.review-invites.index')" variant="ghost" icon="mail">Invite someone</x-admin.button>
      <x-admin.button :href="route('admin.reviews.create')">Add review</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <p class="adm-table__sub" style="margin:0 0 14px">
    Total <strong>{{ $total }}</strong> · Switched on <strong>{{ $visible }}</strong> · Shown on website <strong>{{ $shown }}</strong>
    @if($average) · Website rating <strong>{{ number_format($average, 1) }}</strong>@endif
    @if($total) · Average of all <strong>{{ number_format($allAverage, 1) }}</strong>@endif
  </p>
  @if($visible > $limit)
    <x-admin.alert tone="info">{{ $visible }} reviews are switched on, but the home page has room for {{ $limit }}. The ones with the smallest “Order” number (then the newest) are shown.</x-admin.alert>
  @endif

  <x-admin.card :flush="true">
    <form method="get" class="adm-filters">
      <input class="adm-input" type="search" name="q" value="{{ $search }}" placeholder="Search name or text">
      <select class="adm-select" name="state" data-adm-autosubmit>
        <option value="">Shown and hidden</option>
        <option value="shown" {{ $state === 'shown' ? 'selected' : '' }}>Switched on</option>
        <option value="hidden" {{ $state === 'hidden' ? 'selected' : '' }}>Hidden</option>
      </select>
      <x-admin.button variant="ghost" size="sm">Search</x-admin.button>
    </form>

    @if($items->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Reviewer</th><th>Rating</th><th>Feedback</th><th>Order</th><th>Website</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                <span class="adm-table__main">{{ $item->name }}</span>
                <span class="adm-table__sub">{{ $item->company_role }}@if($item->company_role) · @endif{{ $item->source === 'invite' ? 'Via invite' : 'Added by admin' }}</span>
              </td>
              <td class="adm-nowrap" title="{{ $item->rating }} / 5">{{ $item->stars }}</td>
              <td>{{ \Illuminate\Support\Str::limit($item->comment, 110) }}</td>
              <td class="adm-nowrap">{{ $item->sort_order }}</td>
              <td><x-admin.badge :tone="$item->is_visible ? 'success' : 'neutral'">{{ $item->is_visible ? 'On' : 'Hidden' }}</x-admin.badge></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <form method="post" action="{{ route('admin.reviews.toggle', $item->id) }}" class="adm-inline-form">@csrf @method('PATCH')
                    <x-admin.button variant="ghost" size="sm">{{ $item->is_visible ? 'Hide' : 'Show' }}</x-admin.button>
                  </form>
                  <x-admin.button :href="route('admin.reviews.edit', $item->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  <x-admin.delete-form :action="route('admin.reviews.destroy', $item->id)" message="Delete the review of “{{ $item->name }}” for good?" />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state icon="award" title="No reviews yet" text="Invite a partner to share feedback, or add a review you received another way.">
        <x-admin.button :href="route('admin.review-invites.index')">Invite someone</x-admin.button>
      </x-admin.empty-state>
    @endif
  </x-admin.card>
@stop
