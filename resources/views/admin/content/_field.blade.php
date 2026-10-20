{{-- One field of a content group. Expects $i (index), $field, $value --}}
@php $type = $field['type'] ?? 'text'; $name = "f[$i]"; $err = 'f.' . $i; @endphp

@if($type === 'list')
  <div class="adm-field adm-list" data-list>
    <label>{{ $field['label'] }}</label>
    <div class="adm-list__rows" data-list-rows>
      @foreach($value as $r => $row)
        @include('admin.content._list-row', ['i' => $i, 'r' => $r, 'field' => $field, 'row' => $row])
      @endforeach
    </div>
    <template data-list-template>
      @include('admin.content._list-row', ['i' => $i, 'r' => '__ROW__', 'field' => $field, 'row' => []])
    </template>
    <button type="button" class="adm-btn adm-btn--ghost adm-btn--sm" data-list-add>+ {{ $field['add'] ?? 'Add' }}</button>
  </div>
@elseif($type === 'textarea')
  <div class="adm-field {{ $errors->has($err) ? 'has-error' : '' }}">
    <label for="f_{{ $i }}">{{ $field['label'] }}</label>
    <textarea class="adm-textarea" id="f_{{ $i }}" name="{{ $name }}" rows="3">{{ old($err, $value) }}</textarea>
    @if(!empty($field['hint']))<p class="adm-field__hint">{{ $field['hint'] }}</p>@endif
    @error($err)<p class="adm-field__error">{{ $message }}</p>@enderror
  </div>
@else
  <div class="adm-field {{ $errors->has($err) ? 'has-error' : '' }}">
    <label for="f_{{ $i }}">{{ $field['label'] }}</label>
    <input class="adm-input" id="f_{{ $i }}" name="{{ $name }}" type="{{ $type === 'number' ? 'number' : 'text' }}" value="{{ old($err, $value) }}"
           @if($type === 'number') min="0" step="{{ $field['step'] ?? 1 }}" @if(isset($field['max'])) max="{{ $field['max'] }}" @endif @endif>
    @if(!empty($field['hint']))<p class="adm-field__hint">{{ $field['hint'] }}</p>@endif
    @error($err)<p class="adm-field__error">{{ $message }}</p>@enderror
  </div>
@endif
