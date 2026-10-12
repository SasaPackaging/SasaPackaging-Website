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

<section class="contact-section con-sec-2">
		<div class="auto-container">
			<div class="row clearfix justify-content-center">
				<!-- Form Column -->
				<div class="form-column col-lg-10 form-col-2">
					<div class="inner-column">
						<div class="get-q-head">
							<h2>Get A Quote</h2>
						</div>
						<div class="quote-head-2">
							<div class="quote-main-1">
								<div class="qu-1">
									<img src="<?=url('/')?>/public/assets/main/images/icons/delivery.png" alt="">
									<h4>FREE SHIPPING
									</h4>
								</div>
							</div>
							<div class="quote-main-2">
								<div class="qu-2">
									<img src="<?=url('/')?>/public/assets/main/images/icons/web-design.png" alt="">
									<h4>FREE DESIGN SUPPORT</h4>
								</div>
							</div>
						</div>
						
						<!-- Contact Form -->
						<div class="contact-form">
							<div class="head-st-1">
								<h4>
									Size
								</h4>
							</div>
							<form  action="<?= URL::to('send_quote') ?>" name="form_register" id="form_register" method="post">
                                 <input type="hidden" name="_token" value="<?=csrf_token()?>">
								<div class="row clearfix">
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Length:</label>
										<input type="text" name="q_length" id="q_length" placeholder="Enter Length" >
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Width:</label>
										<input type="text" name="q_width" id="q_width" placeholder="Enter Width" >
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Height:</label>
										<input type="text" name="q_height" id="q_height" placeholder="Enter Height" required="">
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Unit:</label>
										<select name="q_unit" id="q_unit" class="custom-select-box" >
											<option value="">Please Choose Unit</option>
											<option value="CM">CM</option>
											<option value="INCHES">INCHES</option>
											<option value="MM">MM</option>
											<option value="FEET">FEET</option>
										</select>
									</div>
									
								</div>
							

							<div class="head-st-1">
								<h4>
									Product Details
								</h4>
							</div>
							
								<div class="row clearfix">
									
									<div class="col-12 form-group">
										<label>Product Name:</label>
										<input type="text" name="q_produt_name" id="q_produt_name" placeholder="Enter Product Name" >
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Stock:</label>
										<select name="q_stock" id="q_stock" class="custom-select-box" >
											<option value="" >Select Stock</option>
											<option value="208 GSM Bleach card">208 GSM Bleach card</option>
											<option value="210 GSM Bleach card">210 GSM Bleach card</option>
											<option value="220 GSM Bleach card">220 GSM Bleach card</option>
											<option value="240 GSM Bleach card">240 GSM Bleach card</option>
											<option value="300 GSM Bleach card">300 GSM Bleach card</option>
											<option value="250 GSM Box Board">250 GSM Box Board</option>
											<option value="250 GSM Box Board">300 GSM Box Board</option>
											<option value="250 GSM Box Board">350 GSM Box Board</option>
											<option value="148 GSM Matt Paper">148 GSM Matt Paper</option>
											<option value="Kraft Paper 90 GSM">Kraft Paper 90 GSM</option>
											<option value="Kraft Paper 112 GSM">Kraft Paper 112 GSM</option>
											<option value="Kraft Paper 125 GSM">Kraft Paper 125 GSM</option>
											<option value="Rigid">Rigid</option>
										</select>
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Coating:</label>
										<select name="q_coating" id="q_coating" class="custom-select-box" >
											<!--<option value="None">None</option>-->
											<option value="Gloss Lamination">Shine Lamination</option>
											<option value="Matte Lamination">Matt Lamination</option>
											<option value="Gloss Varni">Shine Varnish</option>
											<option value="Matte Varnish">Matt Varnish</option>
										</select>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Add Ons:</label>
										<select name="q_add_ons" id="q_add_ons" class="custom-select-box" >
											<!--<option value="None">None</option>-->
											<option value="Gold Foiling">Gold Foiling</option>
											<option value="Silver Foiling">Silver Foiling</option>
											<option value="Embossing">Embossing</option>
											<option value="Spot UV">Spot UV</option>
											<option value="window Patching">Window Patching</option>
										</select>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Colours:</label>
										<select name="q_colours" id="q_colours" class="custom-select-box" >
											<option value="1 Colour">1 Colour</option>
											<option value="2 Colours">2 Colours</option>
											<option value="3 Colours">3 Colours</option>
											<option value="4 Colours">4 Colours</option>
											<option value="5 Colours">5 Colours</option>
											<option value="3 Colours / 1 Special Colour">3 Colours / 1 Special Colour</option>
											<option value="4 Colours / 1 Special Colour">4 Colours / 1 Special Colour </option>
										</select>
									</div>
									<div class="col-12 form-group">
										<label>Packing:</label>
										<select name="q_colours" id="q_colours" class="custom-select-box" >
											<option value="Bags">Bags</option>
											<option value="Carton">Carton</option>
										</select>
									</div>
								</div>
							

							<div class="head-st-1">
								<h4>
									Specify Quantity
								</h4>
							</div>
							
								<div class="row clearfix">
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Quantity 1:</label>
										<input type="text" name="q_quantity_1" id="q_quantity_1" placeholder="Quantity" >
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label>Quantity 2:</label>
										<input type="text" name="q_quantity_2" id="q_quantity_2"  placeholder="Quantity" >
									</div>
									
								</div>
							

							<div class="head-st-1">
								<h4>
									Upload File
								</h4>
							</div>
							
								<div class="row clearfix">
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<input type="file" name="q_file" id="q_file" accept=".jpg, .png, .gif, .docx, .xlsx, .pdf, .ai, .cdr"  >
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<p>
											Accepted file types: jpg, png, gif, docx, xlsx, pdf, ai, cdr, Max. file size: 15 MB.
										</p>
									</div>
									
								</div>
							

							<div class="head-st-1">
								<h4>
									Contact Information
								</h4>
							</div>
							
								<div class="row clearfix">
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Full Name:</label>
										<input type="text" name="q_full_name" id="q_full_name" placeholder="Enter Your Name" >
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Phone Number:</label>
										<input type="text" name="q_phone" id="q_phone" placeholder="Enter Your Phone Number" >
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Email:</label>
										<input type="text" name="q_email" id="q_email" placeholder="Enter Your Email" >
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 form-group">
										<label for="">Company Name:</label>
										<input type="text" name="q_company" id="q_company" placeholder="Enter Your Company Name" >
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 form-group">
										<textarea class="" name="q_message" id="q_message" placeholder="Message"></textarea>
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 form-group">
										<div class="buttons-box">
											<button class="theme-btn btn-style-one clearfix" type="button" id="id_btn_submit">
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
	
	
	
	
	if ($.trim($("#q_length").val()) == "") {
        $("#q_length").addClass('field_error');
        if (flg == 0) {
            $("#q_length").focus();
            Toast.error('Please enter length');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_length").removeClass('field_error');

    }
	
	if ($.trim($("#q_width").val()) == "") {
        $("#q_width").addClass('field_error');
        if (flg == 0) {
            $("#q_width").focus();
            Toast.error('Please enter width');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_width").removeClass('field_error');

    }
	
	if ($.trim($("#q_height").val()) == "") {
        $("#q_height").addClass('field_error');
        if (flg == 0) {
            $("#q_height").focus();
            Toast.error('Please enter height');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_height").removeClass('field_error');

    }
	
	
	if ($.trim($("#q_unit").val()) == "") {
        $("#q_unit-button").addClass('field_error');
        if (flg == 0) {
            $("#q_unit").focus();
            Toast.error('Please enter unit');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_unit-button").removeClass('field_error');

    }
	
	if ($.trim($("#q_produt_name").val()) == "") {
        $("#q_produt_name").addClass('field_error');
        if (flg == 0) {
            $("#q_produt_name").focus();
            Toast.error('Please enter product name');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_produt_name").removeClass('field_error');

    }
	
	if ($.trim($("#q_stock").val()) == "") {
        $("#q_stock-button").addClass('field_error');
        if (flg == 0) {
            $("#q_stock").focus();
            Toast.error('Please select stock');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_stock-button").removeClass('field_error');

    }
	
	if ($.trim($("#q_coating").val()) == "") {
        $("#q_coating-button").addClass('field_error');
        if (flg == 0) {
            $("#q_coating").focus();
            Toast.error('Please select coating');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_coating-button").removeClass('field_error');

    }
	
	if ($.trim($("#q_add_ons").val()) == "") {
        $("#q_add_ons-button").addClass('field_error');
        if (flg == 0) {
            $("#q_add_ons").focus();
            Toast.error('Please select add ons');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_add_ons-button").removeClass('field_error');

    }
	
	if ($.trim($("#q_colours").val()) == "") {
        $("#q_colours-button").addClass('field_error');
        if (flg == 0) {
            $("#q_colours").focus();
            Toast.error('Please select colours');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_colours-button").removeClass('field_error');

    }
	
	if ($.trim($("#q_quantity_1").val()) == "") {
        $("#q_quantity_1").addClass('field_error');
        if (flg == 0) {
            $("#q_quantity_1").focus();
            Toast.error('Please enter quantity 1');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_quantity_1").removeClass('field_error');

    }
	
	if ($.trim($("#q_quantity_2").val()) == "") {
        $("#q_quantity_2").addClass('field_error');
        if (flg == 0) {
            $("#q_quantity_2").focus();
            Toast.error('Please enter quantity 2');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_quantity_2").removeClass('field_error');

    }
	
	if ($.trim($("#q_file").val()) == "") {
        $("#q_file").addClass('field_error');
        if (flg == 0) {
            $("#q_file").focus();
            Toast.error('Please upload your file');
            flg = flg + 1;
        }
    }
    else {
        $("#q_file").removeClass('field_error');

    }
	
	if ($.trim($("#q_full_name").val()) == "") {
        $("#q_full_name").addClass('field_error');
        if (flg == 0) {
            $("#q_full_name").focus();
            Toast.error('Please enter your name ');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_full_name").removeClass('field_error');

    }
	
	if ($.trim($("#q_phone").val()) == "") {
        $("#q_phone").addClass('field_error');
        if (flg == 0) {
            $("#q_phone").focus();
            Toast.error('Please enter phone ');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_phone").removeClass('field_error');

    }		
	
	
	filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; ///^.+@.+\..{2,15}$/;
    if (!(filter.test($.trim($("#q_email").val())))) {
        $("#q_email").addClass('field_error');
        if (flg == 0) {
            $("#q_email").focus();
            Toast.error('Please enter valid email');
            flg = flg + 1;
        }
    }
    else {
        $("#q_email").removeClass('field_error');

    }
	
		
		
	
	
	if ($.trim($("#q_company").val()) == "") {
        $("#q_company").addClass('field_error');
        if (flg == 0) {
            $("#q_company").focus();
            Toast.error('Please enter company ');
           
            flg = flg + 1;
        }
    }
    else {
        $("#q_company").removeClass('field_error');

    }	


    if (flg == 0) {
        return true;
    }else {
		return false;
	}
	 
	
	}
</script>
@stop
