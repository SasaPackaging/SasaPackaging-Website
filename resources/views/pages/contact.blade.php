{{-- pages\contact.blade.php  (Contact us)
     Looks : public/assets/main/css/sasa-inner-forms.css   Behaviour: public/assets/main/js/sasa-forms.js
     Data  : $cms_dp (Admin > Pages: banner heading, form heading, side image) + Admin settings
             (CONTACT_EMAIL, ADDRESS, PHONE, POBOX).
     The form still posts to /common/contact_process with the same field names as before. --}}
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
$email   = trim((string) ($cfg['CONTACT_EMAIL'] ?? ''));
$address = trim((string) ($cfg['ADDRESS'] ?? ''));
$phone1  = trim((string) ($cfg['PHONE'] ?? ''));
$phone2  = trim((string) ($cfg['POBOX'] ?? ''));
$tel     = function ($v) { return preg_replace('/[^0-9+]/', '', (string) $v); };

$title   = trim(strip_tags((string) ($cms_dp['banner_heading'] ?? ''))) !== '' ? strip_tags($cms_dp['banner_heading']) : ($cms_dp['name'] ?? 'Contact us');
$heading = trim(strip_tags((string) ($cms_dp['heading'] ?? ''))) !== '' ? strip_tags($cms_dp['heading']) : 'Send us a message';
$photo   = trim((string) ($cms_dp['image'] ?? ''));
$crumb   = $cms_dp['name'] ?? 'Contact us';
@endphp

<main class="sxi sxf" id="main">

	<section class="sxi-hero sxf-hero">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<div class="sxi-wrap">
			<ul class="sxi-crumbs">
				<li><a href="{{ url('/') }}">Home</a></li>
				<li>{{ $crumb }}</li>
			</ul>
			<span class="sxi-eyebrow">Get in touch</span>
			<h1 class="sxi-h1" data-split>{{ $title }}</h1>
			<p class="sxf-lead">Questions about a project, a quote or an order? Send us a message and our team will get back to you.</p>
		</div>
	</section>

	<section class="sxi-section">
		<div class="sxi-wrap">

			{{-- contact details --}}
			<div class="sxf-cards">
				@if($email !== '')
				<div class="sxf-ic sxi-reveal">
					<i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 7.5 8.5 6 8.5-6"/></svg></i>
					<div><small>Mail address</small><a href="mailto:{{ $email }}">{{ $email }}</a></div>
				</div>
				@endif
				@if($address !== '')
				<div class="sxf-ic sxi-reveal" style="--d:90ms">
					<i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg></i>
					<div><small>Office location</small><span class="sxf-ic__t">{!! nl2br(e($address)) !!}</span></div>
				</div>
				@endif
				@if($phone1 !== '' || $phone2 !== '')
				<div class="sxf-ic sxi-reveal" style="--d:180ms">
					<i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></i>
					<div><small>Phone number</small>
						@if($phone1 !== '')<a href="tel:{{ $tel($phone1) }}">{{ $phone1 }}</a>@endif
						@if($phone2 !== '')<a href="tel:{{ $tel($phone2) }}">{{ $phone2 }}</a>@endif
					</div>
				</div>
				@endif
			</div>

			<div class="sxf-layout">

				{{-- form --}}
				<div class="sxf-card">
					<div class="sxf-head">
						<h2>{{ $heading }}</h2>
						<p>Fill in the form and we will reply as soon as we can.</p>
					</div>

					<form id="contact-form" method="post" action="{{ url('/common/contact_process') }}" data-sxf-form data-endpoint="{{ url('/common/contact_process') }}" novalidate>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="sxf-grid">
							<div class="sxf-field">
								<label for="contact_full_name">Full name</label>
								<input type="text" id="contact_full_name" name="contact_full_name" placeholder="Enter your name" autocomplete="name" data-req data-label="your name">
							</div>
							<div class="sxf-field">
								<label for="contact_email">Email</label>
								<input type="text" id="contact_email" name="contact_email" placeholder="Enter your email" autocomplete="email" inputmode="email" data-req data-type="email" data-label="your email">
							</div>
							<div class="sxf-field">
								<label for="contact_phone">Phone number</label>
								<input type="text" id="contact_phone" name="contact_phone" placeholder="Enter your phone number" autocomplete="tel" inputmode="tel" data-req data-label="your phone number">
							</div>
							<div class="sxf-field">
								<label for="contact_subject">Subject</label>
								<input type="text" id="contact_subject" name="contact_subject" placeholder="What is this about?" data-req data-label="a subject">
							</div>
							<div class="sxf-field sxf-field--full">
								<label for="contact_message">Message</label>
								<textarea id="contact_message" name="contact_message" placeholder="Write your message here" data-req data-label="your message"></textarea>
							</div>
						</div>

						<div class="sxf-actions">
							<button type="submit" class="sxi-btn sxi-btn--dark" data-sxf-submit>
								<span data-sxf-label data-busy="Sending…">Send message</span>
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
							</button>
							<small>All fields are required.</small>
						</div>
						<p class="sxf-note" data-sxf-note role="status" aria-live="polite"></p>
					</form>
				</div>

				{{-- side: image from Admin (if any) + shortcut to the quote page --}}
				<aside class="sxf-side">
					@if($photo !== '')
					<div class="sxf-photo"><img src="{{ $photo }}" alt="" loading="lazy" onerror="this.remove()"></div>
					@endif
					<div class="sxf-panel">
						<h3>Need a price?</h3>
						<p>Tell us about your packaging, sizes and quantities and we will prepare a quote for you.</p>
						<a class="sxi-btn sxi-btn--solid" href="{{ url('/') }}/get-quote.html">Get a Quote <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
					</div>
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