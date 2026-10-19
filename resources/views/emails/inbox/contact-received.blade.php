@extends('emails.inbox.layout')

@section('heading', 'New contact message')

@section('body')
  @include('emails.inbox._rows', ['rows' => [
      'Name'    => $contact->name,
      'Email'   => $contact->email,
      'Phone'   => $contact->phone,
      'Subject' => $contact->subject,
      'Message' => $contact->message,
  ]])

  @if(Route::has('admin.contact-messages.show'))
    @include('emails.inbox._button', ['url' => route('admin.contact-messages.show', $contact->id), 'label' => __('inbox.mail.open_in_admin')])
  @endif
@stop

@section('footer')
  {{ __('inbox.mail.reply_hint') }}
@stop
