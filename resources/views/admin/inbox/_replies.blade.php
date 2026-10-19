{{-- Reply form + history.  Needs: $item  $inbox  $defaultSubject --}}
<x-admin.card title="Reply by email">
  <p class="adm-muted adm-small" style="margin-bottom:14px">Sent to <strong>{{ $item->email }}</strong>. Replies from the customer come to the company inbox.</p>

  <form method="post" action="{{ route($inbox['route'] . '.reply', $item->id) }}">
    @csrf
    <x-admin.input name="subject" label="Subject" :value="$defaultSubject" :required="true" />
    <x-admin.textarea name="body" label="Message" :required="true" rows="7" />
    <x-admin.button icon="send">Send reply</x-admin.button>
  </form>
</x-admin.card>

@if($item->replies->isNotEmpty())
<x-admin.card title="Sent replies ({{ $item->replies->count() }})">
  @foreach($item->replies as $reply)
    <div class="adm-reply">
      <div class="adm-reply__meta">
        <strong>{{ $reply->subject }}</strong><br>
        {{ $reply->admin ? $reply->admin->name : 'Admin' }} · <x-admin.datetime :value="$reply->created_at" />
      </div>
      <div class="adm-reply__body">{{ $reply->body }}</div>
    </div>
  @endforeach
</x-admin.card>
@endif
