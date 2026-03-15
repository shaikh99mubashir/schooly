<?php  
	$student=$student_data; 

?>
<div style="width: 100%; margin: 0 auto;">
 <table cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td valign="top" style="background:#f6f6f6; padding: 6px 10px 12px">
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
      <td valign="top" width="120" style="padding-top:5px;">
       <img src="<?php echo $image_url; ?>" width="100" height="100">
      </td>
      <?php } ?>
      <td valign="top" >
       <table cellpadding="0" cellspacing="0" width="100%">

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

		<?php if($this->customlib->get_additional_field_status('other_details')) { if($student['designation']) { ?>
          <h4 style="line-height:normal;margin-bottom: 5px;"><?php echo $student['designation']; ?></h4>
		<?php } } ?>  
		  
          <hr style="background:#ddd;margin-bottom: 5px; height: 1px; margin-top: 6px;" />
         </td>
        </tr>
        <tr>
         <td valign="top">
          <table cellpadding="0" cellspacing="0" width="100%">
           <tr>
            <?php if ($this->customlib->getfieldcustomstatus('mobile_no')) {?>
            <td width="30"><img src="<?php echo base_url("backend/images/student_resume/phone-icon.png");?>" width="17" height="17"></td>
            <td><?php echo $student['mobileno'];?></td>
            <?php  } ?>
            <?php if ($this->customlib->getfieldcustomstatus('student_email')) {?>
          	
            <td width="30"><img src="<?php echo base_url("backend/images/student_resume/email-icon.png");?>" width="19" height="19"></td>
            <td><a href="<?php echo $student['email'];?>" style="color:#444; text-decoration: none;"><?php echo $student['email'];?></a></td>
            <?php } ?>
        	</tr>
        	<tr>
            <?php if ($this->customlib->getfieldcustomstatus('guardian_address')) {?>
            <td style="padding-top: 6px"><img src="<?php echo base_url("backend/images/student_resume/location-icon.png");?>" width="20" height="20"></td>
            <td style="padding-top: 6px"><?php echo $student['guardian_address'];?></td>
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
  
  <?php if($this->customlib->get_additional_field_status('other_details')) { if($student['about']) { ?>
  <tr>
   <td valign="top" style="padding: 20px 10px 10px">
    <h2 class="heading-h2"><?php echo $this->lang->line('about_me'); ?></h2>
    <hr />
    <p><?php echo $student['about']; ?></p>
   </td>
  </tr>
  <?php } } ?>
  
 <?php if ($this->customlib->getfieldcustomstatus('personal_details')){ ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 10px;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php echo $this->lang->line('personal_details'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">   
        <tr>
			 <?php if ($this->customlib->getfieldcustomstatus('dob')) {?>
				<th width="30%" style="border-bottom: 0"><?php echo $this->lang->line('date_of_birth'); ?></th>
				<td width="20%" style="border-bottom: 0">
					<?php if (!empty($student['dob']) && $student['dob'] != '0000-00-00') {
						echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
					} ?></td>        
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('gender')) {?>        
				<th width="30%" style="border-bottom:0"><?php echo $this->lang->line('gender'); ?></th>
				<td width="20%" style="border-bottom:0"><?php echo $student['gender'];?></td>        
			<?php } ?>
		<tr>

			<tr>			
			<?php if ($this->customlib->getfieldcustomstatus('category')) {?>         
				<th width="30%"><?php echo $this->lang->line('category'); ?></th>
				<td width="20%">
					<?php
					foreach ($category_list as $value) {
						if ($student['category_id'] == $value['id']) {
							echo $value['category'];
						}
					}
					?>
				</td>         
			<?php } ?>

			<?php if ($this->customlib->getfieldcustomstatus('religion')) {?>        
				<th width="30%"><?php echo $this->lang->line('religion'); ?></th>
				<td width="20%"><?php echo $student['religion']; ?></td>       
			<?php } ?>
			
		</tr>
       
		<tr>
		<?php if ($this->customlib->getfieldcustomstatus('cast')) {?>         
				<th width="30%"><?php echo $this->lang->line('caste'); ?></th>
				<td width="20%"><?php echo $student['cast'];  ?></td>         
			<?php } ?>

			<?php if ($this->customlib->getfieldcustomstatus('is_blood_group')) {?>         
				<th width="30%"><?php echo $this->lang->line('blood_group'); ?></th>
				<td width="20%"><?php echo $student['blood_group'];?></td>       
			<?php } ?>
			
		</tr>	
        
		<tr>	
		<?php if ($this->customlib->getfieldcustomstatus('height')) {?>        
				<th width="30%"><?php echo $this->lang->line('height'); ?></th>
				<td width="20%"><?php echo $student['height'];?></td>         
			<?php } ?>		
			<?php if ($this->customlib->getfieldcustomstatus('weight')) {?>        
				<th width="30%"><?php echo $this->lang->line('weight'); ?></th>
				<td width="20%"><?php echo $student['weight'];?></td>         
			<?php } ?>	
		</tr>		
		<tr>			
			<?php if ($this->customlib->getfieldcustomstatus('rte')) {?>        
				<th width="30%"><?php echo $this->lang->line('rte'); ?></th>
				<td width="20%"><?php echo $student['rte'];?></td>         
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('previous_school_details')) {?>       
				<th width="30%"><?php echo $this->lang->line('previous_school_details'); ?></th>
				<td width="20%"><?php echo $student['previous_school'];?></td>        
			<?php } ?>
		</tr>

			 <tr>
			<?php if ($this->customlib->getfieldcustomstatus('national_identification_no')) {?>        
				<th width="30%"><?php echo $this->lang->line('national_identification_number'); ?></th>
				<td width="20%"><?php echo $student['adhar_no'];?></td>        
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('local_identification_no')) {?>         
				<th width="30%"><?php echo $this->lang->line('local_identification_number'); ?></th>
				<td width="20%"><?php echo $student['samagra_id'];?></td>
			<?php } ?>	
								
		</tr>		
					
        <?php 
			//***student custom fields data***//
			$cutom_fields_data = get_custom_table_values($student['id'], 'students');
			if (!empty($cutom_fields_data)) {
				
				foreach ($cutom_fields_data as $field_key => $field_value) {
					if ($this->customlib->getfieldcustomstatus($field_value->name)) {
					 ?>	
					<tr>
						<th width="20%" colspan="1"><?php echo $field_value->name; ?></th>
						<td colspan="3"><?php
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
						 ?>
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

<?php } ?>
  <?php if ($this->customlib->getfieldcustomstatus('parent_guardian_detail')) {?>
  <tr>
   <td valign="top" style="padding: 10px 10px 10px;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
		<tr>
			<?php if ($this->customlib->getfieldcustomstatus('father_name')) {?>        
				<th width="25%"><?php echo $this->lang->line('father_name'); ?></th>
				<td><?php echo $student['father_name']; ?></td>         
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('mother_name')) {?>         
				<th><?php echo $this->lang->line('mother_name'); ?></th>
				<td width=""><?php echo $student['mother_name'];  ?></td>        
			<?php } ?>
		</tr>
		<tr>
			<?php if ($this->customlib->getfieldcustomstatus('father_occupation')) {?>       
				<th><?php echo $this->lang->line('father_occupation'); ?></th>
				<td><?php echo $student['father_occupation'];  ?></td>        
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('mother_occupation')) {?>        
				<th><?php echo $this->lang->line('mother_occupation'); ?></th>
				<td><?php echo $student['mother_occupation']; ?></td>        
			<?php } ?>
			
		</tr>
		<tr>
			<?php if ($this->customlib->getfieldcustomstatus('father_phone')) {?>        
				<th><?php echo $this->lang->line('father_phone'); ?></th>
				<td><?php echo $student['father_phone'];?></td>        
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('mother_phone')) {?>        
				<th><?php echo $this->lang->line('mother_phone'); ?></th>
				<td><?php echo $student['mother_phone'];?></td>        
			<?php } ?>
			
		</tr>
        <?php //if ($this->customlib->getfieldcustomstatus('if_guardian_is')) {?>
		<tr>
			<?php if ($this->customlib->getfieldcustomstatus('guardian_name')) {?>       
				<th><?php echo $this->lang->line('guardian_name'); ?></th>
				<td><?php echo $student['guardian_name'];  ?></td>        
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('guardian_relation')) {?>         
				<th><?php echo $this->lang->line('guardian_relation'); ?></th>
				<td><?php  echo $student['guardian_relation']; ?></td>       
			<?php } ?> 
		</tr>
		<tr>
			<?php if ($this->customlib->getfieldcustomstatus('guardian_phone')) {?>        
				<th><?php echo $this->lang->line('guardian_phone'); ?></th>
				<td><?php echo $student['guardian_phone'];?></td>        
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('guardian_email')) {?>         
				<th><?php echo $this->lang->line('guardian_email'); ?></th>
				<td><?php echo $student['guardian_email'];?></td>        
			<?php } ?>
		</tr>
		<tr>
			<?php if ($this->customlib->getfieldcustomstatus('guardian_occupation')) {?>        
				<th><?php echo $this->lang->line('guardian_occupation'); ?></th>
				<td><?php echo $student['guardian_occupation']; ?></td>       
			<?php } ?>
			<?php if ($this->customlib->getfieldcustomstatus('guardian_address')) {?>        
				<th><?php echo $this->lang->line('guardian_address'); ?></th>
				<td><?php echo $student['guardian_address'];  ?></td>       
			<?php } ?>
		</tr>
        <?php //} ?>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <?php } ?>
  <?php

if (!empty($get_student_education) && $this->customlib->get_additional_field_status('education_qalification')) { ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 10px;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php echo $this->lang->line('education_qalification'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <?php
        foreach($get_student_education as $key=>$value){ ?>
        <tr>
         <td valign="top">
          <h3><?php echo $value['course'];?></h3>
          <p class="font-italic"><?php echo $value['university'];?>&nbsp;<span style="float: right;"><?php echo $value['education_year'];?></span></p>
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
  <?php   
}  ?>
  <?php if (!empty($get_student_work_experience) && $this->customlib->get_additional_field_status('work_experience')) { ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php echo $this->lang->line('work_experience'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <?php
         foreach($get_student_work_experience as $key=>$value){
                ?>
        <tr>
         <td valign="top">
          <h3><?php echo $value['designation'];?></h3>
          <p class="font-italic"><?php echo $value['institute'];?> (<?php echo $value['location'];?>) <span style="float: right;"><?php echo $value['year'];?></span></p>
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
  <?php } ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <?php if (!empty($get_student_skills) && $this->customlib->get_additional_field_status('technical_skills')) {  ?>
      <td valign="top" style="width: 48%; padding-top:1rem">
       <h2 class="heading-h2"><?php echo $this->lang->line('technical_skills'); ?></h2>
       <hr />
       <ul class="hlaful">
        <?php
                foreach($get_student_skills as $key=>$value){  ?>
        <li><b><?php echo $value['skill_category'];?></b>:-<?php echo $value['skill_detail'];?> </li>
        <?php } ?>
       </ul>
      </td>
      <?php }  ?>
      </tr>
    </table>
   </td>
  </tr>

  <?php if (!empty($get_student_reference) && $this->customlib->get_additional_field_status('reference')){ ?>
  <tr>
   <td valign="top" style="padding: 20px 10px 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php echo $this->lang->line('reference'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <?php foreach($get_student_reference as $key=>$value){ ?>
        <tr>
         <td valign="top">
          <h3><?php echo $value['name'];?></h3>
          <p class="font-italic"><?php echo $value['profession'];?> <span style="float: right;"> </span></p>
          <p style="padding-bottom:30px"><?php echo $this->lang->line('contact'); ?>: <?php echo $value['contact'];?>, &nbsp;<?php echo $this->lang->line('age'); ?>: <?php echo $value['age'];?> <?php echo $this->lang->line('year'); ?>, &nbsp; <?php echo $this->lang->line('relation'); ?>: <?php echo $value['relation'];?>
          </p>
         </td>
        </tr>
        <?php } ?>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>