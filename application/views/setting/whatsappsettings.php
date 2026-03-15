<div class="content-wrapper" style="min-height: 348px;">   
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-lg-9 col-md-8 col-sm-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('whatsapp_settings'); ?></h3>
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
												<h4 class="session-head"><?php echo $this->lang->line('front_site'); ?></h4>
											</div>
										</div><!--./col-md-12-->
										<div class="col-md-12">
											<div class="col-md-12">
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('whatsapp_link'); ?> </label>
													<div class="col-sm-8">
														<div class="material-switch">
                                                    		<input id="front_side_whatsapp" name="front_side_whatsapp" type="checkbox" class=""
                                                        	value="1" <?php echo set_checkbox('front_side_whatsapp', '1', ($result->front_side_whatsapp==1)); ?> />
                                                    		<label for="front_side_whatsapp" class="label-info-success"></label>
                                               			</div>													 

													</div>
												</div>
										
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('mobile_no'); ?></label>
													<div class="col-sm-4">
														<input type="text" name="front_side_whatsapp_mobile" id="front_side_whatsapp_mobile" class="form-control" value="<?php echo $result->front_side_whatsapp_mobile; ?>">
														<span class="text-danger"><?php echo form_error('front_side_whatsapp_mobile'); ?></span>
													</div>
												</div>
										
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('time'); ?></label>
													<div class="col-sm-2">
														<input type="text" name="front_side_whatsapp_from" id="front_side_whatsapp_from" class="form-control time_hour" value="<?php echo $result->front_side_whatsapp_from; ?>" placeholder = "<?php echo $this->lang->line('from'); ?>">
														<span class="text-danger"><?php echo form_error('front_side_whatsapp_from'); ?></span>
													</div>
													
													<div class="col-sm-2">
														<input type="text" name="front_side_whatsapp_to" id="front_side_whatsapp_to" class="form-control time_hour" value="<?php echo $result->front_side_whatsapp_to; ?>" placeholder = "<?php echo $this->lang->line('to'); ?>">
														<span class="text-danger"><?php echo form_error('front_side_whatsapp_to'); ?></span>
													</div>
												</div>											
												
											</div>
										</div>
									</div><!--./row-->
									
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<div class="settinghr"></div>
												<h4 class="session-head"><?php echo $this->lang->line('admin_panel'); ?></h4>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('whatsapp_link'); ?> </label>
													<div class="col-sm-8">
														<div class="material-switch">
                                                    		<input id="admin_panel_whatsapp" name="admin_panel_whatsapp" type="checkbox" class=""
                                                        	value="1" <?php echo set_checkbox('admin_panel_whatsapp', '1', ($result->admin_panel_whatsapp==1)); ?> />
                                                    		<label for="admin_panel_whatsapp" class="label-info-success"></label>
                                               			</div>												
													</div>
												</div>
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('mobile_no'); ?></label>
													<div class="col-sm-4">
														<input type="text" name="admin_panel_whatsapp_mobile" id="admin_panel_whatsapp_mobile" class="form-control" value="<?php echo $result->admin_panel_whatsapp_mobile; ?>">
														<span class="text-danger"><?php echo form_error('admin_panel_whatsapp_mobile'); ?></span>
													</div>
												</div>
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('time'); ?></label>
													<div class="col-sm-2">
														<input type="text" name="admin_panel_whatsapp_from" id="admin_panel_whatsapp_from" class="form-control time_hour" value="<?php echo $result->admin_panel_whatsapp_from; ?>" placeholder = "<?php echo $this->lang->line('from'); ?>">
														<span class="text-danger"><?php echo form_error('admin_panel_whatsapp_from'); ?></span>
													</div>
													<div class="col-sm-2">
														<input type="text" name="admin_panel_whatsapp_to" id="admin_panel_whatsapp_to" class="form-control time_hour" value="<?php echo $result->admin_panel_whatsapp_to; ?>" placeholder = "<?php echo $this->lang->line('to'); ?>">
														<span class="text-danger"><?php echo form_error('admin_panel_whatsapp_to'); ?></span>
													</div>
												</div>												
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<div class="settinghr"></div>
												<h4 class="session-head"><?php echo $this->lang->line('student_guardian_panel'); ?></h4>
											</div>
										</div><!--./col-md-12-->
										<div class="col-md-12">
											<div class="col-md-12">
											
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('whatsapp_link'); ?> </label>
													<div class="col-sm-8">
														<div class="material-switch">
                                                    		<input id="student_panel_whatsapp" name="student_panel_whatsapp" type="checkbox" class=""
                                                        	value="1" <?php echo set_checkbox('student_panel_whatsapp', '1', ($result->student_panel_whatsapp==1)); ?> />
                                                    		<label for="student_panel_whatsapp" class="label-info-success"></label>
                                               			</div>		
													</div>
												</div>
										
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('mobile_no'); ?></label>
													<div class="col-sm-4">
														<input type="text" name="student_panel_whatsapp_mobile" id="student_panel_whatsapp_mobile" class="form-control" value="<?php echo $result->student_panel_whatsapp_mobile; ?>">
														<span class="text-danger"><?php echo form_error('student_panel_whatsapp_mobile'); ?></span>
													</div>
												</div>
										
												<div class="form-group row">
													<label class="col-sm-4"><?php echo $this->lang->line('time'); ?></label>
													<div class="col-sm-2">
														<input type="text" name="student_panel_whatsapp_from" id="student_panel_whatsapp_from" class="form-control time_hour" value="<?php echo $result->student_panel_whatsapp_from; ?>" placeholder = "<?php echo $this->lang->line('from'); ?>">
														<span class="text-danger"><?php echo form_error('student_panel_whatsapp_from'); ?></span>
													</div>
												
													<div class="col-sm-2">
														<input type="text" name="student_panel_whatsapp_to" id="student_panel_whatsapp_to" class="form-control time_hour" value="<?php echo $result->student_panel_whatsapp_to; ?>" placeholder = "<?php echo $this->lang->line('to'); ?>">
														<span class="text-danger"><?php echo form_error('student_panel_whatsapp_to'); ?></span>
													</div>
												</div>												
												
											</div>
										</div>
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
							</div><!-- /.box-body -->
						</form>						
					</div>
				</div><!--/.col (left) -->
            <!-- right column -->
			</div>
		</div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

 

<script type="text/javascript">
	 
    $(function() {
        $('.time_hour').datetimepicker({
            format: 'HH:mm:ss'
        });
    });
</script >

 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_miscellaneous").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savewhatsappsettings") ?>',
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
					window.location.reload(true);
                }

                $this.button('reset');
            }
        });
    });
</script>