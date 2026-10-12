@extends('layouts.master')

@section('customstyle')



@stop



@section('header')

@include('partial.header')

@stop

@section('content')

<?php 
$marketing_dp = App\Model\Marketing::whereRaw("status = 'Yes' ")->orderByRaw('sort_order')->get()->toArray();

?>

 <div class="ttm-page-title-row" style="background-image:url(<?=$cms_dp['banner']?>);">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="title-box ttm-textcolor-white">
                            <div class="page-title-heading">
                                <h1 class="title"><?=$cms_dp['banner_heading']?></h1>
                            </div><!-- /.page-title-captions -->
                            <div class="breadcrumb-wrapper">
                                <span>
                                    <a title="Homepage" href="<?php echo URL::to('/'); ?>"><i class="ti ti-home"></i>&nbsp;&nbsp;Home</a>
                                </span>
                                <span class="ttm-bread-sep">&nbsp; | &nbsp;</span>
                                <span style="color: #fff;"><?=$cms_dp['name']?></span>
                            </div>  
                        </div>
                    </div><!-- /.col-md-12 -->  
                </div><!-- /.row -->  
            </div><!-- /.container -->                      
        </div><!-- page-title end-->


	<div class="site-main">
    
    	<section class="ttm-row about3-section clearfix">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-12">
                    <div class="pl-35 res-991-pl-0 res-991-mt-30">
                        <!-- section title -->
                        <div class="section-title clearfix">
                            <div class="title-header">
                                <h5><?=$cms_dp['tag_line']?></h5>
                                <h2 class="title">
                                    <?=$cms_dp['heading']?>
                                </h2>
                            </div>
                           <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Sr No</th>
                                            <th scope="col">Name / Description</th>
                                            <th scope="col">Download</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($marketing_dp)>0){?>
                                        <?php $i=1; foreach ($marketing_dp as $row){?>
                                        <tr>
                                            <th scope="row"><?=$i?></th>
                                            <td><?= $row['name'] ?></td>
                                            <td><a href="<?= url('/') . '/public/upload/marketing/' . $row['image'] ?>" download  class="btn btn-sm btn-success">Download</a></td>
                                        </tr>
                                        <?php $i++; } ?>
                                        <?php }else{?>
                                        <tr>
                                            <td colspan="3" class="text-center">No Result Found</td>
                                           
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                        </div><!-- section title end -->
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    </div>

  @section('footer')

@include('partial.footer')

@stop   



@stop



@section('customscript')



@stop



