@extends('admin.layouts.dashboard')
@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">Quotes</h3>
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= URL::to('admin/dashboard') ?>">Home</a><i class="fa fa-angle-right"></i></li>
            <li><a href="#">Quotes</a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-toolbar" style="margin-bottom: 8px;">
            <div class="btn-group pull-right">
                
            </div>

        </div>
        <br/>
        <br/>
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    Quotes Management
                </div>

            </div>

            <div class="portlet-body">

                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    <thead>
                        <tr>
                            <th style="display:none;">
                                Id
                            </th>    
                            <th >
                                Name
                            </th>
                            <th>
                                Email
                            </th>
                             <th>
                                Phone
                            </th>
							<th>
                                Product Name
                            </th>

                            <th>
                                New
                            </th>
                            
								
                            <th>Action


                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result_dp as $row) {

                            if ($row['status'] == 'Yes') {
                                $status = '<a style="text-decoration:none;" href="javascript:void(0)"  ><span  class="label label-danger " data-title="Yes"  > Yes</span></a>';
                            } else {
                                $status = '<a style="text-decoration:none;" href="javascript:void(0)"  ><span  class="label label-success" data-title="No"  > No</span></a>';
                            }
							
							
							
                            ?>
                            <tr class="odd gradeX">
                                <td style="display:none;">
                                    <?= $row['id'] ?>
                                </td>
                                <td  >
                                    <?= $row['q_full_name'] ?>
                                </td>
                                <td  >
                                <?= $row['q_phone'] ?>
                                </td>
                                 <td  >
                                    <?= $row['q_email'] ?>
                                </td>
                                
                                 <td  >
                                    <?= $row['q_produt_name'] ?>
                                </td>

                                <td id="row_status_<?= $row['id'] ?>">
                                    <?= $status ?>
                                </td>
                                
                                 
								
                                <td style="width:20px;">
                                    <a href="<?= URL::to('/admin/quotes/detail/' . md5($row['id'])) ?>"><button type="button" class="btn btn-primary btn-xs" data-title="Edit"  ><i class="fa fa-eye"></i> View</button></a>
                                    &nbsp; <a onclick="delete_record('<?= md5($row['id']) ?>')"  href="javascript:void(0)" ><button type="button" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><i class="fa fa-trash"></i> Delete</button></a></td>


                            </tr>
                        <?php } ?>


                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>


<link rel="stylesheet" type="text/css" href="{{ url('/') }}/public/assets/admin/plugins/data-tables/DT_bootstrap.css"/>
<script type="text/javascript" src="{{ url('/') }}/public/assets/admin/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="{{ url('/') }}/public/assets/admin/plugins/data-tables/DT_bootstrap.js"></script>


<script type="text/javascript">
    $('#sample_1').dataTable({
        "aoColumns": [
            null,
           null,
			null,
			null,
			null,
           null,
            {"bSortable": false}
        ],
        "aoColumnDefs": [
            {'bSortable': true, 'aTargets': [0]}
        ]
    });

    jQuery('#sample_1_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline");
    jQuery('#sample_1_wrapper .dataTables_length select').addClass("form-control input-xsmall");

    function delete_record(id) {
        bootbox.confirm('<div class="alert alert-danger alert-error" style="font-size:18px; margin-top: 20px;" >Are you sure you want to delete this ?</div>', function (result) {
            if (result) {
                window.location = "<?= URL::to('admin/quotes/delete') ?>/" + id;
            }
        });
    }

</script>

@stop