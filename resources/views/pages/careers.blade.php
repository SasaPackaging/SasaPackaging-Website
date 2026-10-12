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
								<form  action="<?= URL::to('save_career') ?>" name="form_register" id="form_register" method="post">
                                 <input type="hidden" name="_token" value="<?=csrf_token()?>">
									<div class="row clearfix">

										<div class="col-lg-6 col-md-6 col-sm-12 form-group">
											<label for="">First Name</label>
											<input type="text" name="contact_full_name" id="contact_full_name" placeholder="First Name"
												 >
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12 form-group">
											<label for="">Last Name</label>
											<input type="text" name="contact_last_name" id="contact_last_name" placeholder="Last Name"
												>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 form-group">
											<label for="">Phone Number</label>
											<input type="text" id="contact_phone" name="contact_phone" placeholder="Phone Number"
												>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 form-group">
											<label for="">Email</label>
											<input type="text" id="contact_email" name="contact_email" placeholder="Enter Your Email"
												>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12 form-group">
											<label for="">linkedIn URL</label>
											<input type="text" id="contact_linked_url" name="contact_linked_url" placeholder="Enter linkedIn URL"
												>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 form-group">
											<label for="">Position</label>
											<input type="text" id="contact_position" name="contact_position" placeholder="Enter Position"
												>
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 form-group">
											<label for="">Upload CV</label>
											<div>
											<input type="file" name="cv" id="cv" accept=".pdf,.docx" placeholder="Upload Your CV">

											</div>
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 form-group">
											<div class="buttons-box">
												<button class="theme-btn btn-style-one clearfix"  type="button" id="id_btn_submit">
													<span class="btn-wrap">
														<span class="text-one">Submit</span>
														<span class="text-two">Submit</span>
													</span>
													<span class="icon flaticon-right-arrow"></span>
												</button>
                                                <img id="id_loading_process" style="display:none;" src="<?=url('/')?>/public/assets/images/loading_small.gif">
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
			
		</div>
	</section>



  @section('footer')
@include('partial.footer')
@stop   
@stop
@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/jquery.form.js"></script>

<script>
$(document).ready(function() {
	var options = { 
        beforeSubmit:  showRequest_client,
		success:       showResponse_client,
		dataType: 'json' 
        }; 
 		$('body').delegate('#id_btn_submit','click', function(){
		if(valid_form()){
 			jQuery('#form_register').ajaxForm(options).submit();  	
		}
 	}); 

});
//............................................................
function showRequest_client(formData, jqForm, options) { 
	$('#id_btn_submit').hide();
	$('#id_loading_process').show();
	
}
function showResponse_client(response, statusText, xhr, $form)  {
	$('#id_btn_submit').show();
	$('#id_loading_process').hide();
	
	if(response.status=='success'){
		Toast.success(response.message);
		$("#id_alert_success").html(response.message).show();
		$('#form_register')[0].reset();
	}else {
		Toast.error(response.message);
		$("#id_alert").html(response.message).show();
		
	}
}



function valid_form(){
	
	var flg = 0;
	
	
	
	
	if ($.trim($("#contact_full_name").val()) == "") {
        $("#contact_full_name").addClass('field_error');
        if (flg == 0) {
            $("#contact_full_name").focus();
            Toast.error('Please enter your first name');
           
            flg = flg + 1;
        }
    }
    else {
        $("#contact_full_name").removeClass('field_error');

    }
	
	if ($.trim($("#contact_last_name").val()) == "") {
        $("#contact_last_name").addClass('field_error');
        if (flg == 0) {
            $("#contact_last_name").focus();
            Toast.error('Please enter your last name');
           
            flg = flg + 1;
        }
    }
    else {
        $("#contact_last_name").removeClass('field_error');

    }
	
	if ($.trim($("#contact_phone").val()) == "") {
        $("#contact_phone").addClass('field_error');
        if (flg == 0) {
            $("#contact_phone").focus();
            Toast.error('Please enter phone number');
           
            flg = flg + 1;
        }
    }
    else {
        $("#contact_phone").removeClass('field_error');

    }
	
	filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; ///^.+@.+\..{2,15}$/;
    if (!(filter.test($.trim($("#contact_email").val())))) {
        $("#contact_email").addClass('field_error');
        if (flg == 0) {
            $("#contact_email").focus();
            Toast.error('Please enter valid email');
            flg = flg + 1;
        }
    }
    else {
        $("#contact_email").removeClass('field_error');

    }
	
		
		
	if ($.trim($("#contact_linked_url").val()) == "") {
        $("#contact_linked_url").addClass('field_error');
        if (flg == 0) {
            $("#contact_linked_url").focus();
            Toast.error('Please enter  linkedIn URL ');
           
            flg = flg + 1;
        }
    }
    else {
        $("#contact_linked_url").removeClass('field_error');

    }	
	
	if ($.trim($("#contact_position").val()) == "") {
        $("#contact_position").addClass('field_error');
        if (flg == 0) {
            $("#contact_position").focus();
            Toast.error('Please enter  position ');
           
            flg = flg + 1;
        }
    }
    else {
        $("#contact_position").removeClass('field_error');

    }	
	
	
	
	
	if ($.trim($("#cv").val()) == "") {
        $("#cv").addClass('field_error');
        if (flg == 0) {
            $("#cv").focus();
            Toast.error('Please upload your CV');
            flg = flg + 1;
        }
    }
    else {
        $("#cv").removeClass('field_error');

    }
	
	
	


    if (flg == 0) {
        return true;
    }else {
		return false;
	}
	 
	
	}
</script>
@stop
