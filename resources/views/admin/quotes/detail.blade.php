@extends('admin.layouts.dashboard')
@section('content')
<!-- BEGIN PAGE HEADER-->

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Quotes 
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?= URL::to('admin/dashboard') ?>">
                    Home
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>

                <a href="<?= URL::to('admin/quotes') ?>">
                    Quotes
                </a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">
                    Detail
                </a>
            </li>

        </ul>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    Detail
                </div>
                
                

            </div>
          <div class="portlet-body">
          		<h4>Size</h4>
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    
                   
                        
                            <tr class="odd gradeX">
                               
                                <td width="30%">Length:</td>
                                <td  ><?=$data['q_length']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                                <td  >Width:</td>
                                <td  ><?=$data['q_width']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                               <td  >Height:</td>
                                <td  ><?=$data['q_height']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                               <td  >Unit:</td>
                                <td  ><?=$data['q_unit']?></td>
                            </tr>
                        


                    
                </table>
                
                <h4>Product Details</h4>
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    
                   
                        
                            <tr class="odd gradeX">
                               
                                <td width="30%">Product Name:</td>
                                <td  ><?=$data['q_produt_name']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                                <td  >Stock:</td>
                                <td  ><?=$data['q_stock']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                               <td  >Coating:</td>
                                <td  ><?=$data['q_coating']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                               <td  >Add Ons:</td>
                                <td  ><?=$data['q_add_ons']?></td>
                            </tr>
                            
                              <tr class="odd gradeX">
                               
                               <td  >Colours:</td>
                                <td  ><?=$data['q_colours']?></td>
                            </tr>
                        


                    
                </table>
                
                 <h4>Specify Quantity</h4>
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    
                   
                        
                            <tr class="odd gradeX">
                               
                                <td width="30%">Quantity 1:</td>
                                <td  ><?=$data['q_quantity_1']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                                <td  >Quantity 2:</td>
                                <td  ><?=$data['q_quantity_2']?></td>
                            </tr>
                            
                            
                        


                    
                </table>
                
                <h4>Upload File</h4>
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    
                   
                        
                            <tr class="odd gradeX">
                               
                                <td width="30%">File:</td>
                                <td  ><a href='<?=url('/')?>/public/upload/quote/<?=$data['q_file']?>' download>Download</a></td>
                            </tr>
                            
                            
                            
                            
                        


                    
                </table>
                
                 <h4>Contact Information</h4>
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    
                   
                        
                            <tr class="odd gradeX">
                               
                                <td width="30%">Full Name:</td>
                                <td  ><?=$data['q_full_name']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                                <td  >Phone Number:</td>
                                <td  ><?=$data['q_phone']?></td>
                            </tr>
                            
                            <tr class="odd gradeX">
                               
                                <td  >Email:</td>
                                <td  ><?=$data['q_email']?></td>
                            </tr>
                            
                            
                            <tr class="odd gradeX">
                               
                                <td  >Company Name:</td>
                                <td  ><?=$data['q_company']?></td>
                            </tr>
                            
                            
                            <tr class="odd gradeX">
                               
                                <td  >Message:</td>
                                <td  ><?=$data['q_message']?></td>
                            </tr>
                            
                            
                        


                    
                </table>
            </div>
        </div>
    </div>
</div>
<script>

$('#id_upload').click(function(){
$('#image').trigger('click');
});

$('#id_upload_icon').click(function(){
$('#icon').trigger('click');
});



</script>

@stop