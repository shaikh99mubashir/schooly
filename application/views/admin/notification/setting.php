<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- left column -->
                <form id="form1" action="<?php echo site_url('admin/notification/setting') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('notification_setting'); ?></h3>
                        </div>
                        <div class="around10">
                            <?php
								if($this->session->flashdata('msg')) {  
									echo $this->session->flashdata('msg');
									$this->session->unset_userdata('msg'); 
								}
							?>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body pt0">
                            <!-- Button HTML (to Trigger Modal) -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <th><?php echo $this->lang->line('event'); ?></th>
                                        <th><?php echo $this->lang->line('destination'); ?></th>
                                        <th><?php echo $this->lang->line('recipient'); ?></th>
                                        <th><?php echo $this->lang->line('template_id'); ?></th> 
										
										<?php if ($this->module_lib->hasModule('whatsapp_messaging') && $this->module_lib->hasActive('whatsapp_messaging')) { ?>
                                        <th><?php echo $this->lang->line('whatsapp_template_id'); ?></th>
										<?php } ?>
										
                                        <th><?php echo $this->lang->line('sample_message'); ?></th>
                                    </thead>
                                    <tbody>

                                        <?php
$i        = 1;
$last_key = count($notificationlist);
foreach ($notificationlist as $note_key => $note_value) {
    $hr = "";

    if ($i != $last_key) {
        $hr = "<hr>";
    }
    ?>

                                            <tr>
                                                <td width="15%">
                                                    <input type="hidden" name="ids[]" value="<?php echo $note_value->id; ?>">
                                                    <?php echo $this->lang->line($note_value->type); ?>
                                                </td>
                                                <td width="10%">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="mail_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('mail_' . $note_value->id, 1, set_value('mail_' . $note_value->id, $note_value->is_mail) ? true : false); ?>> <?php echo $this->lang->line('email'); ?>
                                                    </label>
                                                    <br>
                                                    <?php
if ($note_value->display_sms) {
        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="sms_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('sms_' . $note_value->id, 1, set_value('sms_' . $note_value->id, $note_value->is_sms) ? true : false); ?>>
                                                            <?php echo $this->lang->line('sms'); ?>
                                                        </label>
                                                        <?php
}?>
                                                    <br>
                                                    <?php if ($note_value->display_notification) {
        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="notification_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('notification_' . $note_value->id, 1, set_value('notification_' . $note_value->id, $note_value->is_notification) ? true : false); ?>>
                                                            <?php echo $this->lang->line('mobile_app') ?>
                                                        </label>
                                                        <?php
}
    ?>
									<br>
									
									<?php if ($this->module_lib->hasModule('whatsapp_messaging') && $this->module_lib->hasActive('whatsapp_messaging')) { ?>
	<?php if ($note_value->display_whatsapp) {
        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="whatsapp_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('whatsapp_' . $note_value->id, 1, set_value('whatsapp_' . $note_value->id, $note_value->is_whatsapp) ? true : false); ?>>
                                                            <?php echo $this->lang->line('whatsapp') ?>
                                                        </label>
                                                        <?php
									} }
    ?>
                                                </td>
                                                <td  width="10%"><?php
if ($note_value->display_student_recipient) {
        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="student_recipient_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('student_recipient_' . $note_value->id, 1, set_value('student_recipient_' . $note_value->id, $note_value->is_student_recipient) ? true : false); ?>>
                                                            <?php echo $this->lang->line('student'); ?>
                                                        </label> <br>
                                                        <?php
}?>

                                                    <?php if ($note_value->display_guardian_recipient) {
        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="guardian_recipient_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('guardian_recipient_' . $note_value->id, 1, set_value('guardian_recipient_' . $note_value->id, $note_value->is_guardian_recipient) ? true : false); ?>>
                                                            <?php echo $this->lang->line('guardian') ?>
                                                        </label>
                                                        <br>
                                                        <?php
}
    ?>
                                                    <?php if ($note_value->display_staff_recipient) {
        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="staff_recipient_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('staff_recipient_' . $note_value->id, 1, set_value('staff_recipient_' . $note_value->id, $note_value->is_staff_recipient) ? true : false); ?>>
                                                            <?php echo $this->lang->line('staff') ?>
                                                        </label>
                                                        <?php
}
    ?></td>
                                                <td width="10%"> <?php
if (!empty($note_value)) {
        echo $note_value->template_id;
    }
    ?></td>
	<?php if ($this->module_lib->hasModule('whatsapp_messaging') && $this->module_lib->hasActive('whatsapp_messaging')) { ?>
	<td width="10%"> <?php
if (!empty($note_value)) {
        echo $note_value->whatsapp_template_id;
    }
    ?></td><?php } ?>
                                                <td >
                                                    <?php
if (!empty($note_value)) {
        echo $note_value->template;
    }
    ?>
                                                    <br/>
                                                    <?php if ($this->rbac->hasPrivilege('notification_setting', 'can_edit')) {?>
                                                    <button type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" class="button_template btn btn-primary btn-xs" id="load" data-record-id="<?php echo $note_value->id; ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-pencil-square-o"></i></button>
                                                    <?php }?>
													
													<button type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" class="view_template btn btn-primary btn-xs" id="load" data-record-id="<?php echo $note_value->id; ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-reorder"></i></button>
													
                                                </td>
                                            </tr>
                                            <?php
$i++;
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <?php if ($this->rbac->hasPrivilege('notification_setting', 'can_edit')) {
    ?>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            <?php }
?>
                        </div>
                </form>
            </div>
        </div>
</div><!--./wrapper-->

</section><!-- /.content -->
</div>

<div class="modal fade" id="templateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo site_url('admin/notification/savetemplate') ?>" method="post" id="templateForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"> <?php echo $this->lang->line('template'); ?></h4>
                </div>
                <div class="modal-body template_modal_body">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="template_update btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewtemplateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"> <?php echo $this->lang->line('template'); ?></h4>
                </div>
                <div class="modal-body template_view_body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary close" id="load" aria-hidden="true" > </button>
                </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
        $(document).on('click', '.view_template', function () {
            $('.template_message_error').html("");
            var $this = $(this);
            var id = $this.data('recordId');
            $this.button('loading');
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: baseurl + "admin/notification/view_template",
                data: {'id': id},
                beforeSend: function () {
                },
                success: function (data) {
                    if (data.status) {
                        $('#viewtemplateModal').modal('show');
                        $('.template_view_body').html(data.template);
                    }
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });
    });
	
    $(document).ready(function () {
        $(document).on('click', '.button_template', function () {
            $('.template_message_error').html("");
            var $this = $(this);
            var id = $this.data('recordId');
            $this.button('loading');
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: baseurl + "admin/notification/gettemplate",
                data: {'id': id},
                beforeSend: function () {
                },
                success: function (data) {
                    if (data.status) {
                        $('#templateModal').modal('show');
                        $('.template_modal_body').html(data.template);
                    }
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });
    });

    $("#templateForm").submit(function (e) {
        $('.template_message_error').html("");
        var submit_btn = $(this).find("button[type=submit]:focus");
        var form = $(this);
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function () {
                submit_btn.button('loading');
            },
            success: function (data) {
                if (data.status) {
                    successMsg(data.message);
                    window.location.reload(true);
                } else {
                    $.each(data.error, function (key, val) {
                        $('.' + key + '_error').html(val);

                    });
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                submit_btn.button('reset');
            },
            complete: function () {
                submit_btn.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
</script>