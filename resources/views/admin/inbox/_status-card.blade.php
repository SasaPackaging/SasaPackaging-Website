{{-- Right column on a detail page: current status, change status, reply by email.
     Needs: $item  $inbox --}}
<x-admin.card title="Status">
  <div class="adm-row" style="margin-bottom:14px">
    <x-admin.status-badge :item="$item" />
    <span class="adm-muted adm-small">Received <x-admin.datetime :value="$item->created_at" /></span>
  </div>

  <form method="post" action="{{ route($inbox['route'] . '.status', $item->id) }}">
    @csrf
    @method('PATCH')
    <div class="adm-field" style="margin-bottom:0">
      <label for="f_status">Change status</label>
      <select class="adm-select" id="f_status" name="status" data-adm-autosubmit>
        @foreach($inbox['statuses'] as $key => $meta)
          <option value="{{ $key }}" {{ $item->status === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
        @endforeach
      </select>
    </div>
  </form>
</x-admin.card>

<x-admin.card title="Details">
  <dl class="adm-dl">
    <x-admin.detail-row label="Read">@if($item->read_at)<x-admin.datetime :value="$item->read_at" />@endif</x-admin.detail-row>
    <x-admin.detail-row label="Replied">@if($item->replied_at)<x-admin.datetime :value="$item->replied_at" />@endif</x-admin.detail-row>
    <x-admin.detail-row label="Team email">
      @if($item->mail_sent_at)<x-admin.badge tone="success">Sent</x-admin.badge>@else<x-admin.badge tone="warning">Not sent</x-admin.badge>@endif
    </x-admin.detail-row>
    <x-admin.detail-row label="IP address">{{ $item->ip_address }}</x-admin.detail-row>
  </dl>
</x-admin.card>

<x-admin.card title="Delete" class="adm-card--danger">
  <p class="adm-muted adm-small" style="margin-bottom:12px">This removes the record and its replies for good.</p>
  <x-admin.delete-form :action="route($inbox['route'] . '.destroy', $item->id)" message="Delete this record permanently?" label="Delete record" :block="true" />
</x-admin.card>
