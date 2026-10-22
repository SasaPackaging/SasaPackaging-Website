<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveAdminUserRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Super admins add / edit / remove the people who can log in (and choose their role).
 */
class AdminUserController extends Controller
{
    use GuardsActions;

    public function index()
    {
        return view('admin.admin-users.index', [
            'admins' => Admin::orderBy('name')->get(),
            'roles'  => $this->roleOptions(),
        ]);
    }

    public function store(SaveAdminUserRequest $request)
    {
        return $this->attempt(function () use ($request) {
            Admin::create([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'role'     => $request->input('role'),
                'password' => Hash::make($request->input('password')),
            ]);

            return redirect()->route('admin.admin-users.index')->with('success', 'Admin added. They can log in with that email and password.');
        }, 'Could not add the admin. Please try again.');
    }

    public function edit($id)
    {
        return view('admin.admin-users.edit', [
            'admin' => Admin::findOrFail($id),
            'roles' => $this->roleOptions(),
        ]);
    }

    public function update(SaveAdminUserRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);

        // never leave the panel without a super admin
        if ($admin->isSuper() && $request->input('role') !== config('admin.super_role') && $this->superCount() <= 1) {
            return back()->withInput()->with('error', 'This is the only super admin, so its role cannot be changed.');
        }

        return $this->attempt(function () use ($admin, $request) {
            $admin->name  = $request->input('name');
            $admin->email = $request->input('email');
            $admin->role  = $request->input('role');

            if ($request->filled('password')) {
                $admin->password = Hash::make($request->input('password'));
            }

            $admin->save();

            return redirect()->route('admin.admin-users.index')->with('success', 'Changes saved.');
        }, 'Could not save the changes. Please try again.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account while logged in.');
        }

        if ($admin->isSuper() && $this->superCount() <= 1) {
            return back()->with('error', 'The only super admin cannot be deleted.');
        }

        return $this->attempt(function () use ($admin) {
            $admin->delete();

            return redirect()->route('admin.admin-users.index')->with('success', 'Admin removed.');
        }, 'Could not remove the admin. Please try again.', false);
    }

    protected function superCount()
    {
        return Admin::where('role', config('admin.super_role'))->count();
    }

    /** [role key => label] for the drop-down */
    protected function roleOptions()
    {
        return array_map(function ($role) {
            return $role['label'];
        }, config('admin.roles'));
    }
}
