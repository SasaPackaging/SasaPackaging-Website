<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Hash;
use App\Model\Quote;
use App\Model\Category;
use App\Model\Common;
use Session;
use Redirect;
use Response;

class QuotesController extends Controller {
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
		$result_dp = Quote::get()->toArray();
        return view('admin.quotes.index', ['title' => 'Quotes','result_dp'=>$result_dp]);
    }

   
   
    /*
      |--------------------------------------------------------------------------
      | Edit Record
      |--------------------------------------------------------------------------
     */

    public function detail($id) {

        $data = Quote::whereRaw('md5(id) = ?  ', array($id))->get()->toArray();
		
		    $model = Quote::find($data[0]['id']);			
            $model->status = 'No';
            $model->save();
		
        return view('admin.quotes.detail', ['title' => 'Detail','data' => $data[0]]);
    }
    
	
	/*
      |--------------------------------------------------------------------------
      | Delete Record
      |--------------------------------------------------------------------------
     */

    public function delete($id) {

        Quote::whereRaw('md5(id) = ? ', array($id))->delete();
        return Redirect::to('/admin/quotes');
    }

   
	
	
	
	
	

}
