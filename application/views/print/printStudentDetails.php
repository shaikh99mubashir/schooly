 <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css"> 
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	 
</head>
<body>

<div style="width: 100%; margin: 0 auto; text-align: left;">
  <div style="border-bottom:1px solid #ddd;"><img src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/general_purpose/'.$this->setting_model->get_general_purpose_header()); ?>" style="width:100%; height: auto;"></div>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr><td valign="top" class="pt-20 pb-20 text-center"><h2 class="heading-h2">Student Details</h2></td></tr>
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
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
            <th valign="top" width="30%"><?php echo $this->lang->line('name'); ?></th>
            <td valign="top" width="60%">
				<?php
					if ($sch_setting->lastname) {
						$lastname=$student['lastname'];
						$sch_setting_lastname=$sch_setting->lastname;
					}else{
						$lastname="";
						$sch_setting_lastname="";
					} 
					echo $student_name=$this->customlib->getFullName($student['firstname'],$student['middlename'],$lastname,$sch_setting->middlename,$sch_setting_lastname);
				?>
			</td> <?php if ($sch_setting->roll_no) {  $row_span=3; }else{ $row_span=2;} ?>
            <td valign="top" align="center" rowspan="<?php echo $row_span;?>" width="120"><img src="<?php echo $image_url; ?>" style="width:65px; height: 65px;" /></td>
          </tr>		  
           <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('admission_no'); ?></th>
            <td valign="top" width="60%"><?php echo $student['admission_no']; ?></td>			
          </tr>
		  <?php if ($sch_setting->roll_no) {  ?>
           <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('roll_no'); ?></th>
            <td valign="top" width="60%"><?php echo $student['roll_no']; ?></td>
          </tr>
		  <?php } ?>		  
		  <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('class'); ?></th>
            <td valign="top" width="60%"><?php echo $student['class']; ?></td>
			<td valign="top" align="center" rowspan="3" width="120"><?php if ($sch_setting->student_barcode == 1) { ?><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student['id'] . '.png'); ?>" style="width:65px; height: 65px;" /><?php } ?></td>
          </tr>
		  <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('section'); ?></th>
            <td valign="top" width="60%"><?php echo $student['section']; ?></td>
          </tr>
		  <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('gender'); ?></th>
            <td valign="top" width="60%"><?php echo $this->lang->line(strtolower((string) $student['gender'])); ?></td>
          </tr>
		  <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('rte'); ?></th>
            <td valign="top" width="60%"><?php if($student['rte']){ echo $this->lang->line(strtolower($student['rte'])); } ?></td>			
			<td valign="top" align="center" rowspan="<?php echo 9+count($fields);?>" width="120"><?php if ($sch_setting->student_barcode == 1) { ?><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/barcodes/' . $student['id'] . '.png'); ?>" style="width:130px; height: 65px;" /><?php } ?></td>
          </tr>	  
		  
		  <?php if ($sch_setting->admission_date) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('admission_date'); ?></th>
            <td valign="top" width="60%"><?php
				if (!empty($student['admission_date'])) {
                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($student['admission_date']))));
                } ?>
			</td>
          </tr>
		  <?php } ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('date_of_birth'); ?></th>
            <td valign="top" width="60%"><?php
                if(!empty($student['dob']) && $student['dob'] != '0000-00-00') {
                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                }
                ?>
			</td>
          </tr>
		  <?php if ($sch_setting->category) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('category'); ?></th>
            <td valign="top" width="60%">
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

        <?php
      if ($sch_setting->religion) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('religion'); ?></th>
            <td valign="top" width="60%"><?php echo $student['religion']; ?></td>
          </tr>
      <?php } ?>

      <?php if ($sch_setting->cast) {  ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('caste'); ?></th>
            <td valign="top" width="60%"></span><?php echo $student['cast']; ?></td>
          </tr>
		  <?php } ?>

    

       <?php if ($sch_setting->mobile_no) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('mobile_number'); ?></th>
            <td valign="top" width="60%"><?php echo $student['mobileno']; ?></td>
          </tr>
      <?php } ?>

      <?php
       if ($sch_setting->student_email) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('email'); ?></th>
            <td valign="top" width="60%"><?php echo $student['email']; ?></td>
          </tr>
		  <?php } ?>
      <?php
       if($sch_setting->student_note){  ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('note'); ?></th>
            <td valign="top" width="60%"><?php echo $student['note']; ?></td>
          </tr>
		  <?php  }  ?>

      <?php
       if(!empty($fields)) {
          foreach ($fields as $fields_key => $fields_value) {    ?>
            <tr>
             <th><?php echo $fields_value->name; ?></th>
             <th><?php 
                $custom_name   = $fields_value->name;
                $display_field = $student["$custom_name"];
                if($fields_value->type == "link") {
                  $display_field = "<a href=" . $student["$custom_name"] . " target='_blank'>" . $student["$custom_name"] . "</a>";
                }
                echo $display_field;
              ?></th>
            </tr>
       <?php }  } ?>

        </table>
      </td>
    </tr> 
    <tr><td valign="top" class="pt-20 pb-20 text-center"><h2 class="heading-h2"><?php echo $this->lang->line('address'); ?></h2></td></tr>
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
		  <?php if ($sch_setting->current_address) { ?>
          <tr>
            <th valign="top" style="width: 360px"><?php echo $this->lang->line('current_address'); ?></th>
            <td valign="top"><?php echo $student['current_address']; ?></td>            
          </tr>
		  <?php } if ($sch_setting->permanent_address) { ?>
          <tr>
            <th valign="top"><?php echo $this->lang->line('permanent_address'); ?></th>
            <td valign="top"><?php echo $student['permanent_address']; ?></td>
          </tr>
		  <?php } ?>
        </table>
      </td>
    </tr> 

    <tr><td valign="top" class="pt-20 pb-20 text-center"><h2 class="heading-h2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h2></td></tr>
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
		  <?php if ($sch_setting->father_name) {  ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('father_name'); ?></th>
            <td valign="top" width="60%"><?php echo $student['father_name']; ?></td>
            <td valign="top" align="center" rowspan="3" width="120"><img src="<?php
                    if (!empty($student["father_pic"])) {
                        echo $this->media_storage->getImageURL($student["father_pic"]);
                    } else {
                        echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                    }  ?>" style="width:65px; height: 65px;" />
			</td>
          </tr>
		  <?php }
		  if ($sch_setting->father_phone) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('father_phone'); ?></th>
            <td valign="top" width="60%"><?php echo $student['father_phone']; ?></td>
          </tr>
		  <?php }
		  if ($sch_setting->father_occupation) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('father_occupation'); ?></th>
            <td valign="top" width="60%"><?php echo $student['father_occupation']; ?></td>
          </tr>
		  <?php }
		  if($sch_setting->mother_name){ ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('mother_name'); ?></th>
            <td valign="top" width="60%"><?php echo $student['mother_name']; ?></td>
            <td valign="top" align="center" rowspan="3" width="120"><img src="<?php
														if (!empty($student["mother_pic"])) {
                                                            echo $this->media_storage->getImageURL($student["mother_pic"]);
                                                        } else {
                                                            echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                        }
                                                    ?>" style="width:65px; height: 65px;" /></td>
          </tr>		  
		  <?php }
		  if ($sch_setting->mother_phone) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('mother_phone'); ?></th>
            <td valign="top" width="60%"><?php echo $student['mother_phone']; ?></td>
          </tr>		  
		  <?php }
		  if ($sch_setting->mother_occupation) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('mother_occupation'); ?></th>
            <td valign="top" width="60%"><?php echo $student['mother_occupation']; ?></td>
          </tr>		  
		  <?php }
		  if($sch_setting->guardian_name){ ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('guardian_name'); ?></th>
            <td valign="top" width="60%"><?php echo $student['guardian_name']; ?></td>
            <td valign="top" align="center" rowspan="6" width="120"><img src="<?php
                                                            if (!empty($student["guardian_pic"])) {
                                                                echo $this->media_storage->getImageURL($student["guardian_pic"]);
                                                            } else {
                                                                echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                            }
                                                            ?>" style="width:65px; height: 65px;" /></td>
          </tr>		  
		  <?php }
				if ($sch_setting->guardian_email) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('guardian_email'); ?></th>
            <td valign="top" width="60%"><?php echo $student['guardian_email']; ?></td>
          </tr>
		  <?php }
                if ($sch_setting->guardian_relation) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('guardian_relation'); ?></th>
            <td valign="top" width="60%"><?php echo $student['guardian_relation']; ?></td>
          </tr>
		  <?php }
                if ($sch_setting->guardian_phone) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('guardian_phone'); ?></th>
            <td valign="top" width="60%"><?php echo $student['guardian_phone']; ?></td>
          </tr>
		  <?php }
               if ($sch_setting->guardian_occupation) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('guardian_occupation'); ?></th>
            <td valign="top" width="60%"><?php echo $student['guardian_occupation']; ?></td>
          </tr>
		  <?php }
                if ($sch_setting->guardian_address) { ?>
           <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('guardian_address'); ?></th>
            <td valign="top" width="60%"><?php echo $student['guardian_address']; ?></td>
          </tr>
		  <?php } ?>
        </table>
      </td>
    </tr>
	
	<?php if ($sch_setting->route_list) {  ?>
    <tr><td valign="top" class="pt-20 pb-20 text-center"><h2 class="heading-h2"><?php echo $this->lang->line('route_details'); ?></h2></td></tr>
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('pick_up_point'); ?></th>
            <td valign="top"><?php echo $student['pickup_point_name']; ?></td>
          </tr>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('route'); ?></th>
            <td valign="top"><?php echo $student['route_title']; ?></td>
          </tr>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('vehicle_number'); ?></th>
            <td valign="top"><?php echo $student['vehicle_no']; ?></td>
          </tr>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('driver_name'); ?></th>
            <td valign="top"><?php echo $student['driver_name']; ?></td>
          </tr>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('driver_contact'); ?></th>
            <td valign="top"><?php echo $student['driver_contact']; ?></td>
          </tr>
         
        </table>
      </td>
    </tr>
	<?php } ?>
	
	<?php if ($sch_setting->hostel_id) {
            if ($this->module_lib->hasActive('hostel')) {
                if ($student['hostel_room_id'] != 0) {  ?>
    <tr><td valign="top" class="pt-20 pb-20 text-center"><h2 class="heading-h2"><?php echo $this->lang->line('hostel_details'); ?></h2></td></tr>   
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('hostel'); ?></th>
            <td valign="top"><?php echo $student['hostel_name']; ?></td>
          </tr>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('room_no'); ?></th>
            <td valign="top"><?php echo $student['room_no']; ?></td>
          </tr>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('room_type'); ?></th>
            <td valign="top"><?php echo $student['room_type']; ?></td>
          </tr> 
         
        </table>
      </td>
    </tr>
	<?php } } } ?>
	
    <tr><td valign="top" class="pt-20 pb-20 text-center"><h2 class="heading-h2"><?php echo $this->lang->line('miscellaneous_details'); ?></h2></td></tr>
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
		  <?php if ($sch_setting->is_blood_group) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('blood_group'); ?></th>
            <td valign="top"><?php echo $student['blood_group']; ?></td>
          </tr>
		  <?php } if ($sch_setting->is_student_house) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('house'); ?></th>
            <td valign="top"><?php echo $student['house_name']; ?></td>
          </tr>
		  <?php } if ($sch_setting->student_height) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('height'); ?></th>
            <td valign="top"><?php echo $student['height']; ?></td>
          </tr>
		  <?php } if ($sch_setting->student_weight) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('weight'); ?></th>
            <td valign="top"><?php echo $student['weight']; ?></td>
          </tr>
		  <?php } if ($sch_setting->measurement_date) { ?>		  
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('measurement_date'); ?></th>
            <td valign="top">
				<?php
                if (!empty($student['measurement_date']) && $student['measurement_date'] != '0000-00-00') {
                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['measurement_date']));
                }
                ?>
			</td>
          </tr>
		  <?php } if ($sch_setting->previous_school_details) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('previous_school_details'); ?></th>
            <td valign="top"><?php echo $student['previous_school']; ?></td>
          </tr>
		  <?php } if ($sch_setting->national_identification_no) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('national_identification_number'); ?></th>
            <td valign="top"><?php echo $student['adhar_no']; ?></td>
          </tr>
		  <?php } if ($sch_setting->local_identification_no) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('local_identification_number'); ?></th>
            <td valign="top"><?php echo $student['samagra_id']; ?></td>
          </tr>
		  <?php } if ($sch_setting->bank_account_no) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('bank_account_number'); ?></th>
            <td valign="top"><?php echo $student['bank_account_no']; ?></td>
          </tr>
		  <?php } if ($sch_setting->bank_name) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('bank_name'); ?></th>
            <td valign="top"><?php echo $student['bank_name']; ?></td>
          </tr>		  
		  <?php } if ($sch_setting->ifsc_code) { ?>
          <tr>
            <th valign="top" width="30%"><?php echo $this->lang->line('ifsc_code'); ?></th>
            <td valign="top"><?php echo $student['ifsc_code']; ?></td>
          </tr>
		  <?php } ?>
		  
        </table>
      </td>
    </tr> 
	<br>	
	<tr><td valign="top"><?php echo $this->setting_model->get_general_purpose_footer(); ?></td></tr>  
   
  </table>
</div>
</body>
</html>
