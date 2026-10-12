<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Hash;
use App\Model\Setting;
use Session;
use Redirect;
use Response;
class SettingsController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Site Settings Controller
      |--------------------------------------------------------------------------

     */

    /*
      |--------------------------------------------------------------------------
      | Create a new controller instance.
      |--------------------------------------------------------------------------
      @return void
     */

    public function __construct() {
        $this->middleware('AdminAuth');
        
    }

    /*
      |--------------------------------------------------------------------------
      | View Site Settings.
      |--------------------------------------------------------------------------
     */

    public function index() {
        return view('admin.settings.index', ['title' => 'Settings']);
    }

    /*
      |--------------------------------------------------------------------------
      | Post Site Settings.
      |--------------------------------------------------------------------------
     */

    public function update_setting(Request $request) {
        if ($request->_token) {
			$array_settings = $request->setting;
			foreach ($array_settings as $key => $val) {
				$model = Setting::firstOrNew(array('key_name' => $key));
				$model->key_value = $val;
				$model->save();
			}
			
			return Response::json(array('error_code' => 0, 'status' => 'success', 'message' => 'Data has been updated.'));
  
        }
    }

}
