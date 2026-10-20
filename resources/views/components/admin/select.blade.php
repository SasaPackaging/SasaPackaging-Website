{{-- <x-admin.select name="role" label="Role" :options="['a' => 'Label A', 'b' => 'Label B']" :value="$x" :required="true" />
     Shows the old value and the validation error by itself. --}}
@props(['name', 'options', 'label' => null, 'value' => null, 'hint' => null, 'required' => false])
<div class="adm-field {{ $errors->has($name) ? 'has-error' : '' }}">
  @if($label)<label for="f_{{ $name }}">{{ $label }}@if($required) <em>*</em>@endif</label>@endif
  <select class="adm-select" id="f_{{ $name }}" name="{{ $name }}" @if($required) required @endif {{ $attributes }}>
    @foreach($options as $optValue => $optLabel)
      <option value="{{ $optValue }}" {{ (string) old($name, $value) === (string) $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
    @endforeach
  </select>
  @if($hint)<p class="adm-field__hint">{{ $hint }}</p>@endif
  @error($name)<p class="adm-field__error">{{ $message }}</p>@enderror
</div>
