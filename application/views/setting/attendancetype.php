<style type="text/css">
    .attendance_section {
            color: #0d6efd;
            ;
            font-size: 15px;
            font-weight: bold;
            padding: 15px 15px 15px 15px;
            margin: 10px 0px 10px 0px;
            background-color: #f5f5f5;
            border-radius: .25rem !important;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
            text-align: center;
            border-radius: .25rem !important;
            /* background-color: #fff !important; */
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }
</style>

<div class="content-wrapper">  
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-lg-9 col-md-8 col-sm-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('attendance_type'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div>
                        <form role="form" id="attendancetype_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('attendance'); ?></label>
                                            <div class="col-sm-8">
                                                <label class="radio-inline">
                                                    <input type="radio" name="attendence_type" value="0" <?php
                                                    if (!$result->attendence_type) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('day_wise'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="attendence_type" value="1" <?php
                                                    if ($result->attendence_type) {
                                                        echo "checked";
                                                    }
                                                    ?>><?php echo $this->lang->line('period_wise'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-8"> <?php echo $this->lang->line('qrcode') .' / '. $this->lang->line('barcode') .' / '. $this->lang->line('biometric_attendance'); ?></label>
                                            <div class="col-sm-4">
                                                 <div class="material-switch">
                                                    <input id="biometric" name="biometric" type="checkbox" class=""
                                                        value="1" <?php echo set_checkbox('biometric', '1', ($result->biometric==1)); ?> />
                                                    <label for="biometric" class="label-info-success"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3"> <?php echo $this->lang->line('devices_separate_by_comma'); ?> </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="name" name="biometric_device" value="<?php echo $result->biometric_device; ?>">
                                                    <span class="text-danger"><?php echo form_error('biometric_device'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3"> <?php echo $this->lang->line('low_attendance_limit'); ?> <i class="fa fa-question-circle cursor-pointer text-sky-blue" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('below_it_attendance_will_be_mark_as_low_attendance');?>"></i></label>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="low_attendance_limit" id="low_attendance_limit" value="<?php echo $result->low_attendance_limit; ?>">
                                                            <div class="input-group-addon">
                                                                <span class="">%</span>
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
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_attendancetype" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box box-primary hide" id="save_class_time_hide_show">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('class_attendance_time_for_auto_attendance_submission'); ?> (<?php echo $this->lang->line('day_wise_with_cron_setting'); ?>)</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <?php  $count=1;
                    if(!empty($class_list)){ ?>
                    <form method="POST" action="<?php echo site_url('admin/stuattendence/saveclasstime');?>" id="form_timetable">               
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkbox mb0 mt0">
                                    <label for="copy_other">
                                        <input class="copy_other" id="copy_other" value="1" type="checkbox" > <?php echo $this->lang->line('copy_first_detail_for_all'); ?>
                                    </label></div>
                                </div>
                            </div>
                        <?php 
    foreach ($class_list as $class_key => $class_value) {
         ?>
   <hr class="hrexam">
         <div class="row block_row">     
                           
                                <div class="col-sm-4 col-lg-4 col-md-4">         
                                    <h4 class="transport_fee_line"><?php echo $class_value['class']; ?></h4>
                                </div>
                                <div class="col-sm-8 col-lg-8 col-md-8">                                    
                                    <div class="row">  

                                        <div class="col-sm-12 col-lg-12 col-md-12">
                                        <?php 
                                        if(!empty($class_value['sections'])){
foreach ($class_value['sections'] as $section_key => $section_value) {   
 ?>
<div class="row">    
     <div class="form-group col-md-6">
    <label class="control-label col-sm-3" for="time"><?php echo $section_value->section ?></label>
    <div class="col-sm-9">
        <div class="input-group">
                                          <input type="text" class="form-control datetimepicker" name="class_section_id[<?php echo $section_value->id;?>]" value
      ="<?php echo ($section_value->time !=0) ? $section_value->time :"" ?>" id="time" placeholder="Enter time">

                                        <div class="input-group-addon">
                                            <span class="fa fa-clock-o"></span>
                                        </div>
                                    </div>
        <input type="hidden" name="row[]" value="<?php echo $count; ?>">
        <input type="hidden" name="prev_record_id[<?php echo $section_value->id;?>]" value="<?php echo $section_value->class_section_times_id; ?>">  
    </div>
  </div>
</div>
 <?php
 $count++;
}

}else{
    ?>
<div class="alert alert-info">
  <?php echo $this->lang->line('no_section_found'); ?>
</div>
    <?php
}
                                         ?>
                                        </div>              
                                    </div>                                              
                                </div>         
                            </div>
                              
                            <?php
    }

                         ?>
                         
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                                                   
                        <button type="submit" class="btn btn-primary pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"> <?php echo $this->lang->line('save') ?></button>
                                
                        </div>
                    </form>
                    <?php } ?>
                </div>
          
				
					<div class="nav-tabs-custom theme-shadow">
						<ul class="nav nav-tabs"  id="myTab">
							<li class="<?php if($classid==0){ echo "active";}else{ echo ""; }  ?>" ><a href="#staff" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('staff'); ?></a></li>
							<li class="<?php if($classid>0 || $classid==""){ echo "active";}  ?>" > <a href="#student" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('student'); ?></a></li>
						</ul>
                        <div class="tab-content pb0">
                        <div class="tab-pane <?php if($classid==0){ echo "active";}else{ echo ""; }  ?>" id="staff">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $this->lang->line('staff_attendance_setting'); ?></h3>
								</div>
								<div class="box-body">
								<?php
									if (!empty($list_attendance)){  ?>
										<div class="row">
											<?php  foreach ($list_attendance as $list_key => $list_value){ ?>
												<div class="col-md-12">
													<form method="POST" action="<?php echo site_url('schsettings/savestaffsetting'); ?>" class="update">
														<div class="panel panel-info">
														<div class="panel-footer panel-fo border-0">
															<div class="row d-flex align-items-center justify-content-between">
																<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
																		<strong>                                                                
																			<?php echo $this->lang->line('role'); ?>: <?php  echo $list_value['role'];  ?>
																		</strong>
																	</div>
																	<div class="col-lg-4 col-md-8 col-sm-6">
																			<button type="submit" class="btn btn-primary btn-sm pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('update'); ?>"><?php echo $this->lang->line('update'); ?></button>
																	</div>
																</div>
															</div>
															<div class="panel-body pr-05 ps-5">
																<div class="row">
																	<div class="col-sm-12 col-lg-12 col-md-12">
																		<div class="col-sm-3 col-lg-3 col-md-3">
																		    <label for="email"><?php echo $this->lang->line('attendance_type'); ?></label>
																		</div>
																		<div class="col-sm-9 col-lg-9 col-md-9">
																			<div class="row">
																				<div class="col-sm-4 col-lg-4 col-md-4">
																					<label for="email"><?php echo $this->lang->line('entry_from'); ?> (hh:mm:ss)</label>
																				</div>
																				<div class="col-sm-4 col-lg-4 col-md-4">
																					<label for="email"><?php echo $this->lang->line('entry_upto'); ?> (hh:mm:ss)</label>
																				</div>
																				<div class="col-sm-4 col-lg-4 col-md-4">
																					<label for="email"><?php echo $this->lang->line('total_hour'); ?></label>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																
																<div class="append_row paddA10">
																	<?php
																	$row = 1;
																	if (!empty($list_value['schedule'])) {
																		$count = 1;
																	?>
																		<div class="row">
																			<div class="col-md-12">
																				<?php
																				if (!empty($attendance_type)) {
																					foreach ($attendance_type as $att_type_key => $att_type_value) {
																						$return_value = get_input_value($list_value['schedule'], $att_type_value->id);
																					
																				?>
																						<input type="hidden" name="row[]" value="<?php echo $row; ?>">
																						<input type="hidden" name="attendance_type_id_<?php echo $row; ?>" value="<?php echo $att_type_value->id; ?>">
																						<input type="hidden" name="role_id_<?php echo $row; ?>" value="<?php echo $list_value['role_id']; ?>">
																						<div class="row">
																							<div class="col-sm-3 col-lg-3 col-md-3">
																								<?php  
                                                                                                echo $this->lang->line($att_type_value->long_lang_name) . " (" . $att_type_value->key_value . ")"; ?>
																							</div>
																							<div class="col-sm-9 col-lg-9 col-md-9">
																								<div class="row">
																									<div class="col-sm-4 col-lg-4 col-md-4">
																										<div class="form-group">
																											
																											<div class="input-group">
																												<input type="text" name="entry_time_from_<?php echo $row; ?>" class="form-control entry_time_from time valid" id="entry_time_from" value="<?php echo $return_value['entry_time_from'] ?>">
																												<div class="input-group-addon">
																													<span class="fa fa-clock-o"></span>
																												</div>
																											</div>
																										</div>
																									</div>
																									<div class="col-sm-4 col-lg-4 col-md-4">
																										<div class="form-group">
																											
																											<div class="input-group">
																												<input type="text" name="entry_time_to_<?php echo $row; ?>" class="form-control entry_time_to time valid" id="time_to" value="<?php echo $return_value['entry_time_to'] ?>">
																												<div class="input-group-addon">
																													<span class="fa fa-clock-o"></span>
																												</div>
																											</div>
																										</div>
																									</div>
																									<div class="col-sm-4 col-lg-4 col-md-4">
																										<div class="form-group">
																											
																											<div class="input-group">
																												<input type="text" name="total_institute_hour_<?php echo $row; ?>" class="form-control total_institute_hour time_hour valid" id="total_institute_hour" value="<?php echo $return_value['total_institute_hour'] ?>">
																												<div class="input-group-addon">
																													<span class="fa fa-clock-o"></span>
																												</div>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>                                                                            
																						</div>
																				<?php
																				$row++;
																				}
																		}
																		?>
																			</div>
																		</div>
																	<?php  $count++;   }  ?>
																</div>
															</div>
														
														</div>
													</form>
												</div>
											<?php   }      ?>
										</div>
									<?php   }    ?>
								</div>
							</div>
						</div>
						<div class="tab-pane <?php if($classid>0 || $classid==""){ echo "active";}  ?>" id="student">
						<div class="box box-primary">
							<div class="box-header with-border">
                                <form method="post" action="<?php echo base_url('schsettings/attendancetype');?>"  >
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <h3 class="box-title ptt10"><?php echo $this->lang->line('student_attendance_setting'); ?></h3>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" onchange="this.form.submit()">
                                        <option value=""><?php echo $this->lang->line('all_classes'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {  ?>
                                        <option value="<?php echo $class['id'] ?>" <?php echo set_select('class_id', $class['id']); ?>><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                        </select>
                                        </div>
                                    </div>
                                </form>

								</div>
                                 <div class="box-body">
                        <?php
                        if (!empty($student_list_attendance)) {
                        ?>
                            <div class="row">
                                <?php
                                foreach ($student_list_attendance as $list_key => $list_value) {
                                ?>
                                    <div class="col-md-12">
                                        <form method="POST" action="<?php echo site_url('admin/stuattendence/savestudentsetting'); ?>" class="student_update">
                                            <div class="panel panel-info">
												<div class="panel-footer panel-fo border-0">
															<div class="row d-flex align-items-center justify-content-between">
																<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
																		<strong>                                                                
																			<?php echo $this->lang->line('class'); ?>: <?php  echo $list_value['class'];  ?>
																		</strong>
																</div>
																	<div class="col-lg-4 col-md-8 col-sm-6">																	<?php if ($this->rbac->hasPrivilege('multi_class_student', 'can_edit')) { ?>
																		<button type="submit" class="btn btn-primary btn-sm pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('update'); ?>"><?php echo $this->lang->line('update'); ?></button>
																		<?php } ?>													
																	</div>
																</div>
															</div>
															
                                                <div class="panel-body panelheight">													
                                                    <div class="append_row paddA10">
                                                        <?php
                                                        $row = 1;
                                                        if (!empty($list_value['sections'])) {
                                                            $count = 1;
                                                            foreach ($list_value['sections'] as $student_session_key => $student_session_value) { ?>
                                                                <div class="row">
                                                                    <div class="col-md-12">																	
																		<h4><center><?php echo $this->lang->line('section'); ?>: <?php echo $student_session_value['section']; ?></center></h4>
																		
																		    <div class="row">													
																				<div class="col-sm-3 col-lg-3 col-md-3">
																					<label for="email"><?php echo $this->lang->line('attendance_type'); ?></label>
																				</div>
																				<div class="col-sm-9 col-lg-9 col-md-9">
																					<div class="row">
																						<div class="col-sm-4 col-lg-4 col-md-4">
																							<label for="email"><?php echo $this->lang->line('entry_from'); ?> (hh:mm:ss)</label>
																						</div>
																						<div class="col-sm-4 col-lg-4 col-md-4">
																							<label for="email"><?php echo $this->lang->line('entry_upto'); ?> (hh:mm:ss)</label>
																						</div>
																						<div class="col-sm-4 col-lg-4 col-md-4">
																							<label for="email"><?php echo $this->lang->line('total_hour'); ?></label>
																						</div>
																					</div>
																				</div>														
																			</div>
																			 
														
                                                                        <?php
                                                                        if (!empty($student_attendance_type)) {
                                                                            foreach ($student_attendance_type as $att_type_key => $att_type_value) {
                                                                                $return_value = get_student_input_value($student_session_value['student_schedule'], $att_type_value->id);?>
                                                                                <input type="hidden" name="row[]" value="<?php echo $row; ?>">
                                                                                <input type="hidden" name="attendance_type_id_<?php echo $row; ?>" value="<?php echo $att_type_value->id; ?>">
                                                                                <input type="hidden" name="class_section_id_<?php echo $row; ?>" value="<?php echo $student_session_value['class_section_id']; ?>">
                                                                                <div class="row">
                                                                                    <div class="col-sm-3 col-lg-3 col-md-3">
                                                                                        <?php echo $this->lang->line($att_type_value->long_lang_name)." (" .$att_type_value->key_value . ")"; ?>
                                                                                    </div>
                                                                                    <div class="col-sm-9 col-lg-9 col-md-9">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                <div class="form-group">                                                                       
                                                                                                    <div class="input-group">
                                                                                                        <input type="text" name="entry_time_from_<?php echo $row; ?>" class="form-control entry_time_from time valid" id="entry_time_from" value="<?php echo $return_value['entry_time_from']?>">
                                                                                                        <div class="input-group-addon">
                                                                                                            <span class="fa fa-clock-o"></span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                <div class="form-group">
                                                                                                    
                                                                                                    <div class="input-group">
                                                                                                        <input type="text" name="entry_time_to_<?php echo $row; ?>" class="form-control entry_time_to time valid" id="time_to" value="<?php echo $return_value['entry_time_to']?>">
                                                                                                        <div class="input-group-addon">
                                                                                                            <span class="fa fa-clock-o"></span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-4 col-lg-4 col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <div class="input-group">
                                                                                                        <input type="text" name="total_institute_hour_<?php echo $row; ?>" class="form-control total_institute_hour time_hour valid" id="total_institute_hour" value="<?php echo $return_value['total_institute_hour']?>">
                                                                                                        <div class="input-group-addon">
                                                                                                            <span class="fa fa-clock-o"></span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
																				


                                                                        <?php

                                                                                $row++; 
                                                                            } 
                                                                        }
                                                                        ?>



                                                                    </div>
                                                                </div>
                                                        <?php
                                                                $count++;
                                                            }
                                                        }
                                                        ?>

                                                    </div>
                                                </div> 
                                            </div>
                                        </form>

                                    </div>

                                <?php
                                }

                                ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
								
							</div>
						</div>

                        </div>
					</div>
                
            </div>
            <!-- staff attandance settings -->


        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->



<?php
function get_input_value($array, $find_time){
    if (!empty($array)) {
        foreach ($array as $array_key => $array_value) {
            if ($array_value->staff_attendence_type_id == $find_time) {
                return [
                    'entry_time_from' => $array_value->entry_time_from,
                    'entry_time_to' => $array_value->entry_time_to,
                    'total_institute_hour' => $array_value->total_institute_hour,                  
                ];
            }
        }
        return [
            'entry_time_from' => '',
            'entry_time_to' => '',
            'total_institute_hour' => '',          
        ];
    }
} ?>

<?php

function get_student_input_value($array, $find_time)
{
    if (!empty($array)) {
        foreach ($array as $array_key => $array_value) {
            if ($array_value->attendence_type_id == $find_time) {
                return [
                    'entry_time_from' => $array_value->entry_time_from,
                    'entry_time_to' => $array_value->entry_time_to,
                    'total_institute_hour' =>$array_value->total_institute_hour,
                    
                ];
            }
        }
        return [
            'entry_time_from' => '',
            'entry_time_to' => '',
            'total_institute_hour' => ''

        ];
    }
}
?>


<script type="text/javascript">
     $('input[type=radio][name=biometric]').change(function() {
        if (this.value == '1') {
            $('#save_class_time_hide_show').removeClass('hide'); 
        }
        else if (this.value == '0') {
             $('#save_class_time_hide_show').addClass('hide');   
        }
    }); 
     
    window.onload = function(){  
        var biometric = '<?php echo $result->biometric; ?>';  
        if(biometric == '1'){
            $('#save_class_time_hide_show').removeClass('hide'); 
        }else if(biometric == '0'){
            $('#save_class_time_hide_show').addClass('hide');   
        }
    }  
</script> 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_attendancetype").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/saveattendancetype") ?>',
            type: 'POST',
            data: $('#attendancetype_form').serialize(),
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
                    location.reload();
                }

                $this.button('reset');
            }
        });
    });

</script>

<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
      format: 'hh:mm A',
});

$(document).on('submit','#form_timetable',function(e){

    // this is the id of the form


    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = form.attr('action');
      var submit_button = form.find(':submit');
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        dataType: "JSON", // serializes the form's elements.
                    beforeSend: function () {

                        submit_button.button('loading');
                    },
                    success: function (data)
                    {

                        var message = "";
                        if (!data.status) {

                            $.each(data.error, function (index, value) {

                                message += value;
                            });

                            errorMsg(message);

                        } else {
                            successMsg(data.message);
                           
                        }
                    },
                    error: function (xhr) { // if error occured
                        submit_button.button('reset');
                        alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                    },
                    complete: function () {
                        submit_button.button('reset');
                    }
                });    
            });


     $(document).on('change','.copy_other',function(){
        if(this.checked) {           
            var first_due= $('form#form_timetable').find('input.datetimepicker').filter(':visible:first').val();          
            $('form#form_timetable').find('.datetimepicker').val(first_due);  
            
        }
    });
</script>



<script type="text/javascript">
    //****staff attendance settings****//
    $(function() {
        $('.time').datetimepicker({
            format: 'HH:mm:ss'
        });
    });
    $(function() {
        $('.time_hour').datetimepicker({
            format: 'HH:mm:ss'
        });
    });

    $(document).on('submit', '.update', function(e) {
        var submit_btn = $(this).find("button[type=submit]");
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            dataType: "json",
            beforeSend: function() {
                submit_btn.button('loading');
            },
            success: function(data) {
                if (data.status == 1) {
                    successMsg(data.message);
                } else {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                }
                submit_btn.button('reset');
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {
                submit_btn.button('reset');
            }
        });
    });

      $(document).on('submit', '.student_update', function(e) {
        var submit_btn = $(this).find("button[type=submit]");
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            dataType: "json",
            beforeSend: function() {
                submit_btn.button('loading');
            },
            success: function(data) {
                if (data.status == 1) {
                    successMsg(data.message);
                } else {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                }
                submit_btn.button('reset');
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {
                submit_btn.button('reset');
            }
        });
    });

</script>
