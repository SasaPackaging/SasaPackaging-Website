@extends('emails.inbox.layout')

@section('heading', config('site.name'))

@section('body')
  <p style="margin:0 0 16px;">Dear {{ $invite->name }},</p>
  <p style="margin:0 0 16px;">{{ strtr(config('reviews.mail.intro'), [':site' => config('site.name')]) }}</p>

  @include('emails.inbox._button', ['url' => $invite->url, 'label' => config('reviews.mail.button')])

  <p style="margin:24px 0 0;color:#5b6b5e;font-size:13px;">
    {{ strtr(config('reviews.mail.note'), [':date' => optional($invite->expires_at)->format('d M Y') ?: '-']) }}
  </p>
@stop

@section('footer')
  {{ config('site.name') }}
@stop
