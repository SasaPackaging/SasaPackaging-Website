@extends('admin.layouts.auth')
@section('title', 'Sign in')

@section('content')
  <span class="adm-auth__logo">{{ strtoupper(mb_substr(config('admin.brand') ?: config('site.name'), 0, 1)) }}</span>
  <h1>Welcome back</h1>
  <p>Sign in to manage {{ config('site.name') }}.</p>

  <form method="post" action="{{ route('admin.login.submit') }}" novalidate>
    @csrf
    <x-admin.input name="email" label="Email" type="email" :required="true" autocomplete="username" autofocus />
    <x-admin.input name="password" label="Password" type="password" :required="true" autocomplete="current-password" />

    <div class="adm-field">
      <label class="adm-check"><input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Keep me signed in</label>
    </div>

    <x-admin.button :block="true">Sign in</x-admin.button>
  </form>

  <p class="adm-auth__foot"><a href="{{ url('/') }}">← Back to website</a></p>
@stop
