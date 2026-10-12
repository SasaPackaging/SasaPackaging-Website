<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use View;
use Hash;
use App\Model\Cms;
use App\Model\Common;
use Session;
use Redirect;
use Response;

class PagesController extends Controller {
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
		$result_dp = Cms::get()->toArray();
        return view('admin.cms.index', ['title' => 'Page','result_dp'=>$result_dp]);
    }

    /*
      |--------------------------------------------------------------------------
      | Page Add
      |--------------------------------------------------------------------------
     */

    public function create() {
       return view('admin.cms.add', ['title' => 'Add Page']);
    }

    /*
      |--------------------------------------------------------------------------
      | Admin Login Auth
      |--------------------------------------------------------------------------
     */

    public function create_save(Request $request) {
        if ($request->_token) {
			
			$image_name = '';
			$file = $request->image;
			if($file){
			$ext = $file->getClientOriginalExtension();
			$file = $request->image->getClientOriginalName();
			$image_name = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/cms/';
			$request->image->move($path_uploads, $image_name);
			}
			
			$image_name_2 = '';
			$file_2 = $request->banner;
			if($file_2){
			$ext = $file_2->getClientOriginalExtension();
			$file_2 = $request->banner->getClientOriginalName();
			$image_name_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_2)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/cms/';
			$request->banner->move($path_uploads, $image_name_2);
			}
			
			$model = new Cms();
			$model->p_id = $request->p_id;
            $model->name = $request->name;
			$model->slug = Common::slug($request->name);
			$model->heading = $request->heading;
			$model->tag_line = $request->tag_line;
			$model->image_title = $request->image_title;
			$model->sort_order = $request->sort_order;
			$model->meta_title = $request->meta_title;
			$model->meta_keyword = $request->meta_keyword;
			$model->meta_description = $request->meta_description;
			$model->image = $image_name;
			$model->banner = $image_name_2;
			$model->icon = $request->icon;
			$model->short_contents = $request->short_contents;
			$model->full_contents = $request->contents;
			
            $model->save();
			
			
			
			return Redirect::to('/admin/pages');
			
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

        Cms::whereRaw('md5(id) = ? ', array($id))->delete();
        return Redirect::to('/admin/pages');
    }

    /*
      |--------------------------------------------------------------------------
      | Edit Record
      |--------------------------------------------------------------------------
     */

    public function edit($id) {

        $data = Cms::whereRaw('md5(id) = ?  ', array($id))->get()->toArray();
        return view('admin.cms.edit', ['title' => 'Edit Page','data' => $data[0]]);
    }

    /*
      |--------------------------------------------------------------------------
      | Edit Record
      |--------------------------------------------------------------------------
     */

    public function edit_save(Request $request) {

         if ($request->_token) {
			
			$image_name = '';
			$file = $request->image;
			if($file){
			$ext = $file->getClientOriginalExtension();
			$file = $request->image->getClientOriginalName();
			$image_name = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/cms/';
			$request->image->move($path_uploads, $image_name);
			}
			
			$image_name_2 = '';
			$file_2 = $request->banner;
			if($file_2){
			$ext = $file_2->getClientOriginalExtension();
			$file_2 = $request->banner->getClientOriginalName();
			$image_name_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_2)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/cms/';
			$request->banner->move($path_uploads, $image_name_2);
			}
			
			
			
            $model = Cms::find($request->id);
			$model->p_id = $request->p_id;
            $model->name = $request->name;
			$model->slug = Common::slug($request->name);
			$model->heading = $request->heading;
			$model->tag_line = $request->tag_line;
			$model->image_title = $request->image_title;
			$model->sort_order = $request->sort_order;
			$model->meta_title = $request->meta_title;
			$model->meta_keyword = $request->meta_keyword;
			$model->meta_description = $request->meta_description;
			
			$model->icon = $request->icon;
			$model->short_contents = $request->short_contents;
			$model->full_contents = $request->contents;
			if($image_name!=""){
			$model->image = $image_name;
			}
			if($image_name_2!=""){
			$model->banner = $image_name_2;
			}
			
			
			
            $model->save();
			return Redirect::to('/admin/pages');
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
			 $model = Cms::find($request->id);
            if ($model->status == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\'pages/status\')" ><span  class="label label-default" data-title="Inctive"  ><i class="fa fa-ban"></i> Inctive</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\'pages/status\')" ><span  class="label label-success " data-title="Active"  ><i class="fa fa-check"></i> Active</span></a>';
            }
            $model->status = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	public function statusfooter(Request $request) {

        if ($request->_token) {
			 $model = Cms::find($request->id);
            if ($model->is_footer == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_footer(' . $request->id . ',\'pages/statusfooter\')" ><span  class="label label-default" data-title="No"  ><i class="fa fa-ban"></i> No</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_footer(' . $request->id . ',\'pages/statusfooter\')" ><span  class="label label-success " data-title="Yes"  ><i class="fa fa-check"></i> Yes</span></a>';
            }
            $model->is_footer = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	public function statusheader(Request $request) {

        if ($request->_token) {
			 $model = Cms::find($request->id);
            if ($model->is_header == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_header(' . $request->id . ',\'pages/statusheader\')" ><span  class="label label-default" data-title="No"  ><i class="fa fa-ban"></i> No</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_header(' . $request->id . ',\'pages/statusheader\')" ><span  class="label label-success " data-title="Yes"  ><i class="fa fa-check"></i> Yes</span></a>';
            }
            $model->is_header = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	public function statusquick(Request $request) {

        if ($request->_token) {
			 $model = Cms::find($request->id);
            if ($model->is_quick == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_quick(' . $request->id . ',\'pages/statusquick\')" ><span  class="label label-default" data-title="No"  ><i class="fa fa-ban"></i> No</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_quick(' . $request->id . ',\'pages/statusquick\')" ><span  class="label label-success " data-title="Yes"  ><i class="fa fa-check"></i> Yes</span></a>';
            }
            $model->is_quick = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }

}
