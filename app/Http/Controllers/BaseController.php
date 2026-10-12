<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;

use App\Model\Cms;

use App\Model\Setting;

use App\Model\Banner;

use App\Model\Category;

use App\Model\Products;

use App\Model\Projects;

use Session;

use Request;

use Redirect;

class BaseController extends Controller
{



	/*

	|--------------------------------------------------------------------------

	| Welcome Controller

	|--------------------------------------------------------------------------

	|

	| This controller renders the "marketing page" for the application and

	| is configured to only allow guests. Like most of the other sample

	| controllers, you are free to modify or remove it as you desire.

	|

	*/



	/**

	 * Create a new controller instance.

	 *

	 * @return void

	 */

	public function __construct()

	{

		$this->middleware('guest');
	}



	/**

	 * Show the application welcome screen to the user.

	 *

	 * @return Response

	 */

	public function route($slug)

	{



		$parnt_id = '';

		$blog_id = '';

		$array_seg = Request::segments();

		$curr_page = Request::url('/');





		if (in_array('blog', $array_seg)) {

			$blog_id = '1';
		} else {

			$blog_id = '';
		}



		$slug = str_replace('.html', '', $slug);









		// Category ?

		if ($slug == 'contact-us') {

			$cms_dp = Cms::where('slug', '=', $slug)->first();

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] =  ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			return view('pages.contact', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} else if ($slug == 'careers') {

			$cms_dp = Cms::where('slug', '=', $slug)->first();

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] =  ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			return view('pages.careers', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} else if ($slug == 'get-quote') {

			$cms_dp = Cms::where('slug', '=', $slug)->first();

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] =  ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			return view('pages.quote', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} elseif ($slug == 'our-portfolio') {

			$cms_dp = Cms::where('slug', '=', $slug)->first();

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] =  ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];





			return view('pages.portfolio', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} elseif ($cms_dp = Projects::where('slug', '=', $slug)->first()) {



			$data['id'] = $cms_dp['id'];



			$data['slug'] = $cms_dp['slug'];

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['name'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['FullContents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/projects/' . $cms_dp['image'];

			$data['banner'] = ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/projects/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];



			return view('pages.portfolio-detail', ['title' => $cms_dp['name'], 'keywords' => $cms_dp['name'], 'description' => $cms_dp['name'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} elseif ($slug == 'products-and-services') {

			$cms_dp = Cms::where('slug', '=', $slug)->first();

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] =  ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];



			$products_dp = Products::whereRaw("status = 'Yes' ")->orderByRaw('sort_order')->paginate(16);



			return view('pages.products', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data, 'products_dp' => $products_dp]);
		} elseif ($cms_dp = Category::where('slug', '=', $slug)->first()) {



			$data['id'] = $cms_dp['id'];

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['name'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : $cms_dp['name'];

			$data['full_contents'] = $cms_dp['FullContents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/category/' . $cms_dp['image'];

			$data['banner'] =  ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/category/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];



			return view('pages.category-products', ['title' => $cms_dp['name'], 'keywords' => $cms_dp['name'], 'description' => $cms_dp['name'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} elseif ($cms_dp = Products::where('slug', '=', $slug)->first()) {



			$data['id'] = $cms_dp['id'];

			$data['category_id'] = $cms_dp['category_id'];

			$data['name'] = $cms_dp['name'];

			$data['product_code'] = $cms_dp['product_code'];

			$data['heading'] = $cms_dp['name'];

			$data['banner_heading'] = $cms_dp['banner_heading'];

			$data['full_contents'] = $cms_dp['FullContents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/products/' . $cms_dp['image'];

			$data['banner'] = ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/products/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];



			return view('pages.product-detail', ['title' => $cms_dp['name'], 'keywords' => $cms_dp['name'], 'description' => $cms_dp['name'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} elseif ($slug == 'search') {

			$keywords = Request::input('s');

			$data['name'] = 'Search';

			$data['heading'] = 'Search';

			$data['banner_heading'] = ($keywords == '') ? 'Search' : ' Search "' . $keywords . '"';

			$data['full_contents'] = '';

			$data['tag_line'] = '';

			$data['image'] = '';

			$data['banner'] =  url('/') . '/images/bnr2.png';

			$og_image = '';



			$result_cms =  Cms::orWhere('name', 'LIKE', "%{$keywords}%")->orWhere('heading', 'LIKE', "%{$keywords}%")->orWhere('full_contents', 'LIKE', "%{$keywords}%")->whereRaw("status = 'Yes' AND id!=1 ")->orderByRaw('name')->get()->toArray();



			return view('pages.search', ['title' => $data['name'], 'keywords' => $data['name'], 'description' => $data['name'], 'og_image' => $og_image, 'cms_dp' => $data, 'result_cms' => $result_cms]);
		} elseif ($slug == 'marketing-assets') {

			$cms_dp = Cms::where('slug', '=', $slug)->first();

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : '';

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] = ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];



			return view('pages.marketing-assets', ['title' => $data['name'], 'keywords' => $data['name'], 'description' => $data['name'], 'og_image' => $og_image, 'cms_dp' => $data]);
		} elseif ($cms_dp = Cms::where('slug', '=', $slug)->whereRaw('id!=12')->first()) {

			$data['id'] = $cms_dp['id'];

			$data['name'] = $cms_dp['name'];

			$data['heading'] = $cms_dp['heading'];

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : '';

			$data['full_contents'] = $cms_dp['full_contents'];

			$data['short_contents'] = $cms_dp['short_contents'];

			$data['tag_line'] = $cms_dp['tag_line'];

			$data['image'] = ($cms_dp['image'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['image'];

			$data['banner'] = ($cms_dp['banner'] == "") ? url('/') . '/images/bnr2.png' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			$og_image = ($cms_dp['banner'] == "") ? '' : url('/') . '/public/upload/cms/' . $cms_dp['banner'];

			if ($slug == 'about-us' || $slug == 'about-sasa-packaging') {

				return view('pages.about', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
			} else if ($slug == 'ceo-message') {

				return view('pages.ceo-message', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
			} else {

				return view('pages.cms', ['title' => $cms_dp['meta_title'], 'keywords' => $cms_dp['meta_keyword'], 'description' => $cms_dp['meta_description'], 'og_image' => $og_image, 'cms_dp' => $data]);
			}
		} else {

			$data['title'] = 'Error 404 ';



			$data['heading'] =   $data['name'] = 'Error 404';

			$data['banner_heading'] = ($cms_dp['image_title'] != "") ? $cms_dp['image_title'] : 'Error 404';

			$data['full_contents'] = 'Sorry Page Was Not Found';

			$data['tag_line'] = '';

			$data['image'] = '';
			$data['id'] = '0';

			$data['banner'] = url('/') . '/images/bnr2.png';

			$og_image = '';

			return view('pages.cms', ['title' => $data['title'], 'keywords' => $data['title'], 'description' => $data['title'], 'og_image' => $og_image, 'cms_dp' => $data]);
		}
	}
}
