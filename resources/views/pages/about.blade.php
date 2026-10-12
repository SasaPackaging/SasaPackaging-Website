@extends('layouts.master')

@section('customstyle')



@stop



@section('header')

@include('partial.header')

@stop

@section('content')


 <?php 
 $data_choice = App\Model\Cms::whereRaw(" status = 'Yes' AND id=9 ")->get()->toArray();
 $settings = App\Model\Setting::select('key_name','key_value')->get()->toArray();
$array_settings = array();
foreach ($settings as $k=>$setting){
	$array_settings[$setting['key_name']] = $setting['key_value'];
}
 ?> 
 
 
 <section class="page-title" style="background-image: url(<?=$cms_dp['banner']?>); background-size: cover;
	background-repeat: no-repeat; background-position: center;">
        <div class="auto-container">
			<!-- Icons Box -->
			<div class="icons-box parallax-scene-1">
				<div class="icon-one" data-depth="0.10"></div>
				<div class="icon-two" data-depth="0.30">
					<img src="<?=url('/')?>/public/assets/main/images/icons/vector-9.png" alt="" />
				</div>
				<div class="icon-three" data-depth="0.30">
					<img src="<?=url('/')?>/public/assets/main/images/icons/vector-34.png" alt="" />
				</div>
				<div class="icon-four" data-depth="0.10"></div>
			</div>
			<h2><?=$cms_dp['banner_heading']?></h2>
			<ul class="bread-crumb clearfix">
				<li><a href="<?php echo URL::to('/'); ?>">Home</a></li>
				<li><?=$cms_dp['name']?></li>
			</ul>
        </div>
    </section>      
        
<section class="business-section">
			<div class="small-circle-layer"></div>
			<div class="vector-layer-one" style="background-image: url(<?=url('/')?>/public/assets/main/images/icons/vector-1.png)"></div>
			<div class="vector-layer-two" style="background-image: url(<?=url('/')?>/public/assets/main/images/icons/vector-2.png)"></div>
			<div class="vector-layer-three" style="background-image: url(<?=url('/')?>/public/assets/main/images/icons/vector-5.png)"></div>
			<div class="auto-container">
				<div class="row clearfix">
					<?=$cms_dp['full_contents']?>
				</div>
			</div>
		</section>
     

	    
    
  @section('footer')

@include('partial.footer')

@stop   



@stop



@section('customscript')



@stop



