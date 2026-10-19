{{-- pages\quote.blade.php  (Get a Quote)
     Looks : public/assets/main/css/sasa-inner-forms.css   Behaviour: public/assets/main/js/sasa-forms.js
     The form still posts (with the file) to /send_quote using the same field names as before,
     except the Packing select, which now has its own name (q_packing). See the notes in the chat. --}}
@extends('layouts.master')

@section('customstyle')
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner.css" rel="stylesheet">
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner-forms.css" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
@php
$settings = App\Model\Setting::select('key_name', 'key_value')->get()->toArray();
$cfg = [];
foreach ($settings as $s) { $cfg[$s['key_name']] = $s['key_value']; }
$email  = trim((string) ($cfg['CONTACT_EMAIL'] ?? ''));
$phone1 = trim((string) ($cfg['PHONE'] ?? ''));
$tel    = function ($v) { return preg_replace('/[^0-9+]/', '', (string) $v); };
@endphp

<main class="sxi sxf" id="main">

	<section class="sxi-hero sxf-hero">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<div class="sxi-wrap">
			<ul class="sxi-crumbs">
				<li><a href="{{ url('/') }}">Home</a></li>
				<li>Get a Quote</li>
			</ul>
			<span class="sxi-eyebrow">Free quote</span>
			<h1 class="sxi-h1" data-split>Get a Quote</h1>
			<p class="sxf-lead">Tell us about your box or bag (size, material, finish and quantity) and we will prepare your quote.</p>
		</div>
	</section>

	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxf-layout sxf-layout--quote">

				<div class="sxf-card">
					<form id="form_register" name="form_register" method="post" action="{{ url('send_quote') }}" enctype="multipart/form-data" data-sxf-form data-endpoint="{{ url('send_quote') }}" novalidate>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						{{-- 1. size --}}
						<div class="sxf-block">
							<div class="sxf-block__head"><span class="sxf-num">1</span><h3>Size</h3></div>
							<div class="sxf-grid">
								<div class="sxf-field">
									<label for="q_length">Length</label>
									<input type="text" id="q_length" name="q_length" placeholder="Enter length" inputmode="decimal" data-req data-label="the length">
								</div>
								<div class="sxf-field">
									<label for="q_width">Width</label>
									<input type="text" id="q_width" name="q_width" placeholder="Enter width" inputmode="decimal" data-req data-label="the width">
								</div>
								<div class="sxf-field">
									<label for="q_height">Height</label>
									<input type="text" id="q_height" name="q_height" placeholder="Enter height" inputmode="decimal" data-req data-label="the height">
								</div>
								<div class="sxf-field">
									<label for="q_unit">Unit</label>
									<select id="q_unit" name="q_unit" data-req data-label="a unit">
										<option value="">Please choose unit</option>
										@foreach($quoteOptions['units'] as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						{{-- 2. product details --}}
						<div class="sxf-block">
							<div class="sxf-block__head"><span class="sxf-num">2</span><h3>Product details</h3></div>
							<div class="sxf-grid">
								@if(!empty($quoteOptions['products']))
								<div class="sxf-field">
									<label for="q_product_id">Product (optional)</label>
									<select id="q_product_id" name="q_product_id">
										<option value="">Select a product</option>
										@foreach($quoteOptions['products'] as $value => $label)
										<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
								@endif
								@if(!empty($quoteOptions['services']))
								<div class="sxf-field">
									<label for="q_service_id">Service (optional)</label>
									<select id="q_service_id" name="q_service_id">
										<option value="">Select a service</option>
										@foreach($quoteOptions['services'] as $value => $label)
										<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
								@endif
								<div class="sxf-field sxf-field--full">
									<label for="q_produt_name">Product name</label>
									<input type="text" id="q_produt_name" name="q_produt_name" placeholder="e.g. Gift box, Tissue box" data-req data-label="the product name">
								</div>
								<div class="sxf-field">
									<label for="q_stock">Stock</label>
									<select id="q_stock" name="q_stock" data-req data-label="a stock">
										<option value="">Select stock</option>
										@foreach($quoteOptions['stocks'] as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
								<div class="sxf-field">
									<label for="q_coating">Coating</label>
									<select id="q_coating" name="q_coating" data-req data-label="a coating">
										@foreach($quoteOptions['coatings'] as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
								<div class="sxf-field">
									<label for="q_add_ons">Add ons</label>
									<select id="q_add_ons" name="q_add_ons" data-req data-label="an add on">
										@foreach($quoteOptions['add_ons'] as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
								<div class="sxf-field">
									<label for="q_colours">Colours</label>
									<select id="q_colours" name="q_colours" data-req data-label="the colours">
										@foreach($quoteOptions['colours'] as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
								<div class="sxf-field sxf-field--full">
									<label for="q_packing">Packing</label>
									<select id="q_packing" name="q_packing">
										@foreach($quoteOptions['packings'] as $value => $label)
											<option value="{{ $value }}">{{ $label }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

						{{-- 3. quantity --}}
						<div class="sxf-block">
							<div class="sxf-block__head"><span class="sxf-num">3</span><h3>Quantity</h3></div>
							<div class="sxf-grid">
								<div class="sxf-field">
									<label for="q_quantity_1">Quantity 1</label>
									<input type="text" id="q_quantity_1" name="q_quantity_1" placeholder="Quantity" inputmode="numeric" data-req data-label="quantity 1">
								</div>
								<div class="sxf-field">
									<label for="q_quantity_2">Quantity 2</label>
									<input type="text" id="q_quantity_2" name="q_quantity_2" placeholder="Quantity" inputmode="numeric" data-req data-label="quantity 2">
								</div>
							</div>
						</div>

						{{-- 4. file --}}
						<div class="sxf-block">
							<div class="sxf-block__head"><span class="sxf-num">4</span><h3>Upload file</h3></div>
							<label class="sxf-drop" for="q_file">
								<span class="sxf-drop__ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg></span>
								<span class="sxf-drop__txt">
									<strong data-sxf-name data-default="Choose a file or drop it here">Choose a file or drop it here</strong>
									<small>{{ $quoteOptions['file']['types'] }} · maximum {{ $quoteOptions['file']['max_mb'] }} MB</small>
								</span>
								<input type="file" id="q_file" name="q_file" accept="{{ $quoteOptions['file']['accept'] }}" data-req data-label="your file" data-max-mb="{{ $quoteOptions['file']['max_mb'] }}">
							</label>
						</div>

						{{-- 5. contact --}}
						<div class="sxf-block">
							<div class="sxf-block__head"><span class="sxf-num">5</span><h3>Contact information</h3></div>
							<div class="sxf-grid">
								<div class="sxf-field">
									<label for="q_full_name">Full name</label>
									<input type="text" id="q_full_name" name="q_full_name" placeholder="Enter your name" autocomplete="name" data-req data-label="your name">
								</div>
								<div class="sxf-field">
									<label for="q_phone">Phone number</label>
									<input type="text" id="q_phone" name="q_phone" placeholder="Enter your phone number" autocomplete="tel" inputmode="tel" data-req data-label="your phone number">
								</div>
								<div class="sxf-field">
									<label for="q_email">Email</label>
									<input type="text" id="q_email" name="q_email" placeholder="Enter your email" autocomplete="email" inputmode="email" data-req data-type="email" data-label="your email">
								</div>
								<div class="sxf-field">
									<label for="q_company">Company name</label>
									<input type="text" id="q_company" name="q_company" placeholder="Enter your company name" autocomplete="organization" data-req data-label="your company name">
								</div>
								<div class="sxf-field sxf-field--full">
									<label for="q_message">Message (optional)</label>
									<textarea id="q_message" name="q_message" placeholder="Anything else we should know?"></textarea>
								</div>
							</div>
						</div>

						<div class="sxf-actions">
							<button type="submit" class="sxi-btn sxi-btn--dark" data-sxf-submit>
								<span data-sxf-label data-busy="Sending…">Submit request</span>
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
							</button>
						</div>
						<p class="sxf-note" data-sxf-note role="status" aria-live="polite"></p>
					</form>
				</div>

				{{-- side: perks + shortcut to talk to us --}}
				<aside class="sxf-side sxf-side--sticky">
					<div class="sxf-box">
						<h3>What you get</h3>
						<ul class="sxf-list">
							<li>
								<i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7z"/><circle cx="7.5" cy="17.5" r="1.7"/><circle cx="17.5" cy="17.5" r="1.7"/></svg></i>
								<div><strong>Free shipping</strong><span>On your quoted order</span></div>
							</li>
							<li>
								<i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4 20 4-1 11-11a2.1 2.1 0 0 0-3-3L5 16z"/><path d="m14 7 3 3"/></svg></i>
								<div><strong>Free design support</strong><span>Help with your artwork</span></div>
							</li>
						</ul>
					</div>
					@if($phone1 !== '' || $email !== '')
					<div class="sxf-box sxf-talk">
						<h3>Prefer to talk?</h3>
						@if($phone1 !== '')<a href="tel:{{ $tel($phone1) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>{{ $phone1 }}</a>@endif
						@if($email !== '')<a href="mailto:{{ $email }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 7.5 8.5 6 8.5-6"/></svg>{{ $email }}</a>@endif
					</div>
					@endif
				</aside>

			</div>
		</div>
	</section>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-forms.js"></script>
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop