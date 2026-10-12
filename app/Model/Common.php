<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use PDO;
use Illuminate\Support\Str;
use Datetime;
use Config;
class Common extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    
    
	
	public static function timeAgo($datetime, $full = false)
		{
			$now = new DateTime;
			$ago = new DateTime($datetime);
			$diff = $now->diff($ago);
			$diff->w = floor($diff->d / 7);
			$diff->d -= $diff->w * 7;
		
			$string = array(
				'y' => 'Year',
				'm' => 'Month',
				'w' => 'Week',
				'd' => 'Day',
				'h' => 'Hour',
				'i' => 'Minute',
				's' => 'Second',
			);
		
			foreach ($string as $k => &$v) 
			{
				if ($diff->$k) 
				{
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? ' ' : '');
				} 
				else 
				{
					unset($string[$k]);
				}
			}
		
			if ( ! $full)
			{
				$string = array_slice($string, 0, 1);   
			} 
		
			return $string ? implode(', ', $string) . '' : 'just now';
		
		}
	
	/*
      |--------------------------------------------------------------------------
      | function to find city 
      |--------------------------------------------------------------------------
     */
    public static function slug($string) {
		$string = str_replace(" & ", " and ", strtolower($string));
		$string = str_replace(" ", "-", $string);
   		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
		$string = strtolower(trim($string, '-'));
		$string = rtrim($string, '-');
        $string = ltrim($string, '-');
		return preg_replace('/-+/', '-', $string);
	}
	/*
      |--------------------------------------------------------------------------
      | function to find lat long 
      |--------------------------------------------------------------------------
     */
    public static function address_to_latlong($address) {
		$prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
		return array('lat'=>$latitude,'lng'=>$longitude);
	}
	
	/*
      |--------------------------------------------------------------------------
      | function to phoneformat 
      |--------------------------------------------------------------------------
     */
    public static function phoneformat($phone) {
		$phone = str_replace("(", "", $phone);
		$phone = str_replace(")", "", $phone);
   		$phone = str_replace(" ", "", $phone);
		return '+92'.$phone;
	}
	
	/*
      |--------------------------------------------------------------------------
      | function to priceFormat 
      |--------------------------------------------------------------------------
     */
    public static function priceFormat($price) {
		
		return 'Rs.'.number_format($price);
	}
	
	/*
      |--------------------------------------------------------------------------
      | function to priceFormat 
      |--------------------------------------------------------------------------
     */
    public static function formatPrice($price) {
		
		return number_format($price);
	}
	/*
      |--------------------------------------------------------------------------
      | function to set date formate 
      |--------------------------------------------------------------------------
     */
    public static function addDate($date) {
		 $date = str_replace(' - ',' ',$date);
		return date('Y-m-d H:i:s',strtotime($date));
	}
	
	public static function dateFormat($date) {
		 $date = str_replace(' - ',' ',$date);
		return date('d M h:iA',strtotime($date));
	}
	
	
	/*
      |--------------------------------------------------------------------------
      | pagination
      |--------------------------------------------------------------------------
     */
	
	public static function getLinks($count,$cur_page,$per_page)
	{
		$previous_btn = true;
		$next_btn = true;
		$first_btn = true;
		$last_btn = true;
		$no_of_paginations = ceil($count / $per_page);
		
		/* ---------------Calculating the starting and endign values for the loop----------------------------------- */
	if ($cur_page >= 7) {
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
			$end_loop = $cur_page + 3;
		else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
			$start_loop = $no_of_paginations - 6;
			$end_loop = $no_of_paginations;
		} else {
			$end_loop = $no_of_paginations;
		}
	} else {
		$start_loop = 1;
		if ($no_of_paginations > 7)
			$end_loop = 7;
		else
			$end_loop = $no_of_paginations;
	}
	$links = '';
	/* ----------------------------------------------------------------------------------------------------------- */
	$links .= '<nav class="text-center">
			<ul class="pagination ">';
	
	// FOR ENABLING THE PREVIOUS BUTTON
	if ($previous_btn && $cur_page > 1) {
		$pre = $cur_page - 1;
		$links .= "<li p='$pre' class='active'><a onclick='loadData(".$pre.")' href='javascript:void(0)'>Previous</a></li>";
	} else if ($previous_btn) {
		$links .= "<li class='inactive'><a href='javascript:void(0)'>Previous</a></li>";
	}
	for ($i = $start_loop; $i <= $end_loop; $i++) {
	
		if ($cur_page == $i)
			$links .= "<li p='$i' style='' class='active'><a href='javascript:void(0)'>{$i}</a></li>";
		else
			$links .= "<li p='$i' class=''><a onclick='loadData(".$i.")' href='javascript:void(0)'>{$i}</a></li>";
	}
	
	// TO ENABLE THE NEXT BUTTON
	if ($next_btn && $cur_page < $no_of_paginations) {
		$nex = $cur_page + 1;
		$links .= "<li p='$nex' class='active'><a onclick='loadData(".$nex.")' href='javascript:void(0)'>Next</a></li>";
	} else if ($next_btn) {
		$links .= "<li class='inactive'><a href='javascript:void(0)'>Next</a></li>";
	}
	
	
	$links .= "</ul></nav>";
	return  $links;
		
		
	}
	
	
	public static function getYoutubeIdFromUrl($url) {
			$parts = parse_url($url);
			if(isset($parts['query'])){
				parse_str($parts['query'], $qs);
				if(isset($qs['v'])){
					return $qs['v'];
				}else if(isset($qs['vi'])){
					return $qs['vi'];
				}
			}
			if(isset($parts['path'])){
				$path = explode('/', trim($parts['path'], '/'));
				return $path[count($path)-1];
			}
			return '';
	}
	
	
	
	/*
      |--------------------------------------------------------------------------
      | function to override Mailer Config 
      |--------------------------------------------------------------------------
     */
    public static function overrideMailerConfig() {
		
	   Config::set('mail.driver',Setting::findByKey('SMTP_DRIVER'));
       Config::set('mail.host',Setting::findByKey('SMTP_HOST'));
       Config::set('mail.port',Setting::findByKey('SMTP_PORT'));
       Config::set('mail.username',Setting::findByKey('SMTP_USERNAME'));
       Config::set('mail.password',Setting::findByKey('SMTP_PASSWORD'));
       Config::set('mail.sendmail','/usr/sbin/sendmail -bs');
	}
	
	public static function image_validation_rule(){

		$rules = array(

		  'image' => 'mimes:jpeg,jpg,png,gif'

		);

		

		return $rules;

	}

}
