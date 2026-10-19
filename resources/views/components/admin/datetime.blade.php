{{-- <x-admin.datetime :value="$item->created_at" />   shown in the admin time zone (config/admin.php) --}}
@props(['value', 'format' => null])
@if($value)
<time datetime="{{ $value->toIso8601String() }}" title="{{ $value->copy()->timezone(config('admin.timezone'))->format(config('admin.datetime_format')) }}">{{ $value->copy()->timezone(config('admin.timezone'))->format($format ?: config('admin.datetime_format')) }}</time>
@else
<span class="adm-muted">—</span>
@endif
