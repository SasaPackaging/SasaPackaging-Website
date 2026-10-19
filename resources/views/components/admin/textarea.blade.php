{{-- <x-admin.textarea name="body" label="Message" :required="true" rows="8" /> --}}
@props(['name', 'label' => null, 'value' => null, 'hint' => null, 'required' => false])
<div class="adm-field {{ $errors->has($name) ? 'has-error' : '' }}">
  @if($label)<label for="f_{{ $name }}">{{ $label }}@if($required) <em>*</em>@endif</label>@endif
  <textarea class="adm-textarea" id="f_{{ $name }}" name="{{ $name }}" @if($required) required @endif {{ $attributes }}>{{ old($name, $value) }}</textarea>
  @if($hint)<p class="adm-field__hint">{{ $hint }}</p>@endif
  @error($name)<p class="adm-field__error">{{ $message }}</p>@enderror
</div>
