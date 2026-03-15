<div class="content-wrapper">      
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-lg-9 col-md-8 col-sm-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo  $this->lang->line('student_guardian_panel'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="chatsetting_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4"> <?php echo $this->lang->line('allow_student_to_delete_chat'); ?></label>
                                                    <div class="col-sm-8">
                                                        <div class="material-switch">
                                                            <input id="student_delete_chat" name="student_delete_chat" type="checkbox" class=""
                                                            value="1" <?php echo set_checkbox('student_delete_chat', '1', ($result->student_delete_chat==1)); ?> />
                                                            <label for="student_delete_chat" class="label-info-success"></label>
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
                                                    <label class="col-sm-4"> <?php echo $this->lang->line('allow_guardian_to_delete_chat'); ?></label>
                                                    <div class="col-sm-8">
                                                        <div class="material-switch">
                                                            <input id="guardian_delete_chat" name="guardian_delete_chat" type="checkbox" class=""
                                                            value="1" <?php echo set_checkbox('guardian_delete_chat', '1', ($result->guardian_delete_chat==1)); ?> />
                                                            <label for="guardian_delete_chat" class="label-info-success"></label>
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
                                                    <label class="col-sm-4"> <?php echo $this->lang->line('allow_staff_to_delete_chat'); ?></label>
                                                    <div class="col-sm-8">
                                                         <div class="material-switch">
                                                            <input id="staff_delete_chat" name="staff_delete_chat" type="checkbox" class=""
                                                            value="1" <?php echo set_checkbox('staff_delete_chat', '1', ($result->staff_delete_chat==1)); ?> />
                                                            <label for="staff_delete_chat" class="label-info-success"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_student_guardian" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_student_guardian").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savechatsetting") ?>',
            type: 'POST',
            data: $('#chatsetting_form').serialize(),
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