<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Hash;
use App\Model\Products;
use App\Model\Category;
use App\Model\Common;
use App\Model\Projectimages;
use App\Model\Projecttypes;
use Session;
use Redirect;
use Response;

class ProductsController extends Controller {
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
		$result_dp = Products::get()->toArray();
        return view('admin.products.index', ['title' => 'Products','result_dp'=>$result_dp]);
    }

    /*
      |--------------------------------------------------------------------------
      | Service Add
      |--------------------------------------------------------------------------
     */

    public function create() {
		
       return view('admin.products.add', ['title' => 'Add Products']);
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
			$image_name = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file));// uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/products/';
			$request->image->move($path_uploads, $image_name);
			}
			
			$image_name_2 = '';
			$file_2 = $request->banner;
			if($file_2){
			$ext = $file_2->getClientOriginalExtension();
			$file_2 = $request->banner->getClientOriginalName();
			$image_name_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_2)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/products/';
			$request->banner->move($path_uploads, $image_name_2);
			}
			
			
			
			$model = new Products();
			$model->category_id = $request->category_id;
			$model->banner_heading = $request->banner_heading;
			$model->name = $request->name;
			$model->slug =Common::slug($request->name);
			$model->sort_order = $request->sort_order;
			$model->image = $image_name;
			$model->banner = $image_name_2;
			
			$model->FullContents = $request->contents;
			$model->short_contents = $request->short_contents;
            $model->save();
			
			return Redirect::to('/admin/products');
			
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

        Products::whereRaw('md5(id) = ? ', array($id))->delete();
        return Redirect::to('/admin/products');
    }

    /*
      |--------------------------------------------------------------------------
      | Edit Record
      |--------------------------------------------------------------------------
     */

    public function edit($id) {

        $data = Products::whereRaw('md5(id) = ?  ', array($id))->get()->toArray();
		
        return view('admin.products.edit', ['title' => 'Edit Products','data' => $data[0]]);
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
			$image_name = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file));//uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/products/';
			$request->image->move($path_uploads, $image_name);
			}
			
			$image_name_2 = '';
			$file_2 = $request->banner;
			if($file_2){
			$ext = $file_2->getClientOriginalExtension();
			$file_2 = $request->banner->getClientOriginalName();
			$image_name_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_2)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/products/';
			$request->banner->move($path_uploads, $image_name_2);
			}
			
			
			
            $model = Products::find($request->id);
			$model->category_id = $request->category_id;
			$model->banner_heading = $request->banner_heading;
			$model->name = $request->name;
			$model->slug =Common::slug($request->name);
			
			$model->sort_order = $request->sort_order;
			$model->short_contents = $request->short_contents;
			$model->FullContents = $request->contents;
			if($image_name!=""){
			$model->image = $image_name;
			}
			
			if($image_name_2!=""){
			$model->banner = $image_name_2;
			}
			
            $model->save();
			return Redirect::to('/admin/products');
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
			 $model = Products::find($request->id);
            if ($model->status == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\'products/status\')" ><span  class="label label-default" data-title="Inctive"  ><i class="fa fa-ban"></i> Inctive</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\'products/status\')" ><span  class="label label-success " data-title="Active"  ><i class="fa fa-check"></i> Active</span></a>';
            }
            $model->status = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	public function statusfeatured(Request $request) {

        if ($request->_token) {
			 $model = Products::find($request->id);
            if ($model->is_featured == 'Yes') {
                $set_status = 'No';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_header(' . $request->id . ',\'products/statusfeatured\')" ><span  class="label label-default" data-title="No"  ><i class="fa fa-ban"></i> No</span></a>';
            } else {
                $set_status = 'Yes';
                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_header(' . $request->id . ',\'products/statusfeatured\')" ><span  class="label label-success " data-title="Yes"  ><i class="fa fa-check"></i> Yes</span></a>';
            }
            $model->is_featured = $set_status;
            $model->save();
            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));
        } else {
            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));
        }
    }
	
	
	//--------------------------------------------------------
	
	public function images($id) {
		$data = Products::whereRaw('md5(id) = ?  ', array($id))->first()->toArray();
		$result_dp = Projectimages::whereRaw('project_id  = ?  ', array($data['id']))->get()->toArray();
		
        return view('admin.productimages.index', ['title' => $data['name'].' Images','result_dp'=>$result_dp,'data'=>$data]);
    }
	
	/*
      |--------------------------------------------------------------------------
      | Image Add
      |--------------------------------------------------------------------------
     */

    public function createimage($id) {
		$data = Products::whereRaw('md5(id) = ?  ', array($id))->first()->toArray();
		
        return view('admin.productimages.add', ['title' => 'Add '.$data['name'].' Image','data'=>$data]);
    }
	
	/*
      |--------------------------------------------------------------------------
      | Edit Logo
      |--------------------------------------------------------------------------
     */

    public function createimage_save(Request $request) {
			if ($request->_token) {
			
			$image_name = '';
			$file = $request->image;
			if($file){
			$ext = $file->getClientOriginalExtension();
			$file = $request->image->getClientOriginalName();
			$image_name = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file));// uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/products/';
			$request->image->move($path_uploads, $image_name);
			}
			
			
			$model = new Projectimages();
            $model->image = $image_name;
			$model->project_id = $request->project_id;
			$model->featured = 'No';
			$model->sort_order = 1;
            $model->save();
			
			return Redirect::intended('admin/products/images/'.md5($request->project_id).'');
		}
        	
			
         
    }
	
	public function imagedelete($id,$sid) {

        Projectimages::whereRaw('md5(id) = ? ', array($sid))->delete();
        return Redirect::to('/admin/products/images/'.$id);
    }
	
	
}
