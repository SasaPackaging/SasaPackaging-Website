<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;



use View;

use Hash;

use App\Model\Category;

use App\Model\Common;

use Session;

use Redirect;

use Response;

use URL;

class CategoryController extends Controller {

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

		$result_dp = Category::get()->toArray();

        return view('admin.category.index', ['title' => 'Category','result_dp'=>$result_dp]);

    }



    /*

      |--------------------------------------------------------------------------

      | Page Add

      |--------------------------------------------------------------------------

     */



    public function create() {

		

       return view('admin.category.add', ['title' => 'Add Category']);

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

			$path_uploads = 'public/upload/category/';

			$request->image->move($path_uploads, $image_name);

			}

			

			$image_name_2 = '';

			$file_2 = $request->banner;

			if($file_2){

			$ext = $file_2->getClientOriginalExtension();

			$file_2 = $request->banner->getClientOriginalName();

			$image_name_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_2)); //uniqid() . '.' . $ext;

			$path_uploads = 'public/upload/category/';

			$request->banner->move($path_uploads, $image_name_2);

			}

			

            $model = new Category();

			$model->pid = $request->pid;

            $model->name = $request->name;

			$model->heading = $request->heading;

			$model->slug = Common::slug($request->name);

			$model->sort_order = $request->sort_order;

			$model->status = 'Yes';

			$model->image = $image_name;

			$model->banner = $image_name_2;

			$model->detail = $request->detail;

			$model->FullContents = $request->contents;

			$model->save();

           // return Response::json(array('error_code' => 0, 'status' => 'success', 'message' => ''));

		   return Redirect::to('/admin/category');

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



        Category::whereRaw('md5(id) = ? ', array($id))->delete();

        return Redirect::to('/admin/category');

    }



    /*

      |--------------------------------------------------------------------------

      | Edit Record

      |--------------------------------------------------------------------------

     */



    public function edit($id) {



        $data = Category::whereRaw('md5(id) = ?  ', array($id))->get()->toArray();

        return view('admin.category.edit', ['title' => 'Edit Category','data' => $data[0]]);

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

			$image_name = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file));// uniqid() . '.' . $ext;

			$path_uploads = 'public/upload/category/';

			$request->image->move($path_uploads, $image_name);

			}

			

			$image_name_2 = '';

			$file_2 = $request->banner;

			if($file_2){

			$ext = $file_2->getClientOriginalExtension();

			$file_2 = $request->banner->getClientOriginalName();

			$image_name_2 = rand(11111,99999).'_'.str_replace(' ','-',strtolower($file_2)); //uniqid() . '.' . $ext;

			$path_uploads = 'public/upload/category/';

			$request->banner->move($path_uploads, $image_name_2);

			}

			

			

			$model = Category::find($request->id);			

            $model->pid = $request->pid;

			$model->name = $request->name;

			$model->heading = $request->heading;

			$model->slug = Common::slug($request->name);

			$model->sort_order = $request->sort_order;

			$model->detail = $request->detail;

			$model->FullContents = $request->contents;

			if($image_name!=""){

			$model->image = $image_name;

			}

			if($image_name_2!=""){

			$model->banner = $image_name_2;

			}

            $model->save();

			return Redirect::to('/admin/category');

           // return Response::json(array('error_code' => 0, 'status' => 'success', 'message' => ''));

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

			 $model = Category::find($request->id);

            if ($model->status == 'Yes') {

                $set_status = 'No';

                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\''.url('/').'/admin/category/status\')" ><span  class="label label-default" data-title="Inctive"  ><i class="fa fa-ban"></i> Inctive</span></a>';

            } else {

                $set_status = 'Yes';

                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status(' . $request->id . ',\''.url('/').'/admin/category/status\')" ><span  class="label label-success " data-title="Active"  ><i class="fa fa-check"></i> Active</span></a>';

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

			 $model = Category::find($request->id);

            if ($model->is_featured == 'Yes') {

                $set_status = 'No';

                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_header(' . $request->id . ',\'category/statusfeatured\')" ><span  class="label label-default" data-title="No"  ><i class="fa fa-ban"></i> No</span></a>';

            } else {

                $set_status = 'Yes';

                $html = '<a style="text-decoration:none;" href="javascript:void(0)" onclick="change_status_header(' . $request->id . ',\'category/statusfeatured\')" ><span  class="label label-success " data-title="Yes"  ><i class="fa fa-check"></i> Yes</span></a>';

            }

            $model->is_featured = $set_status;

            $model->save();

            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));

        } else {

            return Response::json(array('error_code' => 1, 'status' => 'fail', 'message' => 'Invalid Access'));

        }

    }

	

	

	/*

      |--------------------------------------------------------------------------

      | Change Record Status

      |--------------------------------------------------------------------------

     */



   public function subcategory(Request $request) {

			

			$dp_category = Category::whereRaw('status = ?  AND pid=? ', array('Yes',$request->pid))->orderBy('name')->get()->toArray();

        	$html = '';

			$html = '<select class="form-control"   name="cid" id="cid" >';

			$html .= '<option value="" >Select Sub Category</option>';

            foreach ($dp_category as $sub){

				$html .= '<option value="'.$sub['id'].'" >'.$sub['name'].'</option>';

			}

            $html .= '</select>';

            return Response::json(array('error_code' => 0, 'status' => 'success', 'html' => $html));

        

    }

	

	

	

   

	



}

