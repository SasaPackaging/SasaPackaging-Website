@extends('layouts.master')
@section('customstyle')
<style>
.field_error { border:1px solid #C00 !important;}
</style>
@stop
@section('header')
@include('partial.header')
@stop
@section('content')
<?php 
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
    
    
    <section class="contact-info-section">
		<div class="auto-container">
			<div class="row clearfix">
				
				<!-- Info Column -->
				<div class="info-column col-xl-4 col-lg-4 col-md-6 col-sm-12">
					<div class="inner-column d-flex align-items-center inner-mor">
						<div class="icon flaticon-email-1"></div>
						<div class="content">
							<strong>Mail address</strong>
							<a href="mailto:<?=$array_settings['CONTACT_EMAIL']?>"><?=$array_settings['CONTACT_EMAIL']?></a>
						</div>
					</div>
				</div>
				
				<!-- Info Column -->
				<div class="info-column col-xl-4 col-lg-4 col-md-6 col-sm-12">
					<div class="inner-column d-flex align-items-center">
						<div class="icon flaticon-map"></div>
						<div class="content">
							<strong>Office Location</strong>
							<div class="text"><?=nl2br($array_settings['ADDRESS'])?></div>
						</div>
					</div>
				</div>
				
				<!-- Info Column -->
				<div class="info-column col-xl-4 col-lg-4 col-md-6 col-sm-12">
					<div class="inner-column d-flex align-items-center inner-mor2">
						<div class="icon flaticon-telephone"></div>
						<div class="content">
							<strong>Phone Number</strong>
							<a href="tel:<?=$array_settings['PHONE']?>"><?=$array_settings['PHONE']?>
								</a>
							<a href="tel:<?=$array_settings['POBOX']?>"><?=$array_settings['POBOX']?></a>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
    
    <section class="contact-section">
		<div class="auto-container">
			<div class="row clearfix">
				<!-- Image Column -->
				<div class="image-column col-lg-5 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="image">
							<img src="<?=$cms_dp['image']?>" alt="" />
						</div>
						<div class="color-layer"></div>
					</div>
				</div>
				<!-- Form Column -->
				<div class="form-column col-lg-7 col-md-12 col-sm-12">
					<div class="inner-column">
						<div class="foorm-head">
							<h2><?=$cms_dp['heading']?></h2>
						</div>
						<!-- Contact Form -->
						<div class="contact-form">
							<form method="post" action="" id="contact-form" >
                            <input type="hidden" name="_token" value="<?=csrf_token()?>">
								<div class="row clearfix">
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Full Name</label>
										<input type="text" name="contact_full_name" id="contact_full_name" placeholder="Enter Your name" >
									</div>
									
									

									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Email</label>
										<input type="text" id="contact_email" name="contact_email" placeholder="Enter Your Email" >
									</div>
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Phone Number</label>
										<input type="text" id="contact_phone" name="contact_phone" placeholder="Enter Your Phone Number" >
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Subject</label>
										<input type="text" id="contact_subject" name="contact_subject" placeholder="Sunject" >
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 form-group">
										<textarea class=""  name="contact_message" id="contact_message" placeholder="Message"></textarea>
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 form-group">
										<div class="buttons-box">
											<button type="button" class="theme-btn btn-style-one clearfix" id="submit_btn" onclick="contact_us_new()">
												<span class="btn-wrap">
													<span class="text-one">Submit</span>
													<span class="text-two">Submit</span>
												</span>
												<span class="icon flaticon-right-arrow"></span>
											</button>
                                            <img id="id_loading_process_contact" style="display:none;" src="<?=url('/')?>/public/assets/images/loading_small.gif">
										</div>
									</div>
									
								</div>
							</form>
						</div>
						<!-- End Contact Form -->
						
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
<script type="text/javascript">
 function contact_us_new() {
	 var flg = 0;
		
	if ($.trim($("#contact_full_name").val()) == "") {
        $("#contact_full_name").addClass('field_error');
        if (flg == 0) {
            $("#contact_full_name").focus();
             Toast.error('Please Enter Name');
            $('.alert-danger').show();
            flg = flg + 1;
        }
    }
    else {
        $("#contact_full_name").removeClass('field_error');
    }
	
	
	filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; ///^.+@.+\..{2,15}$/;
    if (!(filter.test($.trim($("#contact_email").val())))) {
        $("#contact_email").addClass('field_error');
        if (flg == 0) {
            $("#contact_email").focus();
            Toast.error('Invalid Email Address');
            $('.alert-danger').show();
            flg = flg + 1;
        }
    }
    else {
        $("#contact_email").removeClass('field_error');
    }
	
	
	
	if ($.trim($("#contact_phone").val()) == "") {
        $("#contact_phone").addClass('field_error');
        if (flg == 0) {
            $("#contact_phone").focus();
             Toast.error('Please Enter Phone');
            $('.alert-danger').show();
            flg = flg + 1;
        }
    }
    else {
        $("#contact_phone").removeClass('field_error');
    }
	
	if ($.trim($("#contact_subject").val()) == "") {
        $("#contact_subject").addClass('field_error');
        if (flg == 0) {
            $("#contact_subject").focus();
             Toast.error('Please Enter Subject');
            $('.alert-danger').show();
            flg = flg + 1;
        }
    }
    else {
        $("#contact_subject").removeClass('field_error');
    }
	
	
	if ($.trim($("#contact_message").val()) == "") {
        $("#contact_message").addClass('field_error');
        if (flg == 0) {
            $("#contact_message").focus();
             Toast.error('Please Enter Message');
            $('.alert-danger').show();
            flg = flg + 1;
        }
    }
    else {
        $("#contact_message").removeClass('field_error');
    }
	
	if(flg==0){
		$('.alert').hide();
		$('#submit_btn').hide();
        $('#id_loading_process_contact').show();
		
		$.post('<?=url('/')?>/common/contact_process', $('#contact-form').serialize(), function (data) {
            var obj = eval(data);
			if (obj.status == 'success') {
					$('#id_loading_process_contact').hide();
					$('#submit_btn').show();
					Toast.success(obj.message);
					$('.alert-success').show();
					$('#contact-form')[0].reset();
			}else {
				    $('#id_loading_process_contact').hide();
					$('#submit_btn').show();
					Toast.error(obj.message);
			}
        }, "json");
	}
}
 </script>
@stop
