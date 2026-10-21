{{-- pages\careers.blade.php  (Careers: open positions + apply drawer)
     Looks : public/assets/main/css/sasa-careers.css   Behaviour: public/assets/main/js/sasa-careers.js
     Data  : tbl_job_openings (Admin later), questions per role in config/careers.php
     Submit: POST /careers/apply  (CareerController@apply) --}}
@extends('layouts.master')

@section('customstyle')
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner.css" rel="stylesheet">
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner-forms.css" rel="stylesheet">
<link href="{{ url('/') }}/public/assets/main/css/sasa-careers.css" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
@php
$title = trim(strip_tags((string) ($cms_dp['banner_heading'] ?? ''))) !== '' ? strip_tags($cms_dp['banner_heading']) : 'Careers';
$crumb = $cms_dp['name'] ?? 'Careers';
try {
    $openings = \App\Model\JobOpening::where('status', 'Yes')->orderBy('sort_order')->get();
} catch (\Exception $e) {
    $openings = collect(); // table not migrated yet
}
$levels = config('careers.education_levels');
$years  = range((int) date('Y') + 6, 1990);
@endphp

<main class="sxi sxf sxc" id="main">

	<section class="sxi-hero sxf-hero">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<div class="sxi-wrap">
			<ul class="sxi-crumbs">
				<li><a href="{{ url('/') }}">Home</a></li>
				<li>{{ $crumb }}</li>
			</ul>
			<span class="sxi-eyebrow">Join our team</span>
			<h1 class="sxi-h1" data-split>{{ $title }}</h1>
			<p class="sxf-lead">Help us build packaging that businesses trust. Pick a role, tell us about yourself, and attach your CV.</p>
		</div>
	</section>

	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxc-top" id="open-positions">
				<h2>Open positions</h2>
				<span>{{ count($openings) }} {{ count($openings) === 1 ? 'role' : 'roles' }} open</span>
			</div>

			<div class="sxc-list">
				@forelse ($openings as $o)
				<article class="sxc-job">
					<div class="sxc-job__main">
						<h3>{{ $o->title }}</h3>
						@if ($o->summary)<p>{{ $o->summary }}</p>@endif
					</div>
					<ul class="sxc-tags">
						@if ($o->department)<li>{{ $o->department }}</li>@endif
						<li>{{ $o->location }}</li>
						<li>{{ $o->job_type }}</li>
					</ul>
					<button type="button" class="sxi-btn sxi-btn--dark" data-apply data-id="{{ $o->id }}" data-title="{{ $o->title }}" data-profile="{{ $o->profile }}">Apply</button>
				</article>
				@empty
				<p class="sxc-empty">There are no open positions right now. You can still send us your CV below.</p>
				@endforelse
			</div>

			<div class="sxc-general">
				<div>
					<h3>Don't see your role?</h3>
					<p>Send a general application and we will keep your CV on file.</p>
				</div>
				<button type="button" class="sxi-btn sxi-btn--line" data-apply data-id="" data-title="General application" data-profile="general">Send your CV</button>
			</div>
		</div>
	</section>

	<div class="sxc-ov" data-ov hidden></div>
	<aside class="sxc-drawer" role="dialog" aria-modal="true" aria-labelledby="sxc-dt" data-drawer hidden>
		<header class="sxc-dh">
			<div><small>Applying for</small><h2 id="sxc-dt" data-title></h2></div>
			<button type="button" class="sxc-x" data-close aria-label="Close application form">&times;</button>
		</header>

		<ol class="sxc-rail" data-rail>
			<li>About you</li><li>Education</li><li>Experience</li><li>Role &amp; CV</li>
		</ol>

		<form id="sxc-form" method="post" action="{{ url('/careers/apply') }}" enctype="multipart/form-data" novalidate data-form>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="job_opening_id" value="" data-oid>
			<input type="text" name="website" class="sxc-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

			<section class="sxc-step" data-step>
				<div class="sxf-grid">
					<div class="sxf-field"><label for="c_fn">First name</label><input type="text" id="c_fn" name="first_name" autocomplete="given-name" data-req data-label="your first name"></div>
					<div class="sxf-field"><label for="c_ln">Last name</label><input type="text" id="c_ln" name="last_name" autocomplete="family-name" data-req data-label="your last name"></div>
					<div class="sxf-field sxf-field--full"><label for="c_em">Email</label><input type="text" id="c_em" name="email" inputmode="email" autocomplete="email" data-req data-type="email" data-label="a valid email"></div>
					<div class="sxf-field"><label for="c_ph">Phone</label><input type="text" id="c_ph" name="phone" inputmode="tel" autocomplete="tel" data-req data-label="your phone number"></div>
					<div class="sxf-field"><label for="c_ct">City</label><input type="text" id="c_ct" name="city" autocomplete="address-level2" data-req data-label="your city"></div>
					<div class="sxf-field sxf-field--full"><label for="c_li">LinkedIn <em>(optional)</em></label><input type="text" id="c_li" name="linkedin_url" placeholder="https://"></div>
				</div>
			</section>

			<section class="sxc-step" data-step hidden>
				<div class="sxf-grid">
					<div class="sxf-field sxf-field--full"><label for="c_lv">Highest qualification</label>
						<select id="c_lv" name="education_level" data-req data-label="your qualification">
							<option value="">Select</option>
							@foreach ($levels as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
						</select></div>
					<div class="sxf-field sxf-field--full"><label for="c_dg">Degree / field of study</label><input type="text" id="c_dg" name="degree" placeholder="e.g. BS Graphic Design" data-req data-label="your degree"></div>
					<div class="sxf-field"><label for="c_in">Institution</label><input type="text" id="c_in" name="institution" data-req data-label="your institution"></div>
					<div class="sxf-field"><label for="c_yr" data-yrlabel>Year completed</label>
						<select id="c_yr" name="grad_year" data-req data-label="the year">
							<option value="">Select</option>
							@foreach ($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
						</select></div>
				</div>
			</section>

			<section class="sxc-step" data-step hidden>
				<p class="sxc-q">Do you have prior work experience?</p>
				<div class="sxc-choice">
					<label><input type="radio" name="has_experience" value="yes" data-req-group data-label="whether you have experience"><span>Yes, I have worked before</span></label>
					<label><input type="radio" name="has_experience" value="no"><span>No, I am a fresher</span></label>
				</div>
				<div class="sxc-reveal" data-exp><div class="sxc-reveal__in">
					<div class="sxf-grid">
						<div class="sxf-field"><label for="c_ey">Total experience</label>
							<select id="c_ey" name="experience_years" data-req data-label="your experience" disabled>
								<option value="">Select</option>
								@foreach (config('careers.experience_years') as $e)<option value="{{ $e }}">{{ $e }}</option>@endforeach
							</select></div>
						<div class="sxf-field"><label for="c_lt">Last job title</label><input type="text" id="c_lt" name="last_title" data-req data-label="your last job title" disabled></div>
						<div class="sxf-field sxf-field--full"><label for="c_le">Last employer</label><input type="text" id="c_le" name="last_employer" data-req data-label="your last employer" disabled></div>
						<div class="sxf-field sxf-field--full"><label for="c_ps">Previous salary (PKR / month)</label>
							<input type="text" id="c_ps" name="previous_salary" inputmode="numeric" data-req data-num data-label="your previous salary" disabled>
							<label class="sxc-check"><input type="checkbox" name="is_unpaid" value="1" data-unpaid disabled> It was unpaid (internship / volunteer). Salary will be saved as N/A.</label></div>
					</div>
				</div></div>
				<div class="sxf-field sxc-exp"><label for="c_xs">Expected salary (PKR / month) <em>(optional)</em></label><input type="text" id="c_xs" name="expected_salary" inputmode="numeric" data-num></div>
			</section>

			<section class="sxc-step" data-step hidden>
				<div class="sxf-grid" data-extra></div>
				<div class="sxf-grid">
					<div class="sxf-field sxf-field--full"><label for="c_cv">Your CV <em>(PDF or Word, up to 5 MB)</em></label><input type="file" id="c_cv" name="cv" accept=".pdf,.doc,.docx" data-req data-label="your CV"></div>
				</div>
			</section>

			<p class="sxc-err" role="alert" data-err></p>
			<div class="sxc-foot">
				<button type="button" class="sxi-btn sxi-btn--line" data-back>Back</button>
				<button type="button" class="sxi-btn sxi-btn--dark" data-next>Next</button>
				<button type="submit" class="sxi-btn sxi-btn--dark" data-submit hidden>Submit application</button>
			</div>
		</form>

		<div class="sxc-done" data-done hidden>
			<span class="sxc-done__tick" aria-hidden="true">&#10003;</span>
			<h3>Application sent</h3>
			<p data-donemsg></p>
			<button type="button" class="sxi-btn sxi-btn--line" data-close>Close</button>
		</div>
	</aside>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script>window.SXC = {profiles: @json(config('careers.profiles')), years: @json(config('careers.experience_years'))};</script>
<script src="{{ url('/') }}/public/assets/main/js/sasa-careers.js"></script>
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop
