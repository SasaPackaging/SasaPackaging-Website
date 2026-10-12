@extends('admin.layouts.dashboard')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">Setting</h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= URL::to('admin/dashboard') ?>">Home</a><i class="fa fa-angle-right"></i></li>
            <li><a href="#">Setting</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    Setting
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" method="post" name="form_data" id="form_data"  class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?=csrf_token()?>">
                    <div class="form-body">
                         <div class="alert alert-success" style="display:none;"></div>
                        <h4>General</h4><hr/>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Site Name </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SITE_NAME]' id="SITE_NAME" value ='<?=App\Model\Setting::findByKey('SITE_NAME')?>' >
							</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Site Link </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SITE_LINK]' id="SITE_LINK" value ='<?=App\Model\Setting::findByKey('SITE_LINK')?>' >
							</div>
                        </div>
                        
                        
                        <h4>Contact</h4><hr/>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">From Name </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[FROM_NAME]' id="FROM_NAME" value ='<?=App\Model\Setting::findByKey('FROM_NAME')?>' >

                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">From Email </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[FROM_EMAIL]' id="FROM_EMAIL" value ='<?=App\Model\Setting::findByKey('FROM_EMAIL')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Contact Email </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[CONTACT_EMAIL]' id="CONTACT_EMAIL" value ='<?=App\Model\Setting::findByKey('CONTACT_EMAIL')?>' >

                            </div>
                        </div>
 
 						<div class="form-group">
                            <label class="col-md-3 control-label">Phone </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[PHONE]' id="PHONE" value ='<?=App\Model\Setting::findByKey('PHONE')?>' >

                            </div>
                        </div>
                        
                        
                        
                        
                         <div class="form-group" >
                            <label class="col-md-3 control-label">Phone 2 </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[POBOX]' id="POBOX" value ='<?=App\Model\Setting::findByKey('POBOX')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Address  </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name='setting[ADDRESS]' id="ADDRESS" ><?=App\Model\Setting::findByKey('ADDRESS')?></textarea>

                            </div>
                        </div>
                        
                        
                        
                        <div class="form-group" style="display:none;">
                            <label class="col-md-3 control-label">Time </label>
                            <div class="col-md-4">
                                <textarea class="form-control" name='setting[TIME]' id="TIME" ><?=App\Model\Setting::findByKey('TIME')?></textarea>

                            </div>
                        </div>
                        
                       
						
                        
                        
                      
                        
                       <h4>Social Media</h4><hr/>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Facebook </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[FACEBOOK]' id="FACEBOOK" value ='<?=App\Model\Setting::findByKey('FACEBOOK')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Twitter </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[TWITTER]' id="TWITTER" value ='<?=App\Model\Setting::findByKey('TWITTER')?>' >

                            </div>
                        </div>
                        
                        
                        
                        <div class="form-group" style="display:none;">
                            <label class="col-md-3 control-label">Instagram </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[INSTAGRAM]' id="INSTAGRAM" value ='<?=App\Model\Setting::findByKey('INSTAGRAM')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Linkedin </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[LINKEDIN]' id="LINKEDIN" value ='<?=App\Model\Setting::findByKey('LINKEDIN')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Youtube </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[YOUTUBE]' id="YOUTUBE" value ='<?=App\Model\Setting::findByKey('YOUTUBE')?>' >

                            </div>
                        </div>
                        
                        
                         
                          <div class="form-group" style="display:none;">
                            <label class="col-md-3 control-label">Successful Customers </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[PROJECTS]' id="PROJECTS" value ='<?=App\Model\Setting::findByKey('PROJECTS')?>' >

                            </div>
                        </div>
                        
                         
                        
                         
                        
                                              
                        <h4>SMTP</h4><hr/>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Driver </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SMTP_DRIVER]' id="SMTP_DRIVER" value ='<?=App\Model\Setting::findByKey('SMTP_DRIVER')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Host </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SMTP_HOST]' id="SMTP_HOST" value ='<?=App\Model\Setting::findByKey('SMTP_HOST')?>' >

                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Username </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SMTP_USERNAME]' id="SMTP_USERNAME" value ='<?=App\Model\Setting::findByKey('SMTP_USERNAME')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Password </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SMTP_PASSWORD]' id="SMTP_PASSWORD" value ='<?=App\Model\Setting::findByKey('SMTP_PASSWORD')?>' >

                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Port </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name='setting[SMTP_PORT]' id="SMTP_PORT" value ='<?=App\Model\Setting::findByKey('SMTP_PORT')?>' >

                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <span class="display-hide" id="id_loading_process"></span>
                            <button type="button" onclick="update_setting()" class="btn green"  name="id_btn_submit" id="id_btn_submit"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>


@stop