@extends('emails.inbox.layout')

@section('heading', config('site.name'))

@section('body')
  <p style="margin:0 0 16px;">{!! nl2br(e($replyBody)) !!}</p>

  @if($signedBy)
    <p style="margin:0 0 16px;color:#425246;">{{ $signedBy }}<br>{{ config('site.name') }}</p>
  @endif

  <div style="margin-top:24px;padding:12px 16px;border-left:3px solid #B8CFB3;background:#F6E2E9;color:#5b6b5e;font-size:13px;">
    <strong>{{ __('inbox.mail.your_message') }}</strong><br>
    @if(isset($inquiry->subject))<em>{{ $inquiry->subject }}</em><br>@endif
    {!! nl2br(e(\Illuminate\Support\Str::limit((string) ($inquiry->message ?? $inquiry->item_name), 600))) !!}
  </div>
@stop

@section('footer')
  {{ __('inbox.mail.reply_footer', ['site' => config('site.name')]) }}
@stop
