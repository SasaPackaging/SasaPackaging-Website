<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Hash;
use App\Model\Services;
use App\Model\Category;
use App\Model\Common;
use Session;
use Redirect;
use Response;

class ServicesController extends Controller {
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
		$result_dp = Services::get()->toArray();
        return view('admin.services.index', ['title' => 'Features','result_dp'=>$result_dp]);
    }

    /*
      |--------------------------------------------------------------------------
      | Service Add
      |--------------------------------------------------------------------------
     */

    public function create() {
		
       return view('admin.services.add', ['title' => 'Add Features']);
    }

    /*
      |--------------------------------------------------------------------------
      | Admin Login Auth
      |--------------------------------------------------------------------------
     */

    public function create_save(Request $request) {
        if ($request->_token) {
			
			$icon = '';
			$file_icon = $request->icon;
			if($file_icon){
			$ext = $file_icon->getClientOriginalExtension();
			$file_icon = $request->icon->getClientOriginalName();
			$file_icon = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_icon));// uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/services/';
			$request->icon->move($path_uploads, $file_icon);
			}
			
			$icon_2 = '';
			$file_icon_2 = $request->icon_2;
			if($file_icon_2){
			$ext = $file_icon_2->getClientOriginalExtension();
			$file_icon_2 = $request->icon_2->getClientOriginalName();
			$file_icon_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_icon_2));// uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/services/';
			$request->icon_2->move($path_uploads, $file_icon_2);
			}
			
			
			
			$model = new Services();
			$model->name = $request->name;
			$model->slug =Common::slug($request->name);
			$model->icon_class =  $file_icon ;
			$model->icon_class_2 =  $file_icon_2 ; 
			$model->sort_order = $request->sort_order;
			$model->image = '';
			$model->FullContents = $request->contents;
            $model->save();
			
			return Redirect::to('/admin/services');
			
            return Response::json(array('error_code' => 0, 'status' => 'success', 'message' => ''));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	/*
      |--------------------------------------------------------------------------
      | Delete Record
      |--------------------------------------------------------------------------
     */

    public function delete($id) {

        Services::whereRaw('md5(id) = ? ', array($id))->delete();
        return Redirect::to('/admin/services');
    }

    /*
      |--------------------------------------------------------------------------
      | Edit Record
      |--------------------------------------------------------------------------
     */

    public function edit($id) {

        $data = Services::whereRaw('md5(id) = ?  ', array($id))->get()->toArray();
		
        return view('admin.services.edit', ['title' => 'Edit Features','data' => $data[0]]);
    }

    /*
      |--------------------------------------------------------------------------
      | Edit Record
      |--------------------------------------------------------------------------
     */

    public function edit_save(Request $request) {

        if ($request->_token) {
			
			$icon = '';
			$file_icon = $request->icon;
			if($file_icon){
			$ext = $file_icon->getClientOriginalExtension();
			$file_icon = $request->icon->getClientOriginalName();
			$file_icon = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_icon));// uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/services/';
			$request->icon->move($path_uploads, $file_icon);
			}
			
			$icon_2 = '';
			$file_icon_2 = $request->icon_2;
			if($file_icon_2){
			$ext = $file_icon_2->getClientOriginalExtension();
			$file_icon_2 = $request->icon_2->getClientOriginalName();
			$file_icon_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_icon_2));// uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/services/';
			$request->icon_2->move($path_uploads, $file_icon_2);
			}
			
			
			
            $model = Services::find($request->id);
			$model->name = $request->name;
			$model->sort_order = $request->sort_order;
			$model->FullContents = $request->contents;
			if($file_icon!=""){
			$model->icon_class =  $file_icon;
			}
			
			if($file_icon_2!=""){
			$model->icon_class_2 =  $file_icon_2;
			}
			
			
			
            $model->save();
			return Redirect::to('/admin/services');
            return Response::json(array('error_code' => 0, 'status' => 'success', 'message' => ''));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	/*
      |--------------------------------------------------------------------------
      | Change Record Status
      |--------------------------------------------------------------------------
     */

    public function status(Request $request) {

        if ($request->_token) {
			 $model = Services::find($request->id);
            if ($model->status == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\'services/status\')" ><span  class="label label-default" data-title="Inctive"  ><i class="fa fa-ban"></i> Inctive</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\'services/status\')" ><span  class="label label-success " data-title="Active"  ><i class="fa fa-check"></i> Active</span></a>';
            }
            $model->status = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	
	
	
	
	

}
