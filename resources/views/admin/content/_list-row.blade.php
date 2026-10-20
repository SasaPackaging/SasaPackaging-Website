{{-- One row of a list field. Expects $i (field index), $r (row index or __ROW__), $field, $row --}}
<div class="adm-list__row" data-list-row>
  <div class="adm-list__cols">
    @foreach($field['columns'] as $col)
      @php $ctype = $col['type'] ?? 'text'; $cname = "f[$i][$r][{$col['name']}]"; $cval = old("f.$i.$r.{$col['name']}", $row[$col['name']] ?? ''); @endphp
      <div class="adm-field">
        <label>{{ $col['label'] }}</label>
        @if($ctype === 'textarea')
          <textarea class="adm-textarea" name="{{ $cname }}" rows="2">{{ $cval }}</textarea>
        @else
          <input class="adm-input" type="text" name="{{ $cname }}" value="{{ $cval }}">
        @endif
      </div>
    @endforeach
  </div>
  <button type="button" class="adm-list__remove" data-list-remove aria-label="Remove row" title="Remove">×</button>
</div>
