<?php  
	$student=$student_data; 
?>
<div style="width: 100%; margin: 0 auto;">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td valign="top" style="background:#f6f6f6; padding: 30px;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <?php
    if ($this->customlib->getfieldcustomstatus('student_photo')) {
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
                        <td valign="top" width="280">
                            <img src="<?php echo $image_url; ?>" width="180" height="180" style="border-radius: 100%;">
                        </td>
                        <?php } ?>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td valign="top" height="10"></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <h1><?php
if ($this->customlib->getfieldcustomstatus('lastname')) {
$lastname=$student['lastname'];
$sch_setting_lastname=$sch_setting->lastname;
}else{
$lastname="";
$sch_setting_lastname="";   
} 
 
 echo $student_name=$this->customlib->getFullName($student['firstname'],$student['middlename'],$lastname,$sch_setting->middlename,$sch_setting_lastname);


?></h1>
                                        <h4>Marketing Manager --r</h4>
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" height="10"></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <?php if ($this->customlib->getfieldcustomstatus('mobile_no')) {?>
                                                <td><img src="<?php echo base_url("backend/images/student_resume/phone-icon.png");?>"
                                                        width="19" height="19"></td>
                                                <td><?php echo $student['mobileno'];?></td>
                                                <?php  } ?>
                                                <?php if ($this->customlib->getfieldcustomstatus('student_email')) {?>
                                                <td><img src="<?php echo base_url("backend/images/student_resume/email-icon.png");?>"
                                                        width="21" height="21"></td>
                                                <td><a href="<?php echo $student['email'];?>"
                                                        style="color:#444; text-decoration: none;"><?php echo $student['email'];?></a>
                                                </td>
                                                <?php } ?>
                                                <?php if ($this->customlib->getfieldcustomstatus('current_address')) {?>
                                                <td><img src="<?php echo base_url("backend/images/student_resume/location-icon.png");?>"
                                                        width="20" height="20"></td>
                                                <td><?php echo $student['current_address'];?></td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding: 30px 30px 0">
                <h2>About Me --r</h2>
                <hr />
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding: 30px 30px 10px;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top">
                            <h2>Personal Detail --r</h2>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">
                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('dob')) {?>
                                    <td width="20%"><?php echo $this->lang->line('date_of_birth'); ?>:</td>
                                    <td width="30%">
                                        <?php if (!empty($student['dob']) && $student['dob'] != '0000-00-00') {
						echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
					} ?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('mobile_no')) {?>
                                    <td width="20%"><?php echo $this->lang->line('mobile_no'); ?>:</td>
                                    <td width="30%"><?php echo $student['mobileno'];?></td>
                                    <?php } ?>
                                <tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('student_email')) {?>
                                    <td width="20%"><?php echo $this->lang->line('email'); ?>:</td>
                                    <td width="30%"><?php echo $student['email'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('local_identification_no')) {?>
                                    <td width="20%"><?php echo $this->lang->line('local_identification_number'); ?> :
                                    </td>
                                    <td width="30%"><?php echo $student['samagra_id'];?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('national_identification_no')) {?>
                                    <td width="20%"><?php echo $this->lang->line('national_identification_number'); ?>:
                                    </td>
                                    <td width="30%"><?php echo $student['adhar_no'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('gender')) {?>
                                    <td width="20%"><?php echo $this->lang->line('gender'); ?> :</td>
                                    <td width="30%"><?php echo $student['gender'];?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('is_blood_group')) {?>
                                    <td width="20%"><?php echo $this->lang->line('blood_group'); ?>:</td>
                                    <td width="30%"><?php echo $student['blood_group'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('height')) {?>
                                    <td width="20%"><?php echo $this->lang->line('height'); ?>:</td>
                                    <td width="30%"><?php echo $student['height'];?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('weight')) {?>
                                    <td width="20%"><?php echo $this->lang->line('weight'); ?> :</td>
                                    <td width="30%"><?php echo $student['weight'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('religion')) {?>
                                    <td width="20%"><?php echo $this->lang->line('religion'); ?> :</td>
                                    <td width="30%"><?php echo $student['religion']; ?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('category')) {?>
                                    <td width="20%"><?php echo $this->lang->line('category'); ?> :</td>
                                    <td width="30%">
                                        <?php
					foreach ($category_list as $value) {
						if ($student['category_id'] == $value['id']) {
							echo $value['category'];
						}
					}
					?>
                                    </td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('cast')) {?>
                                    <td width="20%"><?php echo $this->lang->line('caste'); ?> :</td>
                                    <td width="30%"><?php echo $student['cast']; echo $student['cast'];  ?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('rte')) {?>
                                    <td width="20%"><?php echo $this->lang->line('rte'); ?>:</td>
                                    <td width="30%"><?php echo $student['rte'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('current_address')) { ?>
                                    <td width="20%"><?php echo $this->lang->line('current_address'); ?>:</td>
                                    <td width="30%"><?php echo $student['current_address'];?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('permanent_address')) {?>
                                    <td width="20%"><?php echo $this->lang->line('permanent_address'); ?>:</td>
                                    <td width="30%"><?php echo $student['permanent_address'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('previous_school_details')) {?>
                                    <td width="20%"><?php echo $this->lang->line('previous_school_details'); ?> :</td>
                                    <td width="30%"><?php echo $student['previous_school'];?></td>
                                    <?php } ?>
                                </tr>

                                <?php 
			//***student custom fields data***//
			$cutom_fields_data = get_custom_table_values($student['id'], 'students');
			if (!empty($cutom_fields_data)) {
				$cfcount = 0;
				foreach ($cutom_fields_data as $field_key => $field_value) {
					if ($this->customlib->getfieldcustomstatus($field_value->name)) { $cfcount ++;  
					$cfcount_remainder = $cfcount % 2; 
					if($cfcount_remainder  != 0){  echo "<tr>"; } ?>
                                <td width="20%"><?php echo $field_value->name; ?></td>
                                <td width="30%"><?php
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
                                <?php if($cfcount_remainder  != 0){ echo "</tr>"; } ?>
                                <?php 
					} 
				}
			} 
			
		?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if ($this->customlib->getfieldcustomstatus('is_personal_details')) {?>
        <tr>
            <td valign="top" style="padding: 30px 30px 10px;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top">
                            <h2><?php echo $this->lang->line('parent_guardian_detail'); ?> <br>
                                <?php echo $this->lang->line('en_parent_guardian_detail'); ?></h2>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('father_name')) {?>
                                    <td width="20%"><?php echo $this->lang->line('father_name'); ?> :</td>
                                    <td width="30%"><?php echo $student['father_name']; ?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('mother_name')) {?>
                                    <td width="20%"><?php echo $this->lang->line('mother_name'); ?> :</td>
                                    <td width=""><?php echo $student['mother_name'];  ?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('father_occupation')) {?>
                                    <td width="20%"><?php echo $this->lang->line('father_occupation'); ?> :</td>
                                    <td width=""><?php echo $student['father_occupation'];  ?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('mother_occupation')) {?>
                                    <td width="20%"><?php echo $this->lang->line('mother_occupation'); ?> :</td>
                                    <td width=""><?php echo $student['mother_occupation']; ?></td>
                                    <?php } ?>

                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('father_phone')) {?>
                                    <td width="20%"><?php echo $this->lang->line('father_phone'); ?> :</td>
                                    <td width="30%"><?php echo $student['father_phone'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('mother_phone')) {?>
                                    <td width="20%"><?php echo $this->lang->line('mother_phone'); ?> :</td>
                                    <td width=""><?php echo $student['mother_phone'];?></td>
                                    <?php } ?>

                                </tr>

                                <?php if ($this->customlib->getfieldcustomstatus('if_guardian_is')) {?>
                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('guardian_name')) {?>
                                    <td width="20%"><?php echo $this->lang->line('guardian_name'); ?> :</td>
                                    <td width=""><?php echo $student['guardian_name'];  ?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('guardian_relation')) {?>
                                    <td width="20%"><?php echo $this->lang->line('guardian_relation'); ?> :</td>
                                    <td width=""><?php  echo $student['guardian_relation']; ?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('guardian_phone')) {?>
                                    <td width="20%"><?php echo $this->lang->line('guardian_phone'); ?> :</td>
                                    <td width=""><?php echo $student['guardian_phone'];?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('guardian_email')) {?>
                                    <td width="20%"><?php echo $this->lang->line('guardian_email'); ?> :</td>
                                    <td width=""><?php echo $student['guardian_email'];?></td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <?php if ($this->customlib->getfieldcustomstatus('guardian_occupation')) {?>
                                    <td width="20%"><?php echo $this->lang->line('guardian_occupation'); ?> :</td>
                                    <td width=""><?php echo $student['guardian_occupation']; ?></td>
                                    <?php } ?>
                                    <?php if ($this->customlib->getfieldcustomstatus('guardian_address')) {?>
                                    <td width="20%"><?php echo $this->lang->line('guardian_address'); ?> :</td>
                                    <td width=""><?php echo $student['guardian_address'];  ?></td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php } ?>
        <?php

if ($this->customlib->getfieldcustomstatus('is_educational_details')) { 

if(!empty($get_student_education)){
    ?>
        <tr>
            <td valign="top" style="padding: 30px 30px 10px;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top">
                            <h2>Education --r</h2>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">
                                <?php
        foreach($get_student_education as $key=>$value){ ?>
                                <tr>
                                    <td valign="top">
                                        <h3><?php echo $value['course'];?></h3>
                                        <p style="font-size: 18px; font-style: italic;color:#807e7e;">
                                            <?php echo $value['university'];?><span
                                                style="float: right;"><?php echo $value['education_year'];?></span></p>
                                        <p style="padding-bottom:30px"><?php echo $value['education_detail'];?></p>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php } }  ?>
        <?php if ($this->customlib->getfieldcustomstatus('is_work_experience')) {

   if(!empty($get_student_work_experience)){ ?>
        <tr>
            <td valign="top" style="padding: 30px 30px 0;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top">
                            <h2>Experience --r</h2>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">
                                <?php
         foreach($get_student_work_experience as $key=>$value){
                ?>
                                <tr>
                                    <td valign="top">
                                        <h3><?php echo $value['designation'];?></h3>
                                        <p style="font-size: 18px; font-style: italic;color:#807e7e;">
                                            <?php echo $value['institute'];?> (<?php echo $value['location'];?>) <span
                                                style="float: right;"><?php echo $value['year'];?></span></p>
                                        <p style="padding-bottom:30px"><?php echo $value['detail'];?></p>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php } } ?>
        <tr>
            <td valign="top" style="padding: 30px 30px 0;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <?php if ($this->customlib->getfieldcustomstatus('is_skills')) {
      if(!empty($get_student_skills)){ ?>
                        <td valign="top" style="padding-right:20px; width: 48%; padding-top:1rem">
                            <h2>Professional Skill --r</h2>
                            <hr />
                            <ul class="hlaful">
                                <?php
                foreach($get_student_skills as $key=>$value){  ?>
                                <li><b><?php echo $value['skill_category'];?></b>:-<?php echo $value['skill_detail'];?>
                                </li>
                                <?php } ?>
                            </ul>
                        </td>
                        <?php } }  ?>
                    </tr>
                </table>
            </td>
        </tr>

        <?php if ($this->customlib->getfieldcustomstatus('is_referensh')) {
  if(!empty($get_student_reference)){ ?>
        <tr>
            <td valign="top" style="padding: 30px 30px 0;">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top">
                            <h2>Reference --r</h2>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" class="">
                                <?php foreach($get_student_reference as $key=>$value){ ?>
                                <tr>
                                    <td valign="top">
                                        <h3><?php echo $value['name'];?></h3>
                                        <p style="font-size: 18px; font-style: italic;color:#807e7e;">
                                            <?php echo $value['profession'];?> <span style="float: right;"> </span></p>
                                        <p style="padding-bottom:30px">Contact --r: <?php echo $value['contact'];?>,
                                            &nbsp;Age --r: <?php echo $value['age'];?> Year, &nbsp; Relation --r:
                                            <?php echo $value['relation'];?>
                                        </p>
                                    </td>
                                </tr>
                                <?php } } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td valign="top" height="10"></td>
        </tr>
    </table>
</div>