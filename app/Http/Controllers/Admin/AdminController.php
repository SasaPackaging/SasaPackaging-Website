<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Model\Admins;
use Session;
use Hash;
use Redirect;
use Response;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Controller (sign in, dashboard, profile)
    |--------------------------------------------------------------------------
    |
    | Sign in is now a normal form post (no AJAX), so it does not depend on any
    | JavaScript or asset file. It is limited to 5 wrong tries per email + address
    | every 5 minutes, and the session id is renewed after a successful sign in.
    | Written to run on PHP 7.4.
    */

    public function __construct()
    {
        $this->middleware('AdminAuth', ['except' => ['index', 'login', 'forgotpassword']]);
    }

    /* ---------------------------------------------------------------- sign in page */

    public function index()
    {
        if (Session::get('admin_id')) {
            return redirect('/admin/dashboard');
        }
        return view('admin.login', ['title' => 'Login']);
    }

    /* ---------------------------------------------------------------- sign in */

    public function login(Request $request)
    {
        $request->validate(array(
            'email'    => 'required|email',
            'password' => 'required',
        ));

        $email = strtolower(trim((string) $request->email));
        $key   = 'admin-login|' . $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);
            return back()
                ->withInput($request->only('email'))
                ->withErrors(array('email' => 'Too many tries. Please wait ' . $minutes . ' minute(s) and try again.'));
        }

        $admin = Admins::whereRaw('email = ?', array($email))->first();

        if ($admin && $admin->status == 'Yes' && Hash::check($request->password, $admin->password)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();   // new session id after sign in
            Session::put('admin_id', $admin->id);
            Session::put('admin_name', $admin->name);
            return redirect('/admin/dashboard');
        }

        RateLimiter::hit($key, 300);
        return back()
            ->withInput($request->only('email'))
            ->withErrors(array('email' => 'Invalid login information.'));
    }

    /* ---------------------------------------------------------------- dashboard */

    public function dashboard()
    {
        return view('admin.home', ['title' => 'Dashboard']);
    }

    /* ---------------------------------------------------------------- profile */

    public function profile()
    {
        $data = Admins::whereRaw('id = ?  ', array(Session::get('admin_id')))->first()->toArray();
        return view('admin.profile', ['data' => $data, 'title' => 'My Profile']);
    }

    public function update_profile(Request $request)
    {
        $model = Admins::find(Session::get('admin_id'));
        if ($request->_token) {

            $model->name = $request->name;
            $model->email = $request->email;
            if ($request->password != "") {
                $model->password = Hash::make($request->password);
            }

            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'message' => 'Account Information has been updated.'));
        }
    }

    /* ---------------------------------------------------------------- sign out */

    public function logout(Request $request)
    {
        Admins::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }
}
