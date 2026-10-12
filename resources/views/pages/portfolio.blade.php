@extends('layouts.master')

@section('customstyle')



@stop



@section('header')

@include('partial.header')

@stop

@section('content')

<?php 
$projects_dp = App\Model\Projects::whereRaw("status = 'Yes' ")->orderByRaw('sort_order')->get()->toArray();
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
    
    <div class="project-page-section">
    	<div class="auto-container">
			<div class="row">
				 <?php foreach ($projects_dp as $row) {?>
				<div class="project-block masonry-item col-lg-4 col-md-6 col-sm-12">
					<div class="inner-box">
						<div class="image">
							<a href="<?=url('/').'/'.$row['slug']?>.html"><img src="<?= url('/') . '/public/upload/projects/' . $row['image'] ?>" alt="" /></a>
							<div class="content">
								<h6><a href="<?=url('/').'/'.$row['slug']?>.html"><?=$row['name']?></a></h6>
								<a class="arrow flaticon-right-arrow" href="<?=url('/').'/'.$row['slug']?>.html"></a>
							</div>
						</div>
					</div>
				</div>
				  <?php } ?>
				

			</div>
		</div>
	</div>

  @section('footer')

@include('partial.footer')

@stop   



@stop



@section('customscript')



@stop



