<div class="content-wrapper">
 <!-- Main content -->
 <section class="content">
  <div class="row">
   <div class="col-md-3">
    <div class="box box-primary" <?php if ($student["is_active"] == "no") { echo "style='background-color:#f0dddd;'"; }  ?>>
     <div class="box box-widget widget-user-2 mb0">
      <div class="widget-user-header bg-gray-light overflow-hidden">
       <div class="widget-user-image">
        <?php
         if ($sch_setting->student_photo) {
            if (!empty($student["image"])) {
                $image_url = $this->media_storage->getImageURL($student["image"]);
            } else {
                if ($student['gender'] == 'Female') {
                $image_url = $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
            } else {
                $image_url = $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
            }
        } ?>
        <img class="profile-user-img img-responsive img-rounded" src="<?php echo $image_url; ?>" alt="User profile picture">
        <?php } ?>
       </div>
       <h3 class="widget-user-username"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); echo ' - '.$this->uri->segment(4); ?></h3>
    <h5 class="widget-user-desc mb5"><?php echo $this->lang->line('admission_no'); ?> <span class="text-aqua"><?php echo $student['admission_no']; ?></span></h5>
		<?php if ($sch_setting->roll_no) { ?>
		       <h5 class="widget-user-desc"><?php echo $this->lang->line('roll_number'); ?> <span class="text-aqua"><?php echo $student['roll_no']; ?></h5>
		<?php } ?>
      </div>
     </div>
     <div class="box-body box-profile pt0">
      <ul class="list-group list-group-unbordered">
      
       <li class="list-group-item listnoback border0">
        <b><?php echo $this->lang->line('class'); ?></b> <a class="pull-right text-aqua"><?php echo $student['class'] . " (" . $session . ")"; ?></a>
       </li>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('section'); ?></b> <a class="pull-right text-aqua"><?php echo $student['section']; ?></a>
       </li>      
	    <?php if ($this->customlib->getfieldcustomstatus('gender')) {?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"><?php echo $this->lang->line(strtolower((string) $student['gender'])); ?></a>
       </li>   
       <?php } ?> 

        <?php if ($this->customlib->getfieldcustomstatus('dob')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('date_of_birth'); ?></b> <a class="pull-right text-aqua"><?php if (!empty($student['dob']) && $student['dob'] != '0000-00-00') {
                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                    } ?></a>
        </li>  
        <?php } ?>  

        <?php if ($this->customlib->getfieldcustomstatus('category')) {?>  
        <li class="list-group-item listnoback">
            <b><?php echo $this->lang->line('category'); ?></b> <a class="pull-right text-aqua"> <?php
                foreach ($category_list as $value) {
                    if ($student['category_id'] == $value['id']) {
                        echo $value['category'];
                    }
                 }

        ?></a>
       </li>         
        <?php } ?>
	      <?php if ($this->customlib->getfieldcustomstatus('religion')) {?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('religion'); ?></b> <a class="pull-right text-aqua"><?php echo $student['religion']; ?></a>
       </li>   
       <?php } ?>   
	      <?php if ($this->customlib->getfieldcustomstatus('cast')) {?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('cast'); ?></b> <a class="pull-right text-aqua"><?php echo $student['cast']; ?></a>
       </li>   
       <?php } ?> 

        <?php if ($this->customlib->getfieldcustomstatus('mobile_no')) {?>  
	   
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mobile_number'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mobileno']; ?></a>
       </li> 
       <?php } ?>     
	     <?php if ($this->customlib->getfieldcustomstatus('student_email')) {?>  
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('email'); ?></b> <a class="pull-right text-aqua"><?php echo $student['email']; ?></a>
       </li>     
 <?php } ?>   
   <?php if ($this->customlib->getfieldcustomstatus('is_blood_group')) {?>   
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('blood_group'); ?></b> <a class="pull-right text-aqua"><?php echo $student['blood_group']; ?></a>
       </li>   
	    <?php } ?>  
        <?php if ($this->customlib->getfieldcustomstatus('height')) {?>    
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('height'); ?></b> <a class="pull-right text-aqua"><?php echo $student['height']; ?></a>
       </li>  
	     <?php } ?> 
          <?php if ($this->customlib->getfieldcustomstatus('weight')) {?>     
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('weight'); ?></b> <a class="pull-right text-aqua"><?php echo $student['weight']; ?></a>
       </li>  
    <?php } ?> 
    <?php if ($this->customlib->getfieldcustomstatus('father_name')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('father_name'); ?></b> <a class="pull-right text-aqua"><?php echo $student['father_name']; ?></a>
       </li>  
    <?php } ?>

    <?php if ($this->customlib->getfieldcustomstatus('father_phone')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('father_phone'); ?></b> <a class="pull-right text-aqua"><?php echo $student['father_phone']; ?></a>
       </li>  
    <?php } ?>
  
    <?php if ($this->customlib->getfieldcustomstatus('father_occupation')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('father_occupation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['father_occupation']; ?></a>
       </li>  
    <?php } ?>

  
<?php if ($this->customlib->getfieldcustomstatus('mother_name')) {?>
      <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mother_name'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mother_name']; ?></a>
      </li>  
<?php } ?>

<?php if ($this->customlib->getfieldcustomstatus('mother_phone')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mother_phone'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mother_phone']; ?></a>
       </li>  
<?php } ?>

<?php if ($this->customlib->getfieldcustomstatus('mother_occupation')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mother_occupation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mother_occupation']; ?></a>
       </li>  
<?php } ?>

   <?php if ($this->customlib->getfieldcustomstatus('if_guardian_is')) { ?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_name'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_name']; ?></a>
       </li> 
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_relation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_relation']; ?></a>
       </li> 
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_email'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_email']; ?></a>
       </li> 
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_phone'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_phone']; ?></a>
       </li> 
       
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_occupation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_occupation']; ?></a>
       </li> 
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_address'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_address']; ?></a>
       </li> 
    <?php } ?>

    <?php if ($this->customlib->getfieldcustomstatus('current_address')) {?>
        <li class="list-group-item listnoback">
            <b><?php echo $this->lang->line('current_address'); ?></b> <a class="pull-right text-aqua"><?php echo $student['current_address']; ?></a>
        </li> 
    <?php } ?>

    <?php if ($this->customlib->getfieldcustomstatus('permanent_address')) {?>
        <li class="list-group-item listnoback">
            <b><?php echo $this->lang->line('permanent_address'); ?></b> <a class="pull-right text-aqua"><?php echo $student['permanent_address']; ?></a>
        </li> 
    <?php } ?>
      <?php if ($this->customlib->getfieldcustomstatus('national_identification_no')) {?>   

    <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('national_identification_number'); ?></b> <a class="pull-right text-aqua"><?php echo $student['adhar_no']; ?></a>
       </li>
   <?php } ?>

     <?php if ($this->customlib->getfieldcustomstatus('local_identification_no')) {?>   
       
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('local_identification_number'); ?></b> <a class="pull-right text-aqua"><?php echo $student['samagra_id']; ?></a>
    </li>
<?php } ?>


<?php 
            //***student custom fields data***//
            $cutom_fields_data = get_custom_table_values($student['id'], 'students');
            if (!empty($cutom_fields_data)) {
                foreach ($cutom_fields_data as $field_key => $field_value) {
                    if ($this->customlib->getfieldcustomstatus($field_value->name)) {
                    ?>  
                   <li class="list-group-item listnoback">
                       <b><?php echo $field_value->name; ?> </b><a class="pull-right text-aqua"><?php
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
                        </a>
                     </li> 
                <?php 
                    } 
                }
            } 
        ?>      
    <!-- show resume active fields -->
      </ul>
     </div>
    </div>
   </div>

   <?php $active_class="class='active'";   ?>
   <div class="col-md-9">
    <div class="nav-tabs-custom box box-primary theme-shadow">
     <div class="box-header with-border">
      <h3 class="box-title titlefix"><?php echo $this->lang->line('fill_resume_details'); ?></h3>  
     </div>
    <ul class="nav nav-tabs nav-tabs2" id="myTab">
     
      <?php  if($this->customlib->get_additional_field_status('work_experience')) {   ?>
      <li <?php echo $active_class;?>><a href="#tab_1" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('work_experience'); ?></a></li>
      <?php  $active_class=""; }  ?>
     
      <?php  if($this->customlib->get_additional_field_status('education_qalification')) {   ?>
      <li  <?php echo $active_class;?>><a href="#tab_2" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('education_qalification'); ?></a></li>
      <?php  $active_class=""; }  ?>

      <?php  if($this->customlib->get_additional_field_status('technical_skills')) {   ?>
      <li  <?php echo $active_class;?>><a href="#tab_3" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('technical_skills'); ?></a></li>
      <?php  $active_class=""; }  ?>
      
      <?php  if($this->customlib->get_additional_field_status('reference')) {   ?>
      <li  <?php echo $active_class;?>><a href="#tab_4" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('reference'); ?></a></li>
      <?php  $active_class=""; }  ?>

      <?php  if($this->customlib->get_additional_field_status('other_details')) {   ?>
      <li  <?php echo $active_class;?>><a href="#tab_5" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('other_details'); ?></a></li>
      <?php  $active_class=""; }  ?>
     </ul>
    
     <div class="tab-content pb0">
     <?php  
     $activeclass="active"; 
     if($this->customlib->get_additional_field_status('work_experience')) { ?>
      <div class="tab-pane <?php echo $activeclass;?>" id="tab_1">
       <form id="form_add_multiple" action="<?php echo site_url('admin/resume/add_work') ?>" class="form-horizontal" method="post">
         <div class="row">
          <div class="col-md-12">
           <div class="tshadow mb25 bozero">
            <input type="hidden" id="work_experience_count" name="work_experience_count" value="<?php echo count($get_student_work_experience);?>">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $id;?>">
            <h4 class="pagetitleh2 d-flex justify-content-between align-items-center"><?php echo $this->lang->line('work_experience'); ?>
             <button type="button" class="btn btn-primary btn-xs pull-right add_work_experience"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add');?></button>
            </h4>
            <div class="row around10">
             <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                   <thead>
                    <tr>
                     <th><?php echo $this->lang->line('institute'); ?></th>
                     <th><?php echo $this->lang->line('designation'); ?></th>
                     <th><?php echo $this->lang->line('year'); ?></th>
                     <th><?php echo $this->lang->line('location'); ?></th>
                     <th><?php echo $this->lang->line('details'); ?></th>
                    </tr>
                   </thead>
                   <tbody id="append_row_work_experience">
                    <?php 
                    if(!empty($get_student_work_experience)){
                        $sno=0;
                        foreach($get_student_work_experience as $key=>$value){ $sno++; ?>
                    <tr>
                     <td width="">
                      <input type="hidden" name="total_work_count[]" value="<?php echo $sno;?>" />
                      <input type="text" name="institute_<?php echo $sno;?>" id="institute_<?php echo $sno;?>" class="form-control" value="<?php echo $value['institute'];?>">
                     </td>
                     <td width=""><input type="text" name="designation_<?php echo $sno;?>" id="designation_<?php echo $sno;?>" class="form-control" value="<?php echo $value['designation'];?>"></td>
                     <td width="">
                      <input type="text" name="year_<?php echo $sno;?>" id="year_<?php echo $sno;?>" class="form-control" value="<?php echo $value['year'];?>">
                     </td>
                     <td width=""><input type="text" name="location_<?php echo $sno;?>" id="location_<?php echo $sno;?>" class="form-control" value="<?php echo $value['location'];?>"></td>
                     <td width=""><input type="text" name="detail_<?php echo $sno;?>" id="detail_<?php echo $sno;?>" class="form-control" value="<?php echo $value['detail'];?>"></td>
                     <td width="5%">
    					<?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?>
    					<button class="btn btn-md btn-danger rmv_work_experience" ><?php echo $this->lang->line('remove'); ?></button>
    					<?php } ?>
    					</td>
                    </tr>
                    <?php  } }  ?>
                   </tbody>
                  </table>
                </div>  
             </div>           
            <div class="box-footer">
                <div class="settinghr"></div>
				<?php if ($this->rbac->hasPrivilege('build_cv', 'can_add')) { ?>
                <button type="submit" id="submitbtn" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
				<?php } ?>
            </div>    
           </div>
          </div>
         </div>
        </div>
       </form>
      </div>
     <?php $activeclass=""; } ?>

    <?php if($this->customlib->get_additional_field_status('education_qalification')) {     ?>
    <div class="tab-pane <?php echo $activeclass;?>" id="tab_2">
       <form role="form" id="add_multiple_education" action="<?php echo site_url('admin/resume/add_education') ?>" method="post">
         <div class="row">
          <div class="col-md-12">
           <div class="tshadow mb25 bozero ">
            <input type="hidden" id="education_count" name="education_count" value="<?php echo count($get_student_education);?>">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $id;?>">
            <h4 class="pagetitleh2 d-flex justify-content-between align-items-center"><?php echo $this->lang->line('education_qalification'); ?>
             <button type="button" class="btn btn-primary btn-xs pull-right add_education"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add');?></button>
            </h4>
            <div class="row around10">
             <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                   <thead>
                    <tr>
                     <th><?php echo $this->lang->line('course'); ?></th>
                     <th><?php echo $this->lang->line('university'); ?></th>
                     <th><?php echo $this->lang->line('year'); ?></th>
                     <th><?php echo $this->lang->line('details'); ?></th>
                    </tr>
                   </thead>
                   <tbody id="append_row_education">
                    <?php 
                      if(!empty($get_student_education)){
                        $sno=0;
                        foreach($get_student_education as $key=>$value){ $sno++; ?>
                    <tr>
                     <td width="">
                      <input type="hidden" name="total_education_count[]" value="<?php echo $sno;?>">
                      <input type="text" name="course_<?php echo $sno;?>" id="course_<?php echo $sno;?>" class="form-control" value="<?php echo $value['course'];?>">
                     </td>
                     <td width="">
                      <input type="text" name="university_<?php echo $sno;?>" id="university_<?php echo $sno;?>" class="form-control" value="<?php echo $value['university'];?>">
                     </td>
                     <td width="">
                      <input type="text" name="education_year_<?php echo $sno;?>" id="education_year_<?php echo $sno;?>" class="form-control" value="<?php echo $value['education_year'];?>">
                     </td>
                     <td width="">
                      <input type="text" name="education_detail_<?php echo $sno;?>" id="education_detail_<?php echo $sno;?>" class="form-control" value="<?php echo $value['education_detail'];?>">
                     </td>
                     <td width="5%">
    					<?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?>
    					<button class="btn btn-md btn-danger rmv_education"><?php echo $this->lang->line('remove'); ?></button>
    					<?php } ?>
                     </td>
                    </tr>
                    <?php } } ?>
                   </tbody>
                  </table>
                </div>  
             </div>
             
             <div class="box-footer clearboth pb0">
				<?php if ($this->rbac->hasPrivilege('build_cv', 'can_add')) { ?>
				<button type="submit" id="submitbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>   
				<?php } ?>
             </div>          
             
            </div>
           </div>
          </div>
         </div>       
       </form>
    </div>
    <?php $activeclass="";  } ?>

    <?php  if($this->customlib->get_additional_field_status('technical_skills')) {   ?>
    <div class="tab-pane <?php echo $activeclass;?>" id="tab_3">
       <form role="form" id="add_multiple_skill" action="<?php echo site_url('admin/resume/add_skill') ?>" method="post">
         <div class="row">
          <div class="col-md-12">
           <div class="tshadow mb25 bozero">
            <input type="hidden" id="skills_count" name="skills_count" value="<?php echo count($get_student_skills);?>">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $id;?>">
            <h4 class="pagetitleh2 d-flex justify-content-between align-items-center"><?php echo $this->lang->line('technical_skills'); ?>
             <button type="button" class="btn btn-primary btn-xs pull-right add_skills"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add');?></button>
            </h4>
            <div class="row around10">
             <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                   <tr>
                    <th width=""><?php echo $this->lang->line('skill_category'); ?></th>
                    <th><?php echo $this->lang->line('details'); ?></th>
                   </tr>
                   <tbody id="append_row_skills">
                    <?php 
                      if(!empty($get_student_skills)){
                        $sno=0;
                        foreach($get_student_skills as $key=>$value){ $sno++; ?>
                    <tr>
                     <td width="">
                      <input type="hidden" name="total_skill_count[]" value="<?php echo $sno;?>">
                      <input type="text" name="skill_category_<?php echo $sno;?>" id="skill_category_<?php echo $sno;?>" class="form-control" value="<?php echo $value['skill_category'];?>">
                     </td>
                     <td width="">
                      <input type="text" name="skill_detail_<?php echo $sno;?>" id="skill_detail_<?php echo $sno;?>" class="form-control" value="<?php echo $value['skill_detail'];?>">
                     </td>
                     <td width="5%">
    					<?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?>
    					<button class="btn btn-md btn-danger rmv_skills"><?php echo $this->lang->line('remove'); ?></button>
    					<?php } ?>
                     </td>
                    </tr>
                    <?php }  } ?>
                   </tbody>
                  </table>
                </div>  
             </div>
             <div class="box-footer clearboth pb0">  
				<?php if ($this->rbac->hasPrivilege('build_cv', 'can_add')) { ?>
				<button type="submit" id="submitbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
				<?php } ?>
             </div>
            </div>
           </div>
          </div>
         </div>
       </form>
    </div>

  <?php $activeclass="";  } ?>

   <?php if($this->customlib->get_additional_field_status('reference')) {  ?>
	  
      <div class="tab-pane <?php echo $activeclass;;?>" id="tab_4">
       <form role="form" id="add_multiple_referensh" action="<?php echo site_url('admin/resume/add_referensh') ?>" class="" method="post">
         <div class="row">
          <div class="col-md-12">
           <div class="tshadow mb25 bozero">
            <input type="hidden" id="reference_count" name="reference_count" value="<?php echo count($get_student_reference);?>">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $id;?>">
            <h4 class="pagetitleh2 d-flex justify-content-between align-items-center"><?php echo $this->lang->line('reference'); ?>
             <button type="button" class="btn btn-primary btn-xs pull-right add_reference"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add');?></button>
            </h4>
            <div class="row around10">
             <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                   <tr>
                    <th><?php echo $this->lang->line('name'); ?></th>
                    <th><?php echo $this->lang->line('relation'); ?></th>
                    <th><?php echo $this->lang->line('age'); ?></th>
                    <th><?php echo $this->lang->line('profession'); ?></th>
                    <th><?php echo $this->lang->line('contact'); ?></th>
                   </tr>
                   <tbody id="append_row_reference">
                    <?php
                    if(!empty($get_student_reference)){
                        $sno=0;
                        foreach($get_student_reference as $key=>$value){ $sno++; ?>
                    <tr>
                     <td>
                      <input type="hidden" name="total_reference_count[]" value="<?php echo $sno;?>">
                      <input type="text" name="reference_name_<?php echo $sno;?>" id="reference_name_'<?php echo $sno;?>" class="form-control" value="<?php echo $value['name'];?>">
                     </td>
                     <td width=""><input type="text" name="relation_<?php echo $sno;?>" id="relation_<?php echo $sno;?>" class="form-control" value="<?php echo $value['relation'];?>"></td>
                     <td width=""><input type="text" name="reference_age_<?php echo $sno;?>" id="reference_age_<?php echo $sno;?>" class="form-control" value="<?php echo $value['age'];?>"></td>
                     <td width=""><input type="text" name="profession_<?php echo $sno;?>" id="profession_<?php echo $sno;?>" class="form-control" value="<?php echo $value['profession'];?>"></td>
                     <td width=""><input type="text" name="contact_<?php echo $sno;?>" id="contact_<?php echo $sno;?>" class="form-control" value="<?php echo $value['contact'];?>"></td>
                     <td width="5%">
    					<?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?>
    					<button class="btn btn-md btn-danger rmv_skills"><?php echo $this->lang->line('remove'); ?></button>
    					<?php } ?>
    				 </td>
                    </tr>
                    <?php } } ?>
                   </tbody>
                  </table>
                </div>  
             </div>
             <div class="box-footer clearboth pb0"> 
				<?php if ($this->rbac->hasPrivilege('build_cv', 'can_add')) { ?>
				<button type="submit" id="submitbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
				<?php } ?>
             </div>
            </div>
           </div>
          </div>
         </div>
        </form>
      </div>

  <?php  $activeclass=""; } ?>

   <?php  if($this->customlib->get_additional_field_status('other_details')) {  ?>
	  
      <div class="tab-pane <?php echo $activeclass;?>" id="tab_5">
       <form role="form" id="add_other_details" action="<?php echo site_url('admin/resume/add_other_details') ?>" class="" method="post">
         <div class="row">
          <div class="col-md-12">
           <div class="tshadow mb25 bozero">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $id;?>">
            <h4 class="pagetitleh2 d-flex justify-content-between align-items-center"><?php echo $this->lang->line('other_details'); ?></h4>
            <div class="row around10">
             <div class="col-md-12">
				<div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4"><?php echo $this->lang->line('designation'); ?> </label>
                        <div class="col-sm-8">
                            <input type="text" rows="10" class="form-control" id="designation" name="designation" value="<?php if(isset($student['designation'])){ echo $student['designation']; } ?>" />
                            <span class="text-danger"><?php echo form_error('designation'); ?></span>  
                        </div>
                    </div>
                </div>
				<div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4"><?php echo $this->lang->line('about'); ?><small class="req"> *</small> </label>
                        <div class="col-sm-8">
                            <textarea type="text" rows="10" class="form-control" id="about" name="about" value=""><?php if(isset($student['about'])){ echo $student['about']; } ?></textarea>
                            <span class="text-danger"><?php echo form_error('about'); ?></span>  
                        </div>
                    </div>
                </div>
				
             </div>
             <div class="box-footer clearboth pb0">
				<?php if ($this->rbac->hasPrivilege('build_cv', 'can_add')) { ?>
				<button type="submit" id="submitbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
				<?php } ?>
             </div>
            </div>
           </div>
          </div>
         </div>
        </form>
      </div>
      <?php  $activeclass="";  } ?>  
     </div>
    </div>
   </div>
  </div>
 </section>
</div>
<script type="text/javascript">
 
 $(document).on('click', '.rmv_work_experience', function (e) {
    let text = "<?php echo $this->lang->line('are_you_sure'); ?>";
    if (confirm(text) == true) {
        $(this).closest( "tr" ).remove();
    }
 });
 
 $(document).on('click', '.rmv_education', function (e) {
     // $(this).closest( "tr" ).remove();
     let text = "<?php echo $this->lang->line('are_you_sure'); ?>";
    if (confirm(text) == true) {
        $(this).closest( "tr" ).remove();
    }
 });
 
 $(document).on('click', '.rmv_skills', function (e) {
     // $(this).closest( "tr" ).remove();
      let text = "<?php echo $this->lang->line('are_you_sure'); ?>";
    if (confirm(text) == true) {
        $(this).closest( "tr" ).remove();
    }
 });
 
 $(document).on('click', '.rmv_reference', function (e) {
     // $(this).closest( "tr" ).remove();
     let text = "<?php echo $this->lang->line('are_you_sure'); ?>";
    if (confirm(text) == true) {
        $(this).closest( "tr" ).remove();
    }
 });
 
 
 $(document).ready(function(){
 var get_student_work_experience    =    '<?php echo count($get_student_work_experience);?>';
 var get_student_education          =    '<?php echo count($get_student_education);?>';
 var get_student_skills             =    '<?php echo count($get_student_skills);?>';
 var get_student_reference          =    '<?php echo count($get_student_reference);?>';
 
 if(get_student_work_experience==0){
     $(".add_work_experience").trigger("click");
 }
 
 if(get_student_education==0){
     $(".add_education").trigger("click");
 }
 
 if(get_student_skills==0){
     $(".add_skills").trigger("click");
 }
 if(get_student_reference==0){
     $(".add_reference").trigger("click");
 }
 });
    
 $(document).on('click', '.add_work_experience', function () { 
     var work_experience_count=$("#work_experience_count").val();
     $("#work_experience_count").val(parseInt(work_experience_count)+parseInt(1));
     var count=$("#work_experience_count").val(); 
     $("#append_row_work_experience").append('<tr><td width=""><input type="hidden"  name="total_work_count[]" value="'+count+'"/><input type="text" name="institute_'+count+'"  id="institute_'+count+'" class="form-control" value=""></td><td width=""><input type="text"  name="designation_'+count+'"  id="designation_'+count+'" class="form-control" value=""></td><td width=""><input type="text"  name="year_'+count+'"  id="year_'+count+'" class="form-control" value=""></td><td width=""><input type="text"  name="location_'+count+'"  id="location_'+count+'" class="form-control" value=""></td><td width=""><input type="text"  name="detail_'+count+'" id="detail_'+count+'" class="form-control" value=""></td><td width="5%"><?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?><button class="btn btn-md btn-danger rmv_work_experience"><?php echo $this->lang->line('remove'); ?></button><?php } ?></td> </tr>');
 }); 
 
 $(document).on('click', '.add_education', function () {
     var education_count=$("#education_count").val();
     $("#education_count").val(parseInt(education_count)+parseInt(1));
     var count=$("#education_count").val(); 
     $("#append_row_education").append('<tr><td width=""><input type="hidden"  name="total_education_count[]" value="'+count+'"><input type="text" name="course_'+count+'"  id="course_'+count+'" class="form-control" value=""></td><td width=""><input type="text"  name="university_'+count+'" id="university_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="education_year_'+count+'" id="education_year_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="education_detail_'+count+'" id="education_detail_'+count+'" class="form-control" value=""></td><td width="5%"><?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?><button class="btn btn-md btn-danger rmv_education"><?php echo $this->lang->line('remove'); ?></button><?php } ?></td> </tr>');
 }); 
 
 $(document).on('click', '.add_skills', function () {
     var skills_count=$("#skills_count").val();
     $("#skills_count").val(parseInt(skills_count)+parseInt(1));
     var count=$("#skills_count").val(); 
     $("#append_row_skills").append('<tr><td width=""><input type="hidden"  name="total_skill_count[]" value="'+count+'"><input type="text" name="skill_category_'+count+'" id="skill_category_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="skill_detail_'+count+'" id="skill_detail_'+count+'" class="form-control" value=""></td><td width="5%"><?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?><button class="btn btn-md btn-danger rmv_skills"><?php echo $this->lang->line('remove'); ?></button><?php } ?></td> </tr>');
  }); 
 
 $(document).on('click', '.add_reference', function () {
     var reference_count=$("#reference_count").val();
     $("#reference_count").val(parseInt(reference_count)+parseInt(1));
     var count=$("#reference_count").val(); 
     $("#append_row_reference").append('<tr><td width=""><input type="hidden"  name="total_reference_count[]" value="'+count+'"><input type="text" name="reference_name_'+count+'" id="reference_name_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="relation_'+count+'" id="relation_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="reference_age_'+count+'" id="reference_age_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="profession_'+count+'" id="profession_'+count+'" class="form-control" value=""></td><td width=""><input type="text" name="contact_'+count+'" id="contact_'+count+'" class="form-control" value=""></td><td width="5%"><?php if ($this->rbac->hasPrivilege('build_cv', 'can_delete')) { ?><button class="btn btn-md btn-danger rmv_skills"><?php echo $this->lang->line('remove'); ?></button><?php } ?></td> </tr>'); 
 });  
 
 $(document).ready(function (e) {
     $('#form_add_multiple').on('submit', (function (e) {
         $("#formadd_multiple_btn").button('loading');
         e.preventDefault();
         $.ajax({
             url: $(this).attr('action'),
             type: "POST",
             data: new FormData(this),
             dataType: 'json',
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {
                 if (data.status == "fail") {
                     var message = "";
                     $.each(data.error, function (index, value) {
                         message += value;
                     });
                     errorMsg(message);
                 }else if(data.status==2){
                     errorMsg(data.error);
                 } else {
                     successMsg(data.message);
                     window.location.reload(true);
                 }
                 $("#formadd_multiple_btn").button('reset');
             },
             error: function () {
 
             }
         });
     }));
 }); 
 
 $(document).ready(function (e) {
     $('#add_multiple_education').on('submit', (function (e) {
         $("#add_multiple_education_btn").button('loading');
         e.preventDefault();
         $.ajax({
             url: $(this).attr('action'),
             type: "POST",
             data: new FormData(this),
             dataType: 'json',
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {
                 if (data.status == "fail") {
                     var message = "";
                     $.each(data.error, function (index, value) {
                         message += value;
                     });
                     errorMsg(message);
                 }else if(data.status==2){
                     errorMsg(data.error);
                 } else {
                     successMsg(data.message);
                     window.location.reload(true);
                 }
                 $("#add_multiple_education_btn").button('reset');
             },
             error: function () {
 
             }
         });
     }));
 }); 
 
 $(document).ready(function (e) {
     $('#add_multiple_skill').on('submit', (function (e) {
         $("#add_multiple_skill_btn").button('loading');
         e.preventDefault();
         $.ajax({
             url: $(this).attr('action'),
             type: "POST",
             data: new FormData(this),
             dataType: 'json',
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {            
                 if (data.status == "fail") {
                     var message = "";
                     $.each(data.error, function (index, value) {
                         message += value;
                     });
                     errorMsg(message);
                 }else if(data.status==2){
                     errorMsg(data.error);
                 } else {
                     successMsg(data.message);
                     window.location.reload(true);
                 }
                 $("#add_multiple_skill_btn").button('reset');
             },
             error: function () {
 
             }
         });
     }));
 }); 

 $(document).ready(function (e) {
     $('#add_multiple_referensh').on('submit', (function (e) {
         $("#add_multiple_referensh_btn").button('loading');
         e.preventDefault();
         $.ajax({
             url: $(this).attr('action'),
             type: "POST",
             data: new FormData(this),
             dataType: 'json',
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {            
                 if (data.status == "fail") {
                     var message = "";
                     $.each(data.error, function (index, value) {
                         message += value;
                     });
                     errorMsg(message);
                 }else if(data.status==2){
                     errorMsg(data.error);
                 } else {
                     successMsg(data.message);
                     window.location.reload(true);
                 }
                 $("#add_multiple_referensh_btn").button('reset');
             },
             error: function () {
 
             }
         });
     }));
 });

 $(document).ready(function (e) {
     $('#add_other_details').on('submit', (function (e) {
        
         e.preventDefault();
         $.ajax({
             url: $(this).attr('action'),
             type: "POST",
             data: new FormData(this),
             dataType: 'json',
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {            
                 if (data.status == "fail") {
                     var msg = "";
                     $.each(data.error, function (index, value) {
                         msg += value;
                     });
                     errorMsg(msg);
                
                 } else {
                     successMsg(data.msg);
                     window.location.reload(true);
                 }
                  
             },
             error: function () {
 
             }
         });
     }));
 });
 
//maintain tab status after refresh

$(document).ready(function(){
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
    var id = $(e.target).attr("href").substr(1);
    window.location.hash = id;
    window.scrollTo({ top: 0 });
});

var hash = window.location.hash;
    activaTab(hash);
});

function activaTab(tab){
    $('ul.nav-tabs > li > a[href="' + tab + '"]').trigger("click");  
};
</script>