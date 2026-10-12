@extends('layouts.master')
@section('customstyle')

@stop

@section('header')
@include('partial.header')
@stop
@section('content')

<?php 
//$category_dp = App\Model\Category::whereRaw("status = 'Yes' AND pid = 0 ")->orderByRaw('sort_order')->get()->toArray();
//$product_total = App\Model\Products::whereRaw("status='Yes'")->count();
//$products_dp = App\Model\Products::whereRaw("status = 'Yes'")->orderByRaw('sort_order')->get()->toArray();

//$db_category = App\Model\Category::whereRaw("status = 'Yes' AND pid = 0")->orderByRaw('sort_order')->get()->toArray();


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
    
    <div class="sidebar-page-container">
    	<div class="auto-container">
        	<div class="row clearfix">
				
				<!-- Content Side -->
                <div class="content-side col-12 right-sidebar">
					
					<div class="shops-outer">
						<div class="row clearfix">
							
                            <?php 
							if(count($products_dp)>0){
							?>
							
                            <?php foreach ($products_dp as $row) {?>
							<div class="shop-item col-lg-3 col-md-4 col-sm-6">
								<div class="inner-box">
									<div class="image">
										<a href="<?=url('/').'/'.$row['slug']?>.html"><img src="<?= url('/') . '/public/upload/products/' . $row['image'] ?>" alt="" /></a>
									</div>
									<div class="lower-content">
										<h6><a href="<?=url('/').'/'.$row['slug']?>.html"><?=$row['name']?></a></h6>
									</div>
								</div>
							</div>
							 <?php } ?>
                             
                             
                             <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="ttm-pagination">
                            <?php echo $products_dp->render(); ?>
                        </div>
                    </div>
                </div>
                            <?php } else {?>
                            <div class="col-md-12">
                            <div class="alert alert-info text-center">No Result Found</div>
                            </div>
                            <?php } ?>
							
							
						</div>
						
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

