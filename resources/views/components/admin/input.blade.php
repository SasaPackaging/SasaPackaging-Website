{{-- <x-admin.input name="email" label="Email" type="email" :required="true" hint="..." :value="$model->email ?? null" />
     Shows the old value and the validation error by itself. --}}
@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'hint' => null, 'required' => false])
<div class="adm-field {{ $errors->has($name) ? 'has-error' : '' }}">
  @if($label)<label for="f_{{ $name }}">{{ $label }}@if($required) <em>*</em>@endif</label>@endif
  <input class="adm-input" id="f_{{ $name }}" name="{{ $name }}" type="{{ $type }}"
         @if($type !== 'password') value="{{ old($name, $value) }}" @endif
         @if($required) required @endif {{ $attributes }}>
  @if($hint)<p class="adm-field__hint">{{ $hint }}</p>@endif
  @error($name)<p class="adm-field__error">{{ $message }}</p>@enderror
</div>
