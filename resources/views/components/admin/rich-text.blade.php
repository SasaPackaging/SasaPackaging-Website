{{-- <x-admin.rich-text name="full_contents" label="Page content" :value="$row->full_contents ?? ''" />
     Simple editor: bold, italic, bullet list, link. "HTML" switches to the raw code. JS: js/content.js --}}
@props(['name', 'label' => null, 'value' => null, 'hint' => null])
<div class="adm-field {{ $errors->has($name) ? 'has-error' : '' }}" data-rich>
  @if($label)<label>{{ $label }}</label>@endif
  <div class="adm-rich">
    <div class="adm-rich__bar" role="toolbar">
      <button type="button" data-cmd="bold" title="Bold"><b>B</b></button>
      <button type="button" data-cmd="italic" title="Italic"><i>I</i></button>
      <button type="button" data-cmd="insertUnorderedList" title="Bullet list">• List</button>
      <button type="button" data-cmd="formatBlock" data-arg="h3" title="Heading">Heading</button>
      <button type="button" data-cmd="formatBlock" data-arg="p" title="Normal text">Text</button>
      <button type="button" data-cmd="createLink" title="Link">Link</button>
      <button type="button" data-cmd="removeFormat" title="Clear formatting">Clear</button>
      <button type="button" data-rich-code class="adm-rich__code" title="Show / hide HTML code">HTML</button>
    </div>
    <div class="adm-rich__area" contenteditable="true" data-rich-area>{!! old($name, $value) !!}</div>
    <textarea class="adm-rich__src" name="{{ $name }}" data-rich-src hidden>{{ old($name, $value) }}</textarea>
  </div>
  @if($hint)<p class="adm-field__hint">{{ $hint }}</p>@endif
  @error($name)<p class="adm-field__error">{{ $message }}</p>@enderror
</div>
