@extends('admin.layouts.app')
@section('title', 'Review invites')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')"><link rel="stylesheet" href="@adminAsset('css/shop.css')">@endpush
@push('scripts')
<script>
/* "Copy link" buttons */
document.addEventListener('click', function (e) {
  var b = e.target.closest('[data-copy]');
  if (!b) { return; }
  var text = b.getAttribute('data-copy'), old = b.textContent;
  function done() { b.textContent = 'Copied'; setTimeout(function () { b.textContent = old; }, 1500); }
  if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(text).then(done); return; }
  var t = document.createElement('textarea'); t.value = text; document.body.appendChild(t); t.select();
  try { document.execCommand('copy'); done(); } catch (err) { window.prompt('Copy this link:', text); }
  document.body.removeChild(t);
});
</script>
@endpush

@section('content')
  @php $tones = ['pending' => 'info', 'used' => 'success', 'expired' => 'warning', 'revoked' => 'neutral']; @endphp
  <x-admin.page-header title="Review invites" subtitle="Only people you invite can leave feedback. Each person gets a private link that works once.">
    <x-slot name="actions"><x-admin.button :href="route('admin.reviews.index')" variant="ghost" icon="arrow-left">Reviews</x-admin.button></x-slot>
  </x-admin.page-header>

  <x-admin.card title="Invite a partner or collaborator">
    <form method="post" action="{{ route('admin.review-invites.store') }}">
      @csrf
      <div class="adm-row" style="gap:14px;align-items:flex-start;flex-wrap:wrap">
        <div style="flex:1 1 200px"><x-admin.input name="name" label="Name" :required="true" maxlength="120" /></div>
        <div style="flex:1 1 240px"><x-admin.input name="email" type="email" label="Email" :required="true" maxlength="150" /></div>
        <div style="flex:1 1 200px"><x-admin.input name="company" label="Company (optional)" maxlength="150" /></div>
        <div style="flex:0 1 140px"><x-admin.input name="days" type="number" min="1" max="180" label="Valid for (days)" :value="config('reviews.invite_days')" /></div>
      </div>
      <label class="adm-check">
        <input type="hidden" name="send_email" value="0">
        <input type="checkbox" name="send_email" value="1" checked>
        <span>Email the link now (otherwise use “Copy link” and send it yourself, for example on WhatsApp)</span>
      </label>
      <x-admin.button>Create invite</x-admin.button>
    </form>
  </x-admin.card>

  <x-admin.card :flush="true" style="margin-top:18px">
    @if($items->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Person</th><th>Status</th><th>Expires</th><th>Email sent</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                <span class="adm-table__main">{{ $item->name }}</span>
                <span class="adm-table__sub">{{ $item->email }}@if($item->company) · {{ $item->company }}@endif</span>
              </td>
              <td>
                <x-admin.badge :tone="$tones[$item->state] ?? 'neutral'">{{ ucfirst($item->state) }}</x-admin.badge>
                @if($item->review)<span class="adm-table__sub"><a href="{{ route('admin.reviews.edit', $item->review->id) }}">See review ({{ $item->review->rating }}★)</a></span>@endif
              </td>
              <td class="adm-nowrap">{{ optional($item->expires_at)->format('d M Y') ?: '—' }}</td>
              <td class="adm-nowrap">{{ optional($item->sent_at)->format('d M Y, h:i A') ?: 'Not sent' }}</td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  @if($item->is_open)
                    <x-admin.button type="button" variant="ghost" size="sm" data-copy="{{ $item->url }}">Copy link</x-admin.button>
                    <form method="post" action="{{ route('admin.review-invites.resend', $item->id) }}" class="adm-inline-form">@csrf<x-admin.button variant="ghost" size="sm">Send email</x-admin.button></form>
                    <form method="post" action="{{ route('admin.review-invites.revoke', $item->id) }}" class="adm-inline-form" data-adm-confirm="Stop this link from working?">@csrf @method('PATCH')<x-admin.button variant="ghost" size="sm">Cancel link</x-admin.button></form>
                  @endif
                  <x-admin.delete-form :action="route('admin.review-invites.destroy', $item->id)" message="Delete this invite? A review already received from it is kept." />
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state icon="mail" title="No invites yet" text="Create the first invite above." />
    @endif
  </x-admin.card>
@stop
