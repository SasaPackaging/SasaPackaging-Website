{{-- pages/feedback.blade.php  (/feedback/{token})
     Needs: $state (form|thanks|used|expired|invalid), $invite (ReviewInvite|null), $page (config reviews.page) --}}
@extends('layouts.master')

@section('customstyle')
<meta name="robots" content="noindex,nofollow">
<meta name="referrer" content="no-referrer">
<link href="{{ url('/') }}/public/assets/main/css/sasa-feedback.css" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
<main class="fbk" id="main">
	<div class="fbk-wrap">
		<div class="fbk-card">
		@if($state === 'form')
			<p class="fbk-eyebrow">{{ config('site.name') }}</p>
			<h1>{{ $page['title'] }}</h1>
			<p class="fbk-lead">Hello {{ $invite->name }}. {{ $page['lead'] }}</p>

			@if($errors->any())
				<div class="fbk-alert" role="alert">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
			@endif

			<form method="post" action="{{ url('/feedback/' . $invite->token) }}" novalidate>
				@csrf
				<fieldset class="fbk-rate">
					<legend>{{ $page['rating_label'] }}</legend>
					<div class="fbk-stars">
						@for($i = 5; $i >= 1; $i--)
							<input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ (string) old('rating') === (string) $i ? 'checked' : '' }} required>
							<label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"><span class="sr-only">{{ $i }} star{{ $i > 1 ? 's' : '' }}</span>&#9733;</label>
						@endfor
					</div>
				</fieldset>

				<div class="fbk-field">
					<label for="f_role">{{ $page['role_label'] }}</label>
					<input id="f_role" name="company_role" type="text" maxlength="150" value="{{ old('company_role', $invite->company) }}">
				</div>

				<div class="fbk-field">
					<label for="f_comment">{{ $page['comment_label'] }}</label>
					<textarea id="f_comment" name="comment" rows="6" required minlength="{{ config('reviews.comment_min') }}" maxlength="{{ config('reviews.comment_max') }}">{{ old('comment') }}</textarea>
				</div>

				{{-- honeypot: invisible to people, bots fill it --}}
				<div class="fbk-hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

				<button class="fbk-btn" type="submit">{{ $page['submit'] }}</button>
			</form>

		@elseif($state === 'thanks')
			<div class="fbk-state fbk-state--ok"><span aria-hidden="true">&#10003;</span>
				<h1>{{ $page['thanks_title'] }}</h1><p>{{ $page['thanks_text'] }}</p></div>
		@elseif($state === 'used')
			<div class="fbk-state"><h1>{{ $page['used_title'] }}</h1><p>{{ $page['used_text'] }}</p></div>
		@elseif($state === 'expired')
			<div class="fbk-state"><h1>{{ $page['expired_title'] }}</h1><p>{{ $page['expired_text'] }}</p>
				<a class="fbk-btn" href="{{ url('/contact-us.html') }}">Contact us</a></div>
		@else
			<div class="fbk-state"><h1>{{ $page['invalid_title'] }}</h1><p>{{ $page['invalid_text'] }}</p>
				<a class="fbk-btn" href="{{ url('/') }}">Back to home</a></div>
		@endif
		</div>
	</div>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop
