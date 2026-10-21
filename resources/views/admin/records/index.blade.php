@extends('admin.layouts.app')
@section('title', $def['label'])

@section('content')
  <x-admin.page-header :title="$def['label']" :subtitle="$def['about']" :crumbs="[['Landing content', route('admin.landing.index')], [$def['label']]]">
    @if(!empty($def['creatable']))
      <x-slot name="actions"><x-admin.button :href="route('admin.records.create', $def['key'])">Add {{ $def['singular'] }}</x-admin.button></x-slot>
    @endif
  </x-admin.page-header>

  <x-admin.card :flush="true">
    @if($rows->count())
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr>
            @foreach($def['list'] as $col => $label)@if(in_array($col, $def['columns']))<th>{{ $label }}</th>@endif @endforeach
            <th class="is-right">Action</th>
          </tr></thead>
          <tbody>
          @foreach($rows as $row)
            <tr>
              @foreach($def['list'] as $col => $label)
                @if(in_array($col, $def['columns']))
                  <td>
                    @if($col === 'status')
                      <x-admin.badge :tone="$row->status === 'Yes' ? 'success' : 'neutral'">{{ $row->status === 'Yes' ? 'Shown' : 'Hidden' }}</x-admin.badge>
                    @elseif($col === $def['title'])
                      <span class="adm-table__main">{{ strip_tags(html_entity_decode((string) $row->$col)) ?: '(no title)' }}</span>
                    @else
                      {{ $row->$col }}
                    @endif
                  </td>
                @endif
              @endforeach
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <x-admin.button :href="route('admin.records.edit', [$def['key'], $row->id])" variant="ghost" size="sm">Edit</x-admin.button>
                  @if(!empty($def['deletable']))<x-admin.delete-form :action="route('admin.records.destroy', [$def['key'], $row->id])" message="Delete this {{ $def['singular'] }} for good?" />@endif
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      {{ $rows->links('admin.partials.pagination') }}
    @else
      <x-admin.empty-state title="Nothing here yet" />
    @endif
  </x-admin.card>
@stop
