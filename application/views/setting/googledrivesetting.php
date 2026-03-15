<div class="content-wrapper" style="min-height: 348px;">     
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?> 
            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="box box-primary">
                     
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('google_drive_setting'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                          <form role="form" id="savegoogledrive" action="<?php echo base_url('schsettings/savegoogledrive') ?>"  method="post">
                                <div class="box-body">
                                    <div class="row">
                                         
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-6"><?php echo $this->lang->line('client_id'); ?><small class="req"> *</small></label>
                                                <div class="col-sm-6">
                                                    <input autofocus="" type="text" class="form-control" name="client_id" value="<?php echo $setting_result['client_id']; ?>">
                                                    <span class=" text text-danger client_id_error"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-6"><?php echo $this->lang->line('api_key'); ?><small class="req"> *</small></label>
                                                <div class="col-sm-6">
                                                    <input type="hidden" name="id" value="<?php echo $setting_result['id']; ?>">
                                                    <input type="text" class="form-control" name="api_key"  value="<?php echo $setting_result['api_key']; ?>">
                                                    <span class=" text text-danger api_key_error"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-6"><?php echo $this->lang->line('project_number_app_id'); ?><small class="req"> *</small></label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="project_number"  value="<?php echo $setting_result['project_number']; ?>">
                                                    <span class=" text text-danger project_number_error"></span>
                                                </div>
                                            </div>
                                        </div>                                        

                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-6"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                <div class="col-sm-6">
                                                    <div class="material-switch ">
                                                        <input id="is_enable" name="is_enable" type="checkbox" class=""
                                                        value="" <?php echo set_checkbox('is_enable', 'enabled', ($setting_result['is_enable']=="enabled")); ?> />
                                                        <label for="is_enable" class="label-info-success"></label>
                                                    </div>
                                                    <span class=" text text-danger is_enable_error"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 <?php if($setting_result['is_enable']=="enabled"){ echo "show"; }else{ echo "hide"; } ?>" id="otheroption" >

                                            <div class="form-group row">
                                                <label class="col-sm-6"><?php echo $this->lang->line("allow_students_parents_and_staff_to_upload_student_document_through_google_drive"); ?><small class="req"> *</small></label>

                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-md-2"> <?php echo $this->lang->line('student'); ?></div>
                                                        <div class="col-md-2"> 
                                                            <div class="material-switch">
                                                            <input id="is_student" name="is_student" type="checkbox" class=""
                                                            value="" <?php echo set_checkbox('is_enable', 'enabled', ($setting_result['is_student']=="enabled")); ?> />
                                                            <label for="is_student" class="label-info-success"></label>
                                                            </div>
                                                        </div>
                                                         
                                                        <div class="col-md-2"> <?php echo $this->lang->line('guardian'); ?></div>
                                                        <div class="col-md-2"> 
                                                            <div class="material-switch">
                                                             <input id="is_parent" name="is_parent" type="checkbox" class=""
                                                                value="" <?php echo set_checkbox('is_parent', 'enabled', ($setting_result['is_parent']=="enabled")); ?> />
                                                                <label for="is_parent" class="label-info-success"></label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-2"> <?php echo $this->lang->line('staff'); ?></div>
                                                        <div class="col-md-2"> 
                                                            <div class="material-switch">
                                                            <input id="is_staff" name="is_staff" type="checkbox" class=""
                                                            value="" <?php echo set_checkbox('is_staff', 'enabled', ($setting_result['is_staff']=="enabled")); ?> />
                                                            <label for="is_staff" class="label-info-success"></label>   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        </div> 
                                    </div>
                                </div>
                                <div class="box-footer">
                                  <div class="col-md-offset-3">
                                      <button type="submit" class="btn btn-primary btnleftinfo pull-right"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="drive_loader"></span>
                                  </div>       
                                </div>
                                 
                            </form>
                    </div>
                </div>
            </div>
        </div>  
    </section>
</div>
</div>
</div>

<script type="text/javascript">


$(document).ready(function() {
    $("#is_enable").change(function() {
        // Check if checkbox is checked
        var isChecked = $(this).prop("checked");
        if (isChecked) { // when checkbox is checked
            $("#otheroption").removeClass("hide");
            $("#otheroption").addClass("show");
        } else {
            $("#otheroption").removeClass("show");
            $("#otheroption").addClass("hide");
            $("#is_student").prop("checked", false);
            $("#is_parent").prop("checked", false);
            $("#is_staff").prop("checked", false);
            $("#is_student").prop("checked", false);
        }
    });
});


    var img_path = "<?php echo base_url() . '/backend/images/loading.gif' ?>";
    $("#savegoogledrive").submit(function (e) {
        $("[class$='_error']").html("");
        $(".drive_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        var isChecked           = $("#is_enable").prop("checked");
        var isStudentChecked    = $("#is_student").prop("checked");
        var isParentChecked     = $("#is_parent").prop("checked");
        var isStaffChecked      = $("#is_staff").prop("checked");

        if(isStudentChecked){
            var is_student="enabled";
        }else{
            var is_student="disabled";
        }
        if(isParentChecked){
            var is_parent="enabled";
        }else{
            var is_parent="disabled";
        }
        if(isStaffChecked){
            var is_staff="enabled";
        }else{
            var is_staff="disabled";
        }

        if(isChecked){
            var is_enable="enabled";
        }else{
            var is_enable="disabled";
            var is_student="disabled";
            var is_parent="disabled";
            var is_staff="disabled";
        }       

        var formData = $("#savegoogledrive").serialize();
        formData += '&is_enable='+is_enable; // Add extra key-value pairs
        formData += '&is_student='+is_student; // Add extra key-value pairs
        formData += '&is_parent='+is_parent; // Add extra key-value pairs
        formData += '&is_staff='+is_staff; // Add extra key-value pairs

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: formData, // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".drive_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".drive_loader").html("");
            }
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


 
</script>