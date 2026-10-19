@extends('emails.inbox.layout')

@section('heading', 'New quote request ' . $quote->reference)

@section('body')
  @include('emails.inbox._rows', ['rows' => [
      'Reference'    => $quote->reference,
      'Product'      => $quote->product_label,
      'Service'      => $quote->service_label,
      'Product name' => $quote->item_name,
      'Size'         => $quote->size_text,
      'Stock'        => $quote->stock,
      'Coating'      => config('quote.coatings.' . $quote->coating, $quote->coating),
      'Add ons'      => $quote->add_ons,
      'Colours'      => $quote->colours,
      'Packing'      => $quote->packing,
      'Quantity 1'   => number_format($quote->quantity_1),
      'Quantity 2'   => number_format($quote->quantity_2),
      'File'         => $quote->file_original_name,
  ]])

  <h3 style="margin:22px 0 4px;color:#1f2d22;font-size:15px;">Customer</h3>
  @include('emails.inbox._rows', ['rows' => [
      'Name'    => $quote->full_name,
      'Company' => $quote->company,
      'Email'   => $quote->email,
      'Phone'   => $quote->phone,
      'Message' => $quote->message,
  ]])

  @if(Route::has('admin.quote-requests.show'))
    @include('emails.inbox._button', ['url' => route('admin.quote-requests.show', $quote->id), 'label' => __('inbox.mail.open_in_admin')])
  @endif
@stop

@section('footer')
  {{ __('inbox.mail.reply_hint') }}
@stop
