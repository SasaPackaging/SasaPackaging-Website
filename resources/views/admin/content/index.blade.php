@extends('admin.layouts.app')
@section('title', 'Landing content')

@section('content')
  <x-admin.page-header title="Landing content" subtitle="Change the wording of the website. Pick a section; the design stays the same." />

  @foreach($hub as $title => $groups)
    <h2 class="adm-section-title">{{ $title }}</h2>
    <div class="adm-grid adm-grid--cards">
      @foreach($groups as $g)
        <a class="adm-tile" href="{{ route('admin.landing.edit', $g['key']) }}">
          <span class="adm-tile__icon"><x-admin.icon :name="$g['icon'] ?? 'layout'" /></span>
          <span class="adm-tile__title">{{ $g['label'] }}</span>
          @if(!empty($g['about']))<span class="adm-tile__text">{{ $g['about'] }}</span>@endif
        </a>
      @endforeach
    </div>
  @endforeach

  <h2 class="adm-section-title">Texts that live in lists</h2>
  <div class="adm-grid adm-grid--cards">
    @foreach($tables as $key => $t)
      <a class="adm-tile" href="{{ route('admin.records.index', $key) }}">
        <span class="adm-tile__icon"><x-admin.icon :name="$t['icon'] ?? 'layout'" /></span>
        <span class="adm-tile__title">{{ $t['label'] }}</span>
        <span class="adm-tile__text">{{ $t['about'] }}</span>
      </a>
    @endforeach
  </div>
@stop
