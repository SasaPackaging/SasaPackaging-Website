@extends('layouts.master')

@section('customstyle')



@stop



@section('header')

@include('partial.header')

@stop

@section('content')

<?php 
$images_dp = App\Model\Projectimages::whereRaw('project_id  = ?  ', array($cms_dp['id']))->get()->toArray();

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
    
    
    
    
    <section class="shop-detail-section">
		<div class="auto-container">
			<!-- Upper Box -->
			<div class="upper-box">
				<div class="row clearfix">
					<!-- Gallery Column -->
					<div class="gallery-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner-column">
							<div class="carousel-outer">
                                <!-- Swiper -->
                                <div class="swiper-container content-carousel">
                                    <div class="swiper-wrapper">
                                        <!--<div class="swiper-slide">-->
                                        <!--    <figure class="image"><a href="<?=$cms_dp['image']?>" class="lightbox-image"><img src="<?=$cms_dp['image']?>" alt=""></a></figure>-->
                                        <!--</div>-->
                                        <?php if(count($images_dp)>0){?>
                                        <?php foreach ($images_dp as $row_img) {?>
                                        <div class="swiper-slide" 
                                            <figure class="image"><a href="<?= url('/') . '/public/upload/products/' . $row_img['image'] ?>" class="lightbox-image"><img src="<?= url('/') . '/public/upload/products/' . $row_img['image'] ?>" alt=""></a></figure>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
								<?php if(count($images_dp)>0){?>
                                <div class="swiper-container thumbs-carousel">
                                        <div class="swiper-wrapper">
                                         <?php foreach ($images_dp as $row_img) {?>
                                        <div class="swiper-slide">
                                            <figure class="thumb"><img src="<?= url('/') . '/public/upload/products/' . $row_img['image'] ?>" alt=""></figure>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
						</div>
					</div>
					<!-- Content Column -->
					<div class="content-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner-column">
							<h3><?=$cms_dp['heading']?></h3>
							
							<?=$cms_dp['full_contents']?>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</section>

	

  @section('footer')

@include('partial.footer')

@stop   



@stop



@section('customscript')



@stop



