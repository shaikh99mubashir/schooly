 <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css"> 
 
   <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  </head>

  <body>
<div style="width: 100%; margin: 0 auto">
    <div class="row header ">
        <div class="col-sm-12">       
        <img src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/general_purpose/'.$this->setting_model->get_general_purpose_header()); ?>" style="height: 100px;width: 100%;">    
        </div>
    </div> 

 <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td valign="top" width="100%" >
        <table cellpadding="0" cellspacing="5" width="100%">
            <tr>
            <?php 
			if (!empty($student["image"])) {
				$image_url = $this->media_storage->getImageURL($student["image"]);
			} else {
				if ($student['gender'] == 'Female') {
					$image_url = $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
				} else {
					$image_url = $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
				}
			} 
			?>           
                <td width="80" style="text-align: left;">
                    <img src="<?php echo $image_url; ?>" width="65" height="65" style="border-radius: 100%; width: 65px; height: 65px;">
                </td>

            <td valign="middle">
          <h3 ><?php
if ($sch_setting->lastname) {
$lastname=$student['lastname'];
$sch_setting_lastname=$sch_setting->lastname;
}else{
$lastname="";
$sch_setting_lastname="";   
} 
echo $student_name=$this->customlib->getFullName($student['firstname'],$student['middlename'],$lastname,$sch_setting->middlename,$sch_setting_lastname);
?></h3>
         </td></tr>
         <tr><td valign="top" colspan="2" height="10" style="border-bottom:1px solid #999;"></td></tr>
        </table>
      </td>
     </tr>
	 <tr>
        <td valign="top">
            <table cellpadding="0" cellspacing="5" width="100%">
	 
		      <tbody>
                                   
                                            <tr>
                                                <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('admission_no'); ?></td>
                                                <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['admission_no']; ?></td>
                                            </tr>
                                            

                                            <?php if ($sch_setting->roll_no) {  ?>
                                                <tr>
                                                    <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('roll_no'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['roll_no']; ?></td>

                                                </tr>
                                            <?php } ?>


                                            <?php if ($sch_setting->admission_date) { ?>
                                                <tr>
                                                    <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('admission_date'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span>
                                                        <?php
                                                        if (!empty($student['admission_date'])) {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($student['admission_date']))));
                                                        }
                                                        ?></td>
                                                </tr>
                                            <?php } ?>


                                            <tr>
                                                <td style="font-size:12px;"><?php echo $this->lang->line('date_of_birth'); ?></td>
                                                <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php
                                                    if(!empty($student['dob']) && $student['dob'] != '0000-00-00') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                    }
                                                    ?></td>
                                            </tr>

                                            <?php if ($sch_setting->category) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('category'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span>
                                                        <?php
                                                        foreach ($category_list as $value) {
                                                            if ($student['category_id'] == $value['id']) {
                                                                echo $value['category'];
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($sch_setting->mobile_no) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('mobile_number'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['mobileno']; ?></td>
                                                </tr>
                                            <?php } ?>
                                           
                                            <?php if ($sch_setting->cast) {  ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('caste'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['cast']; ?></td>
                                                </tr>
                                            <?php  } ?>
                                            <?php if ($sch_setting->religion) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('religion'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['religion']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            
                                            <?php if ($sch_setting->student_email) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('email'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['email']; ?></td>
                                                </tr>
                                            <?php }  ?>

                                            <!-- custom fields space -->
                                            <!-- custom fields space -->

                                            <?php if($sch_setting->student_note){  ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('note'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['note']; ?></td>
                                                </tr>
                                            <?php  }  ?>
											
											<?php if ($sch_setting->current_address) { ?>
                                                <tr>
                                                    <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('current_address'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['current_address']; ?></td>
                                                </tr>
                                            <?php } ?>
                                           
                                            <?php if ($sch_setting->permanent_address) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('permanent_address'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['permanent_address']; ?></td>
                                                </tr>
                                            <?php } ?>
											
											<?php if ($sch_setting->father_name) {  ?>
                                                <tr>
                                                    <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('father_name'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['father_name']; ?></td>
                                                    <td rowspan="3" style="text-align: right;"><img class="profile-user-img img-responsive img-rounded" width="65" height="65" src="<?php
                                                        if (!empty($student["father_pic"])) {
                                                            echo $this->media_storage->getImageURL($student["father_pic"]);
                                                        } else {
                                                            echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                        }  ?>" /></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($sch_setting->father_phone) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('father_phone'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['father_phone']; ?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php if ($sch_setting->father_occupation) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('father_occupation'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['father_occupation']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if($sch_setting->mother_name){ ?>
                                                <tr class="bordertop" style="border-top:1px solid #ddd;">
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('mother_name'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['mother_name']; ?></td>
                                                    <td rowspan="3" style="text-align: right;"><img class="profile-user-img img-responsive img-rounded" width="65" height="65" src="<?php                                                                                                                                 if (!empty($student["mother_pic"])) {
                                                            echo $this->media_storage->getImageURL($student["mother_pic"]);
                                                        } else {
                                                            echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                        }
                                                    ?>"></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($sch_setting->mother_phone) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('mother_phone'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['mother_phone']; ?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php if ($sch_setting->mother_occupation) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('mother_occupation'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['mother_occupation']; ?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php if ($sch_setting->guardian_name) { ?>
                                                <tr class="bordertop" style="border-top:1px solid #ddd;">
                                                <td style="font-size:12px;"><?php echo $this->lang->line('guardian_name'); ?></td>
                                            <?php  } ?>

                                            <?php if($sch_setting->guardian_name){ ?>
                                                <td style="font-size:12px;"><?php echo $student['guardian_name']; } ?></td>
                                                <td rowspan="3" style="text-align: right;">
                                                    <?php 
                                                    if ($sch_setting->guardian_pic) {  ?>
                                                        <img class="profile-user-img img-responsive img-rounded"  width="65" height="65" src="<?php
                                                            if (!empty($student["guardian_pic"])) {
                                                                echo $this->media_storage->getImageURL($student["guardian_pic"]);
                                                            } else {
                                                                echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                            }
                                                            ?>"> 
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                            <?php if ($sch_setting->guardian_email) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('guardian_email'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['guardian_email']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_relation) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('guardian_relation'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['guardian_relation']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_phone) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('guardian_phone'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['guardian_phone']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_occupation) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('guardian_occupation'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['guardian_occupation']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_address) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('guardian_address'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['guardian_address']; ?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php if ($sch_setting->route_list) {  ?>
											<tr>
                                                            <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('pick_up_point'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['pickup_point_name']; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size:12px;"><?php echo $this->lang->line('route'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['route_title']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:12px;"><?php echo $this->lang->line('vehicle_number'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['vehicle_no']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:12px;"><?php echo $this->lang->line('driver_name'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['driver_name']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:12px;"><?php echo $this->lang->line('driver_contact'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['driver_contact']; ?></td>
                                                        </tr>

                                            <?php } ?>
                                           
                                            <?php if ($sch_setting->hostel_id) {
                                                if ($this->module_lib->hasActive('hostel')) {
                                                    if ($student['hostel_room_id'] != 0) {  ?>
														<tr>
                                                            <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('hostel'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['hostel_name']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:12px;"><?php echo $this->lang->line('room_no'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['room_no']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:12px;"><?php echo $this->lang->line('room_type'); ?></td>
                                                            <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['room_type']; ?></td>
                                                        </tr>


                                            <?php } } } ?>
												
                                            <?php if ($sch_setting->is_blood_group) { ?>
                                                <tr>
                                                    <td width="50%" style="font-size:12px;"><?php echo $this->lang->line('blood_group'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['blood_group']; ?></td>
                                                </tr>
                                            <?php } ?>

                                           <?php if ($sch_setting->is_student_house) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('house'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['house_name']; ?></td>
                                                </tr>
                                            <?php } ?>


                                            <?php if ($sch_setting->student_height) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('height'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['height']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($sch_setting->student_weight) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('weight'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['weight']; ?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php if ($sch_setting->measurement_date){ ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('measurement_date'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php
                                                        if (!empty($student['measurement_date']) && $student['measurement_date'] != '0000-00-00') {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['measurement_date']));
                                                        }
                                                        ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($sch_setting->previous_school_details) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('previous_school_details'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['previous_school']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->national_identification_no) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('national_identification_number'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['adhar_no']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->local_identification_no) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('local_identification_number'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['samagra_id']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->bank_account_no) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('bank_account_number'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['bank_account_no']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->ifsc_code) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('bank_name'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['bank_name']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->ifsc_code) { ?>
                                                <tr>
                                                    <td style="font-size:12px;"><?php echo $this->lang->line('ifsc_code'); ?></td>
                                                    <td style="font-size:12px;"><span style="padding-right:5px;"> : </span><?php echo $student['ifsc_code']; ?></td>
                                                </tr>
                                            <?php } ?>


                                            <?php 
                                            /* 
                                            $cutom_fields_data = get_custom_table_values($student['id'], 'students');
                                            if (!empty($cutom_fields_data)) {
                                                foreach ($cutom_fields_data as $field_key => $field_value) { ?>
                                                    <tr>
                                                        <td style="font-size:12px;"><?php echo $field_value->name; ?></td>
                                                        <td style="font-size:12px;"><span style="padding-right:5px;"> : </span>
                                                            <?php
                                                            if (is_string($field_value->field_value) && is_array(json_decode($field_value->field_value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                                                                $field_array = json_decode($field_value->field_value);
                                                                echo "<ul class='student_custom_field'>";
                                                                foreach ($field_array as $each_key => $each_value) {
                                                                    echo "<li>" . $each_value . "</li>";
                                                                }
                                                                echo "</ul>";
                                                            } else {
                                                                $display_field = $field_value->field_value;
                                                                if ($field_value->type == "link") {
                                                                    $display_field = "<a href=" . $field_value->field_value . " target='_blank'>" . $field_value->field_value . "</a>";
                                                                }
                                                                echo $display_field;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            }  */
                                            ?>



                                        </tbody>
                                    </table>
                                    </td>
                                    </tr>    
    </table>

    <div class="row header ">
        <div class="col-sm-12">
            <?php echo $this->setting_model->get_general_purpose_footer(); ?>
        </div>
    </div>  
</div>
</html>