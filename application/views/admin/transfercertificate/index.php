<?php 
$admin_session   = $this->session->userdata('admin');
$currency_symbol = $admin_session['currency_symbol'];
?>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <div class="box-header with-border">
                       <h3 class="box-title titlefix"><?php echo $this->lang->line('transfer_certificate_settings'); ?></h3>
                    </div>
                    <ul class="nav nav-tabs">
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('transfer_certificate_fields'); ?></a></li>
                        <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('header_footer_setting'); ?></a></li>
                        <li><a href="#tab_3" data-toggle="tab"><?php echo $this->lang->line('other_setting'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
							
							<?php 
                                $errors = [];
                                if (form_error('validate_resource')) {
                                    $errors[] = form_error('validate_resource');
                                }
                                if (form_error('validate_storage')) {
                                    $errors[] = form_error('validate_storage');
                                }

                                if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        
                                            <?php foreach ($errors as $error): ?>
                                             <?php echo $error; ?> 
                                            <?php endforeach; ?>
                                        
                                    </div>
                                    <?php endif;
                                
								?>
								
                            <?php   if ($this->session->flashdata('msg')) {  
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                    } 
                            ?>
                            <form role="form" id="form1"  enctype="multipart/form-data" action="<?php echo site_url('admin/transfercertificate/edit_header') ?>" class="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('header_image') . " (2230px X 300px)"; ?><small class="req"> *</small></label>
                                            <input id="documents" data-default-file="<?php echo $this->customlib->getBaseUrl() ?>/uploads/transfer_certificate/<?php echo $get_settings[0]['header_image']; ?>" placeholder="" type="file" class="filestyle form-control" data-height="180"  name="header_image">
                                            <input  placeholder="" type="hidden" class="form-control" value="transfer_certificate" name="type">
                                            <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                        </div>
                                        <div class="form-group"><label><?php echo $this->lang->line('footer_content'); ?> </label>
                                            <textarea id="transfer_certificate_textarea" name="transfer_certificate" class="form-control" style="height: 250px">
                                                <?php echo set_value('transfer_certificate',$get_settings[0]['footer_content']); ?>
                                            </textarea>
                                            <span class="text-danger"><?php echo form_error('transfer_certificate'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="pull-right">
                                            <button type="submit" id="submitbtn1" class="btn btn-primary " data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <div class="tab-pane" id="tab_3">  
                          <?php foreach ($get_settings as $key => $value) {   ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="">
                                <div class="box-body">
									<div class="row">
                                    <div class="col-md-12">                                        
                                        <h4 class="session-head"><?php echo $this->lang->line('transfer_certificate_serial_number'); ?></h4>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('start_next_from'); ?><small class="req"> *</small> </label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" name="tc_no_start" id="tc_no_start" value="<?php echo $print_next_tc_no;?>">
                                                <span class="text-danger"><?php echo form_error('tc_no_start'); ?></span>
                                            </div>
											<div class="col-sm-2">
											  
											  
											</div>
                                        </div>
                                    </div>
									<div class="col-md-12">
                                        <div class="form-group row"> 
                                            <label class="col-sm-3"><?php echo $this->lang->line('affiliation_no'); ?></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" name="affiliation_no" id="affiliation_no" value="<?php echo $value['affiliation_no'];?>">
                                                <span class="text-danger"><?php echo form_error('affiliation_no'); ?></span>
                                            </div>
											<div class="col-sm-2">
											  <button type="button" class="btn btn-primary affiliation_no" onclick="save_generation_id()" autocomplete="off"> <?php echo $this->lang->line('save');?></button>
											</div>
                                        </div>
                                    </div>
                                    </div>
									
                                    <div class="row">
                                        <div class="settinghr"></div>  
                                        <div class="col-lg-3 col-md-6 col-sm-6 mt10 mb10">
                                            <div class="demo-card-inner text-center">
                                            <h5><?php echo $this->lang->line('class_teacher_signature');?></h5> 
                                            <div class="text-center">     
                                            <?php if ($value['class_teacher_signature'] == "") { ?>
                                            <div class="card-body-logo-img">
                                            <?php 
                                            if(file_exists(FCPATH."uploads/transfer_certificate/no_image.png")){ ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/no_image.png') ?>" class="" alt="" width="304" height="236">
                                            <?php } ?>
                                            </div>
                                            <?php
                                            } else { ?>
                                            <div class="card-body-logo-img">
                                            <?php if(file_exists(FCPATH."uploads/transfer_certificate/".$value['class_teacher_signature'])){ ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/'.$value['class_teacher_signature']); ?>" class="" alt="" width="304" height="236">  <?php } ?></div>
                                            <?php } ?>
                                            <p class="bolds ptt10">(200px X 80px)</p>
                                            </div> 
                                            <div class="card-footer  display-inline-block">   
                                            <a href="#schsetting" role="button" class="d-flex justify-centent-center align-items-center btn-primary btn-sm mx-auto btn upload_logo" 
                                            data-id_logo="<?php echo $value['id'];?>" data-field_name="class_teacher_signature"><?php echo $this->lang->line('update'); ?></a>
                                            </div>
                                            <div class="logo_image pt5">
                                                <div class="fadeheight-sms"><?php if ($value['class_teacher_signature'] != "") { ?> 
                                                <p class="mb0"> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash-o" onclick="remove_signature(<?php echo $value['id'];?>,'class_teacher_signature')"></i></a> <?php echo $value['class_teacher_signature'];?></p> <?php } ?>
                                                </div>
                                            </div> 
                                            </div> 
                                        </div> 
                                         
                                        <!-- ============================================================= -->
                                        <div class="col-lg-3 col-md-6 col-sm-6 mt10 mb10 col-lg-offset-1">
                                            <div class="demo-card-inner text-center">
                                            <h5><?php echo $this->lang->line('principle_signature');?></h5> 
                                            <div class="text-center">     
                                            <?php if ($value['signature_of_principle'] == "") { ?>
                                            <div class="card-body-logo-img">
                                                <?php if(file_exists(FCPATH."uploads/transfer_certificate/no_image.png")){ ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/no_image.png') ?>" class="" alt="" width="200" height="80">
                                                <?php } ?>
                                            </div>
                                            <?php
                                            } else { ?>
                                            <div class="card-body-logo-img">
                                            <?php if(file_exists(FCPATH."uploads/transfer_certificate/".$value['signature_of_principle'])){ ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/'.$value['signature_of_principle']); ?>" class="" alt="" width="200" height="80">
                                                 <?php } ?>
                                            </div>
                                            <?php } ?>
                                            <p class="bolds ptt10">(200px X 80px)</p>
                                            </div>  
                                            <div class="card-footer display-inline-block">  
                                            <a href="#schsetting" role="button" class="btn d-flex justify-centent-center align-items-center btn-primary btn-sm mx-auto upload_logo" 
                                            data-id_logo="<?php echo $value['id'];?>"  data-field_name="signature_of_principle"><?php echo $this->lang->line('update'); ?></a></div>
                                            <div class="logo_image pt5">
                                                <div class="fadeheight-sms"> <?php if ($value['signature_of_principle'] != "") { ?> 
                                                <p class="mb0">
                                                <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash-o" onclick="remove_signature(<?php echo $value['id'];?>,'signature_of_principle')"></i></a>
                                                <?php echo $value['signature_of_principle'];?></p> <?php } ?>
                                                </div>
                                            </div></div>                                            
                                        </div> 
                                        <!-- ============================================================= -->										
                                        <div class="col-lg-3 col-md-6 col-sm-6 mt10 mb10 col-lg-offset-1">
                                            <div class="demo-card-inner text-center">
                                            <h5><?php echo $this->lang->line('checked_by');?></h5> 
                                            <div class="text-center">     
                                            <?php if ($value['checked_by'] == "") { ?>
                                            <div class="card-body-logo-img">
                                                <?php if(file_exists(FCPATH."uploads/transfer_certificate/no_image.png")){ ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/no_image.png') ?>" class="" alt="" width="200" height="80">
                                                <?php } ?>
                                            </div>
                                            <?php
                                            } else { ?>
                                            <div class="card-body-logo-img">
                                            <?php if(file_exists(FCPATH."uploads/transfer_certificate/".$value['checked_by'])){ ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/'.$value['checked_by']); ?>" class="" alt="" width="304" height="236">
                                            <?php } ?>
                                            </div>
                                            <?php } ?>
                                            <p class="bolds ptt10">(200px X 80px)</p>
                                            </div>   
                                            <div class="card-footer display-inline-block"> 
                                            <a href="#schsetting" role="button" class="btn mx-auto d-flex justify-centent-center align-items-center btn-primary btn-sm upload_logo" 
                                            data-id_logo="<?php echo $value['id'];?>" data-field_name="checked_by"><?php echo $this->lang->line('update'); ?></a></div>
											
                                             
                                            <div class="logo_image pt5">
                                                <div class="fadeheight-sms"><?php if ($value['checked_by'] != "") { ?> 
                                                <p class="mb0"> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash-o" onclick="remove_signature(<?php echo $value['id'];?>,'checked_by')"></i></a> <?php echo $value['checked_by'];?></p> <?php } ?>
                                                </div>
                                            </div></div>  
                                        </div>
										<!-- ============================================================= -->
                                    </div>
                                </div>
                                </div>
                           </div>
                        </div>
                         <?php } ?>
                    </div>

                        <div class="tab-pane" id="tab_2">
                                <div class="" id="transfee">
                                    <div class="row">
                                            <div class="col-sm-10">
                                              <h4 class="box-title"><?php echo $this->lang->line('transfer_certificate_settings'); ?></h4>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="download_label">
                                                    <?php echo $this->lang->line('transfer_certificate_settings'); ?>
                                                </div>
												<a class="btn btn-default btn-xs pull-right" id="print" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv()" ><i class="fa fa-print"></i></a> 
												<button class="btn btn-default btn-xs pull-right" id="btnExport" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download_excel'); ?>"  onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i></button>
                                                <table class="table table-striped table-bordered table-hover tableswitch grabbable" id="headerTable"  cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('transfer_certificate_settings'); ?>" >
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('name'); ?></th>
                                                            <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="row_position" class="row_position ">
                                                    <?php

$sch_setting_array = json_decode(json_encode($sch_setting_detail), true);
if (!empty($fields)) {
    foreach ($fields as $fields_key => $fields_value) {
        if($fields_value['is_default']==1){
            $fields_name=$fields_value['name'];
            if (array_key_exists("$fields_name", $sch_setting_array)){
                if (($sch_setting_detail->$fields_name)) { ?>
                    <!-- when field found in sch_setting field and is field is active -->
                    <tr  id="<?php echo $fields_value["id"]; ?>">
                        <td class="text-rtl-right" width="100%"><?php
                            echo $this->lang->line($fields_value['lang_key']); ?></td>
                        <td class="text-right">
                            <div class="material-switch pull-right">
                            <input id="field_<?php echo $fields_value['name']; ?>" name="<?php echo $fields_value['name']; ?>" type="checkbox" data-role="field_<?php $fields_value['name'];?>"  data-iscustomfield="0"  class="chk"  value="" <?php echo set_checkbox($fields_value['name'], $fields_value['name'], findSelected($inserted_fields,$fields_value['name'])); ?>/>
                            <label for="field_<?php echo $fields_value['name']; ?>" class="label-info-success"></label>
                            </div> 
                        </td>
                    </tr>
                <?php  
                }
            } else {   ?>
                <!-- when field not found in setting field -->
                <tr id="<?php echo $fields_value["id"]; ?>">
                    <td class="text-rtl-right" width="100%"><?php echo $this->lang->line($fields_value['lang_key']); ?></td>
                    <td class="text-right">
                        <div class="material-switch pull-right">
                        <input id="field_<?php echo $fields_value['name']; ?>" name="<?php echo $fields_value['name']; ?>" type="checkbox" data-role="field_<?php $fields_value['name'];?>"  data-iscustomfield="0" class="chk"  value="" <?php echo set_checkbox($fields_value['name'], $fields_value['name'], findSelected($inserted_fields, $fields_value['name'])); ?>/>
                        <label for="field_<?php echo $fields_value['name']; ?>" class="label-info-success"></label>
                        </div>
                    </td>
                </tr>
            <?php
            }
        }

// ===========show only old custom fields===============//
if (!empty($custom_fields_array)) {
        foreach ($custom_fields_array as $custom_fields) {
            //custom field should be exist in the tranfer certificate table only then we will show
            if($custom_fields['name']==$fields_value['name']){
            $exist = $this->customlib->checkfieldexist_transfer_certificate($custom_fields['name']);
            if ($exist == 1) {
                $value = $this->customlib->checkfieldexist_transfer_certificate($custom_fields['name']);
            } else {
                $value = 0;
            }
            ?>
             <tr id="<?php echo $fields_value["id"]; ?>">
                <td class="text-rtl-right" width="100%"><?php echo $custom_fields['name']; ?></td>
                <td class="text-right">
                    <div class="material-switch pull-right">
                    <input id="field_<?php echo $custom_fields['name']; ?>" name="<?php echo $custom_fields['name']; ?>" type="checkbox" data-role="field_<?php $custom_fields['name'];?>" data-iscustomfield="1" class="chk"  value="<?php echo $value; ?>" <?php if ($value == 1) {echo 'checked';}?> />
                    <label for="field_<?php echo $custom_fields['name']; ?>" class="label-info-success"></label>
                    </div> 
                </td>
            </tr> 
        <?php 
            }
        }
    }
// ===========show only old custom fields===============//
}

// ===========show New custom fields===============//
    if (!empty($custom_fields_array)) {
        foreach ($custom_fields_array as $custom_fields) {
            $name_teststest=$custom_fields['name'];
            if(in_array("$name_teststest", array_column($fields, 'name'))==false){
                $custom_position = $this->customlib->getfieldsposition($custom_fields['name']);
                $exist = $this->customlib->checkfieldexist_transfer_certificate($custom_fields['name']);
                if($exist==0){
                    if ($exist == 1) {
                        $value = $this->customlib->checkfieldexist_transfer_certificate($custom_fields['name']);
                    } else {
                        $value = 0;
                    }
                    ?>
                    <tr id="<?php echo $custom_position; ?>">
                        <td><?php echo $custom_fields['name']; ?></td>
                        <td class="text-right">
                            <div class="material-switch pull-right">
                            <input id="field_<?php echo $custom_fields['name']; ?>" name="<?php echo $custom_fields['name']; ?>" type="checkbox" data-role="field_<?php $custom_fields['name'];?>" data-iscustomfield="1" class="chk"  value="<?php echo $value; ?>" <?php if ($value == 1) {echo 'checked';}?> />
                            <label for="field_<?php echo $custom_fields['name']; ?>" class="label-info-success"></label>
                            </div> 
                        </td>
                    </tr> 
                <?php 
                }
            }
        }
    }
// ===========show New custom fields===============//
}
?>
        </tbody>
       </table>
      </div>
   </div>
</div>
 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
</div>

<!-- ============= upload image model================= -->

<div class="modal fade" id="modal-uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('admin/transfercertificate/upload_signature') ?>" enctype="multipart/form-data">
                    <input value="" type="hidden" name="id" id="id_logo"/>
                    <input value="" type="hidden" name="field_name" id="field_name"/>
                    <input type="file" name="file" id="file">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-area"  id="uploadfile">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong></label>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- ============= upload image model================= -->

<?php

function findSelected($inserted_fields, $find)
{
    foreach ($inserted_fields as $inserted_key => $inserted_value) {
        if ($find == $inserted_value['name'] && $inserted_value['status']) {
            return true;
        }
    }
    return false;
}
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<script>
    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(data) {
        $.ajax({
            url: base_url + "admin/transfercertificate/sortQueue",
            type:'post',
            dataType:'json',
            data:{position:data},
            success:function(data){
                if(data.status =="success"){
                    successMsg(data.message);
                }else{
                    errorMsg(data.message);
                }
            }
        })
    }
</script>


<script>

$(function () {
    $("#transfer_certificate_textarea").wysihtml5();
});

$(document).ready(function() {
    $('.example').DataTable({
        "info": false,      // Hides "Showing X to Y of Z entries"
        "paging": false,    // Disables pagination
    });
});

</script>

<script>
   $(".ckeditor").each(function (_, ckeditor) {
        CKEDITOR.env.isCompatible = true;
        CKEDITOR.replace(ckeditor, {
            toolbar: 'Ques',
            customConfig: baseurl + '/backend/js/ckeditor_config.js'
        });
    });
</script>


<script type="text/javascript">
(function ($) {
    $(document).ready(function () {
        $(document).on('click', '.chk', function(event) {
        var name=$(this).attr('name');
        var iscustomfield=$(this).data('iscustomfield');
        var status=1;
        if(this.checked) {
            status=1;
        } else {
            status=0;
        }

        if(confirm("<?php echo $this->lang->line('confirm_status'); ?>")){
            changeStatus(name, status,iscustomfield);
        }
        else{
            event.preventDefault();
        }
        });
    });

    function changeStatus(name, status,iscustomfield) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/transfercertificate/changeformfieldsetting",
            data: {'name': name, 'status': status,'iscustomfield':iscustomfield},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
            }
        });
    }

 })(jQuery);
</script>


<script type="text/javascript">
(function ($) {
    var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    function IsNumeric(e)
    {
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 48 && keyCode <= 57) ||  keyCode==46);
        document.getElementById("error").style.display = ret ? "none" : "inline";
        return ret;
    }
 })(jQuery);
</script>

<script>
    $(function(){
        $('#form1'). submit( function() {
            $("#submitbtn").button('loading');
        });
    })
</script>

<!-- =================================================================================================================================== -->

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var logo_type = "logo";
    $('.upload_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        id_logo = $this.data('id_logo');
        field_name = $this.data('field_name');
        $("#id_logo").val(id_logo);
        $("#field_name").val(field_name);
        logo_type = $this.data('logo_type');
        $this.button('loading');
        $('#modal-uploadfile').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }); 

    // set focus when modal is opened
    $('#modal-uploadfile').on('shown.bs.modal', function () {
        $('.upload_logo').button('reset');
    });

</script>
<script type="text/javascript">
    $(function () {

        // Drag enter
        $('.upload-area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Upload");
            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();
            fd.append('file', file[0]);
            fd.append("id", $('#id_logo').val());
            fd.append("logo_type", logo_type);
            fd.append("field_name", field_name);
            uploadData(fd);
        });

        // Open file selector on div click
        $("#uploadfile").click(function () {
            $("#file").click();
        });

        // file selected
        $("#file").change(function () {
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
            fd.append("id", $('#id_logo').val());
            fd.append("logo_type", logo_type);
            fd.append("field_name", field_name);
            uploadData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadData(formdata) {
        $.ajax({
            url: '<?php echo site_url('admin/transfercertificate/update_signature') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                $('#modal-uploadfile').addClass('modal_loading');
            },           
			
			success: function(res) {
                if (res.success) {
                   
					successMsg(res.message);
                    window.location.reload(true);
                } else {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
					
                    errorMsg(message);
                }
            },
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $('#modal-uploadfile').removeClass('modal_loading');
            }
        });
    }

    function remove_signature(id,field_name){
        if (confirm("<?php echo $this->lang->line('are_you_sure_you_want_to_delete_this');?>")) {
            $.ajax({
                url: '<?php echo site_url('admin/transfercertificate/remove_signature') ?>',
                type: 'post',
                data: {id:id,field_name:field_name},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (response) {
                    if (response.success) {
                        successMsg(response.message);
                        window.location.reload(true);
                    } else {
                        errorMsg(response.error.file);
                    }
                },
                error: function (xhr) { // if error occured

                },
                complete: function () {

            }
            });
        }

    }

    function save_generation_id(){
        var tc_no_start=$("#tc_no_start").val();
        var affiliation_no=$("#affiliation_no").val();
        $.ajax({
            url: '<?php echo site_url('admin/transfercertificate/save_generation_id') ?>',
            type: 'post',
            data: {id:1,tc_no_start:tc_no_start,affiliation_no:affiliation_no},
            dataType: 'json',
            beforeSend: function () {
            },
            success: function (response) {
                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    errorMsg(response.error);
                }
            },
            error: function (xhr) { // if error occured
            },
            complete: function () {
            }
        });
    }
//maintain tab status after refresh

$(document).ready(function(){
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
    var id = $(e.target).attr("href").substr(1);
    window.location.hash = id;
    window.scrollTo({ top: 0 });
});

var hash = window.location.hash;
    activaTab(hash);
});

function activaTab(tab){
    $('ul.nav-tabs > li > a[href="' + tab + '"]').trigger("click");  
};
  
</script>
<script>

    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

    function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
                "<html><head><title></title></head><body>" +
                divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;

        location.reload(true);
    }   
    
</script>

