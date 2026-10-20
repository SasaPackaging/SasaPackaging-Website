@extends('admin.layouts.app')
@section('title', 'Admin users')

@section('content')
  <x-admin.page-header title="Admin users" subtitle="Who can log in, and what each person is allowed to open." />

  <div class="adm-split">
    <x-admin.card :flush="true">
      <div class="adm-tablewrap">
        <table class="adm-table">
          <thead><tr><th>Name</th><th>Role</th><th>Last login</th><th class="is-right">Action</th></tr></thead>
          <tbody>
          @foreach($admins as $a)
            <tr>
              <td><span class="adm-table__main">{{ $a->name }}@if($a->id === auth('admin')->id()) <span class="adm-muted">(you)</span>@endif</span><span class="adm-table__sub">{{ $a->email }}</span></td>
              <td><x-admin.badge :tone="$a->isSuper() ? 'success' : 'info'">{{ $a->role_label }}</x-admin.badge></td>
              <td class="adm-nowrap"><x-admin.datetime :value="$a->last_login_at" /></td>
              <td class="is-right">
                <div class="adm-row" style="justify-content:flex-end">
                  <x-admin.button :href="route('admin.admin-users.edit', $a->id)" variant="ghost" size="sm">Edit</x-admin.button>
                  @if($a->id !== auth('admin')->id())
                    <x-admin.delete-form :action="route('admin.admin-users.destroy', $a->id)" message="Remove {{ $a->name }}? They will no longer be able to log in." label="Remove" />
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </x-admin.card>

    <div class="adm-split__side">
      <x-admin.card title="Add an admin">
        <form method="post" action="{{ route('admin.admin-users.store') }}">
          @csrf
          <x-admin.input name="name" label="Full name" :required="true" />
          <x-admin.input name="email" label="Email (used to log in)" type="email" :required="true" />
          <x-admin.select name="role" label="Role" :options="$roles" :value="config('admin.default_role')" :required="true" />
          <x-admin.input name="password" label="Password" type="password" :required="true" hint="At least 8 characters." autocomplete="new-password" />
          <x-admin.button :block="true">Add admin</x-admin.button>
        </form>
      </x-admin.card>
    </div>
  </div>
@stop
