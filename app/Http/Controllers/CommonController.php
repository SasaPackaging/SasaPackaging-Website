<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Cms;
use App\Model\Setting;
use App\Model\Common;
use App\Model\Comments;
use App\Model\Quote;
use Session;
use Response;
use Mail;
use URL;

class CommonController extends Controller
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
	public function index() {}


	/*
      |--------------------------------------------------------------------------
      | Load ajax 
      |--------------------------------------------------------------------------
     */






	public function save_career(Request $request)
	{
		$data = array();

		$name_cv = '';
		$file = $request->cv;
		if ($file) {
			$ext = $file->getClientOriginalExtension();
			$file = $request->cv->getClientOriginalName();
			$name_cv = rand(11111, 99999) . '_' . str_replace(' ', '-', strtolower($file)); //uniqid() . '.' . $ext;
			$path_uploads = 'public/upload/cv/';
			$request->cv->move($path_uploads, $name_cv);
		}


		$subject = Setting::findByKey('SITE_NAME') . ' Career';
		$msg = '';
		$msg	= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<style type='text/css'>
.style1 {font-size: 24px}
</style></head>
<body style='margin:0px; padding:0px;'>
	<table width='100%' border='0' cellpadding='0' cellspacing='0'>
        <tr>
        	<td height='427' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:12px;'>
            	<table width='100%' border='0' cellpadding='0' cellspacing='10' bgcolor='#FFFFFF' align='center'>
					<tr>
						<td valign='top' style='padding:0 0 10px 0;'>
							<table width='100%' border='0' cellpadding='4' cellspacing='4' bgcolor='#ea0307' 
                            style=' border-bottom:1px solid #d1d1d1;' align='center'>
								<tr>
									<td valign='top' style='font-size:18px; font-weight:normal; color:#FFF;'>Career</td>
								</tr>
						  </table>						</td>
					</tr>
                  	
                  	<tr>
                   	  	<td valign='top' style='color:#414141;'><span style='font-weight:bold;'> Name : </span> " . $request->contact_full_name . " " . $request->contact_last_name . " </td>
                   	</tr>
					
					
					<tr>
                   	  	<td valign='top' style='color:#414141;'><span style='font-weight:bold;'> Email  : </span> " . $request->contact_email . "</td>
                   	</tr>
					
					<tr>
                   	  	<td valign='top' style='color:#414141;'><span style='font-weight:bold;'> Phone : </span> " . $request->contact_phone . "</td>
                   	</tr>
					
					<tr>
                   	  	<td valign='top' style='color:#414141;'><span style='font-weight:bold;'> linkedIn URL : </span> " . $request->contact_linked_url . "</td>
                   	</tr>
					
					<tr>
                   	  	<td valign='top' style='color:#414141;'><span style='font-weight:bold;'>Position : </span> " . $request->contact_position . "</td>
                   	</tr>
					
					<tr>
                   	  	<td valign='top' style='color:#414141;'><span style='font-weight:bold;'>CV : </span> <a href='" . url('/') . '/public/upload/cv/' . $name_cv . "' download>Download</a> </td>
                   	</tr>
                    
			  </table>
          </td>
  </tr>
</table>
</body>
</html>";



		Common::overrideMailerConfig();

		$data_email = array('name' => '', 'msg' => $msg);
		$user_data = array('to' => Setting::findByKey('CONTACT_EMAIL'), 'name' => Setting::findByKey('SITE_NAME'), 'subject' => $subject, 'siteName' => $request->contact_full_name, 'businessEmail' => $request->contact_email);

		Mail::send('emails.user', $data_email, function ($message) use ($user_data) {
			$message->from($user_data['businessEmail'], $user_data['siteName']);
			$message->to($user_data['to'], $user_data['name'])->subject($user_data['subject']);
		});


		return Response::json(array('error_code' => '',  'status' => 'success', 'message' => 'Your request has been send successfully.'));
	}
}
