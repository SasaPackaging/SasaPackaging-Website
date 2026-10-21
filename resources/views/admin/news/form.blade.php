@extends('admin.layouts.app')
@section('title', $item->exists ? 'Edit ' . $item->type_label : 'Add news / event')

@push('styles')<link rel="stylesheet" href="@adminAsset('css/news.css')">@endpush
@push('scripts')
<script src="@adminAsset('js/content.js')"></script>
<script src="@adminAsset('js/news.js')"></script>
@endpush

@section('content')
  @php
    $isNew = !$item->exists;
    $type  = old('type', $item->type);
    $image = config('news.image');
  @endphp
  <x-admin.page-header :title="$isNew ? 'Add news / event' : 'Edit ' . $item->type_label" :crumbs="[['News & events', route('admin.news.index')], [$isNew ? 'New' : 'Edit']]">
    <x-slot name="actions">
      @if(!$isNew && $item->is_published)<x-admin.button :href="url('/') . '#news-events'" variant="ghost" target="_blank" rel="noopener">View on website ↗</x-admin.button>@endif
      <x-admin.button :href="route('admin.news.index')" variant="ghost" icon="arrow-left">Back</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.news.store') : route('admin.news.update', $item->id) }}" data-news-form>
    @csrf
    @unless($isNew) @method('PUT') @endunless

    <div class="adm-split">
      <div>
        <x-admin.card>
          <x-admin.select name="type" label="What is it?" :options="$types" :value="$item->type" :required="true" data-news-type :hint="implode(' ', config('news.type_hints'))" />
          <x-admin.input name="title" label="Title" :value="$item->title" :required="true" maxlength="200" />
          <x-admin.textarea name="summary" label="Short text (shown on the cards)" :value="$item->summary" rows="3" maxlength="400" hint="One or two sentences. Maximum 400 letters." />
          <x-admin.rich-text name="body" label="Full story (optional)" :value="$item->body" />
        </x-admin.card>
      </div>

      <div class="adm-split__side">
        <x-admin.card title="Publish">
          <x-admin.input name="published_at" type="date" label="Publish date" :value="optional($item->published_at)->format('Y-m-d')" :required="true" />
          <div data-news-event {{ $type === 'event' ? '' : 'hidden' }}>
            <x-admin.input name="event_date" type="date" label="Event date" :value="optional($item->event_date)->format('Y-m-d')" hint="The day the event happens." />
            <x-admin.input name="location" label="Place (optional)" :value="$item->location" maxlength="150" />
          </div>
          <label class="adm-check">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $item->is_published ? 1 : 0) ? 'checked' : '' }}>
            <span>Show on the website</span>
          </label>
          <x-admin.button :block="true">{{ $isNew ? 'Add' : 'Save changes' }}</x-admin.button>
        </x-admin.card>

        <x-admin.card title="Pictures (optional)">
          @if($item->image_urls)
            <div class="adm-gallery">
              @foreach($item->image_urls as $gi => $gurl)
                <label class="adm-gallery__item">
                  <img src="{{ $gurl }}" alt="">
                  @if($gi === 0)<em>Cover</em>@endif
                  <span><input type="checkbox" name="remove_images[]" value="{{ $item->images[$gi] }}"> Remove</span>
                </label>
              @endforeach
            </div>
          @endif
          <div class="adm-field {{ $errors->has('images') || $errors->has('images.*') ? 'has-error' : '' }}">
            <label for="f_images">{{ $item->image_urls ? 'Add more pictures' : 'Upload pictures' }}</label>
            <input class="adm-input" id="f_images" type="file" name="images[]" multiple accept=".{{ implode(',.', $image['extensions']) }}">
            <p class="adm-field__hint">Up to {{ $image['max_count'] }} pictures ({{ strtoupper(implode(', ', $image['extensions'])) }}, {{ round($image['max_kb'] / 1024) }} MB each). The first one is the cover.</p>
            @error('images')<p class="adm-field__error">{{ $message }}</p>@enderror
            @foreach($errors->get('images.*') as $msgs)<p class="adm-field__error">{{ $msgs[0] }}</p>@endforeach
          </div>
        </x-admin.card>

        <x-admin.card title="Video (optional)">
          <x-admin.input name="video_url" label="Video link" :value="$item->video_url" placeholder="https://www.youtube.com/watch?v=..." hint="YouTube or Vimeo link (or a direct .mp4). It plays on the news page." />
        </x-admin.card>
      </div>
    </div>
  </form>
@stop
