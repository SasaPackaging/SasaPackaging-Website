@extends('admin.layouts.app')
@section('title', 'Edit admin')

@section('content')
  <x-admin.page-header :title="'Edit ' . $admin->name" :crumbs="[['Admin users', route('admin.admin-users.index')], [$admin->name]]">
    <x-slot name="actions">
      <x-admin.button :href="route('admin.admin-users.index')" variant="ghost" icon="arrow-left">Back</x-admin.button>
    </x-slot>
  </x-admin.page-header>

  <x-admin.card style="max-width:560px">
    <form method="post" action="{{ route('admin.admin-users.update', $admin->id) }}">
      @csrf
      @method('PUT')
      <x-admin.input name="name" label="Full name" :value="$admin->name" :required="true" />
      <x-admin.input name="email" label="Email (used to log in)" type="email" :value="$admin->email" :required="true" />
      <x-admin.select name="role" label="Role" :options="$roles" :value="$admin->role" :required="true" />
      <x-admin.input name="password" label="New password" type="password" hint="Leave empty to keep the current password." autocomplete="new-password" />
      <x-admin.button>Save changes</x-admin.button>
    </form>
  </x-admin.card>
@stop
