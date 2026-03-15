<div class="content-wrapper" style="min-height: 348px;">   
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-lg-9 col-md-8 col-sm-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('miscellaneous'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="miscellaneous_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="row">
                                    <div class="col-md-12">
                                    <div class="col-md-12">                                        
                                        <h4 class="session-head"><?php echo $this->lang->line('online_examination'); ?></h4>
                                    </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-5"><?php echo $this->lang->line('show_me_only_my_question'); ?></label>
                                            <div class="col-sm-7">                                               

                                                <div class="material-switch">
                                                    <input id="my_question" name="my_question" type="checkbox" class=""
                                                        value="1" <?php echo set_checkbox('my_question', '1', ($result->my_question==1)); ?> />
                                                    <label for="my_question" class="label-info-success"></label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="settinghr"></div>
                                            <h4 class="session-head"><?php echo $this->lang->line('id_card_scan_code'); ?></h4>
                                        </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-5"><?php echo $this->lang->line('scan_type'); ?></label>
                                                <div class="col-sm-7">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="scan_code_type" value="barcode" <?php
                                                        if ($result->scan_code_type == "barcode") {
                                                            echo "checked";
                                                        }
                                                        ?>  ><?php echo $this->lang->line('barcode'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="scan_code_type" value="qrcode" <?php
                                                        if ($result->scan_code_type == "qrcode") {
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('qrcode'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->                                     
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="settinghr"></div>
                                            <h4 class="session-head">
                                                <?php echo $this->lang->line('examinations'); ?></h4>
                                        </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-5"><?php echo $this->lang->line('exam_result_page_in_front_site'); ?></label>
                                                <div class="col-sm-7">                                                 

                                                    <div class="material-switch">
                                                    <input id="exam_result" name="exam_result" type="checkbox" class=""
                                                        value="1" <?php echo set_checkbox('exam_result', '1', ($result->exam_result==1)); ?> />
                                                    <label for="exam_result" class="label-info-success"></label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-5"><?php echo $this->lang->line('download_admit_card_in_student_parent_panel'); ?></label>
                                                <div class="col-sm-7">                                                    

                                                    <div class="material-switch">
                                                    <input id="download_admit_card" name="download_admit_card" type="checkbox" class=""
                                                        value="1" <?php echo set_checkbox('download_admit_card', '1', ($result->download_admit_card==1)); ?> />
                                                    <label for="download_admit_card" class="label-info-success"></label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->                                     
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="settinghr"></div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-5"><?php echo $this->lang->line('teacher_restricted_mode'); ?></label>
                                            <div class="col-sm-7">                                               

                                                <div class="material-switch">
                                                    <input id="class_teacher" name="class_teacher" type="checkbox" class=""
                                                        value="yes" <?php echo set_checkbox('class_teacher', 'yes', ($result->class_teacher=='yes')); ?> />
                                                    <label for="class_teacher" class="label-info-success"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-5"> <?php echo $this->lang->line('superadmin_visibility'); ?></label>
                                            <div class="col-sm-7">
                                                <div class="material-switch">
                                                    <input id="superadmin_restriction_mode" name="superadmin_restriction_mode" type="checkbox" class=""
                                                        value="enabled" <?php echo set_checkbox('superadmin_restriction', 'enabled', ($result->superadmin_restriction=='enabled')); ?> />
                                                    <label for="superadmin_restriction_mode" class="label-info-success"></label>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>                                
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-5"><?php echo  $this->lang->line('event_reminder'); ?></label>
                                            <div class="col-sm-7" id="radioBtnDiv"> 
                                                <div class="material-switch">
                                                    <input id="event_reminder" name="event_reminder" type="checkbox" class=""
                                                        value="enabled" <?php echo set_checkbox('event_reminder', 'enabled', ($result->event_reminder=='enabled')); ?> />
                                                                                                       
                                                        <label for="event_reminder" class="label-info-success"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 hide" id="reminder_before_days">
                                        <div class="form-group row">
                                            <label class="col-sm-5"><?php echo $this->lang->line('calendar_event_reminder_before_days'); ?></label>
                                            <div class="col-sm-7">
                                                <input type="number" name="calendar_event_reminder" id="calendar_event_reminder" class="form-control" value="<?php echo $result->calendar_event_reminder; ?>">
                                                <span class="text-danger"><?php echo form_error('calendar_event_reminder'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-5"><?php echo $this->lang->line('staff_apply_leave_notification_email'); ?></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="staff_notification_email" id="staff_notification_email" class="form-control" value="<?php echo $result->staff_notification_email; ?>">
                                                    <span class="text-danger"><?php echo form_error('staff_notification_email'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>                                  

                                   <div class="row">
                                    <div class="col-md-12">
                                     <div class="col-md-12">
                                        <div class="settinghr"></div>
                                            <h4 class="session-head"><?php echo $this->lang->line('multi_class'); ?></h4>
                                        </div>
                                     </div>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-5"><?php echo $this->lang->line('enable_multi_class_selection_in_student_admission_form'); ?></label>
                                                <div class="col-sm-7">
                                                    <div class="material-switch">
                                                    <input id="student_form_multi_class" name="student_form_multi_class" type="checkbox" class=""
                                                        value="enabled" <?php echo set_checkbox('student_form_multi_class', 'enabled', ($result->student_form_multi_class=='enabled')); ?> />
                                                    <label for="student_form_multi_class" class="label-info-success"></label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>                                   
                                    </div>
                                </div><!--./row-->      
                                <!-- =============================================== -->

                                </div><!--./row--> 
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_miscellaneous" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $("input[name='event_reminder']").change(function () {
    if ($(this).is(':checked') && $(this).val() === 'enabled') {
        $('#reminder_before_days').removeClass('hide');
    } else {
        $('#reminder_before_days').addClass('hide');
    }
});

// On page load
window.onload = function () {
    var eventReminder = document.getElementById('event_reminder');
    if (eventReminder.checked && eventReminder.value === 'enabled') {
        document.getElementById('reminder_before_days').classList.remove('hide');
    } else {
        document.getElementById('reminder_before_days').classList.add('hide');
    }
} 
</script> 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_miscellaneous").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savemiscellaneous") ?>',
            type: 'POST',
            data: $('#miscellaneous_form').serialize(),
            dataType: 'json',

            success: function (data) {

                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);                   
                }

                $this.button('reset');
            }
        });
    });
</script>