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

    

    

    <section class="business-section">

			<div class="small-circle-layer"></div>

			<div class="vector-layer-one" style="background-image: url(<?=url('/')?>/public/assets/main/images/icons/vector-1.png)"></div>

			<div class="vector-layer-two" style="background-image: url(<?=url('/')?>/public/assets/main/images/icons/vector-2.png)"></div>

			<div class="vector-layer-three" style="background-image: url(<?=url('/')?>/public/assets/main/images/icons/vector-5.png)"></div>

			<div class="auto-container">

				<div class="row clearfix">
					<?php 
					if($cms_dp['id']==20){
					?>
                    <div class="content-column col-lg-12 col-md-12 col-sm-12 cont-colum-1">

						<div class="inner-column">

							<!-- Sec Title -->

							<div class="sec-title">

								<div class="title"><?=$cms_dp['tag_line']?></div>

								<h2> <?=$cms_dp['heading']?></h2>

								<div class="text">

									 <?=$cms_dp['full_contents']?>						

								</div>

							</div>



						</div>

					</div>
                    <?php }else{  ?>
					<!-- Images Column -->

					<div class="images-column col-lg-6 col-md-12 col-sm-12">

						<div class="inner-column">

							<div class="circle-layer"

								style="background-image: url(<?=url('/')?>/public/assets/main/images/background/circles-layer.png)"></div>

							<div class="images-outer parallax-scene-1">

								<div class="image" data-depth="0.20">

									<img src="<?=$cms_dp['image']?>" alt="" />

								</div>

							</div>

						</div>

					</div>

					<!-- Content Column -->

					<div class="content-column col-lg-6 col-md-12 col-sm-12 cont-colum-1">

						<div class="inner-column">

							<!-- Sec Title -->

							<div class="sec-title">

								<div class="title"><?=$cms_dp['tag_line']?></div>

								<h2> <?=$cms_dp['heading']?></h2>

								<div class="text">

									 <?=$cms_dp['full_contents']?>						

								</div>

							</div>



						</div>

					</div>
                    <?php } ?>

				</div>

			</div>

		</section>

  @section('footer')

@include('partial.footer')
@stop   
@stop
@section('customscript')
@stop







