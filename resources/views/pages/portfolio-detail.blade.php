@extends('layouts.master')

@section('customstyle')



@stop



@section('header')

@include('partial.header')

@stop

@section('content')

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
    
    <div class="project-detail-section">
    	<div class="auto-container">
        	<div class="inner-container">
				<div class="image">
					<img src="<?=$cms_dp['image']?>" alt="" />
				</div>
				<div class="row clearfix mt-3">
					<div class="col-12">
						
						 <?=$cms_dp['full_contents']?>
					</div>

				</div>
			</div>
		</div>
	</div>

  @section('footer')

@include('partial.footer')

@stop   



@stop



@section('customscript')



@stop



