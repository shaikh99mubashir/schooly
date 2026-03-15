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
        <?php if ($this->customlib->checkfieldexist_transfer_certificate('gender')) {?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"><?php echo $this->lang->line(strtolower((string) $student['gender'])); ?></a>
       </li>   
       <?php } ?> 

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('dob')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('date_of_birth'); ?></b> <a class="pull-right text-aqua"><?php if (!empty($student['dob']) && $student['dob'] != '0000-00-00') {
                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                    } ?></a>
        </li>  
        <?php } ?>  

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('category')) {?>  
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
          <?php if ($this->customlib->checkfieldexist_transfer_certificate('religion')) {?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('religion'); ?></b> <a class="pull-right text-aqua"><?php echo $student['religion']; ?></a>
       </li>   
       <?php } ?>   
          <?php if ($this->customlib->checkfieldexist_transfer_certificate('cast')) {?>
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('cast'); ?></b> <a class="pull-right text-aqua"><?php echo $student['cast']; ?></a>
       </li>   
       <?php } ?> 

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('mobile_no')) {?>  
       
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mobile_number'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mobileno']; ?></a>
       </li> 
       <?php } ?>     
         <?php if ($this->customlib->checkfieldexist_transfer_certificate('student_email')) {?>  
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('email'); ?></b> <a class="pull-right text-aqua"><?php echo $student['email']; ?></a>
       </li>     
 <?php } ?>   
   <?php if ($this->customlib->checkfieldexist_transfer_certificate('is_blood_group')) {?>   
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('blood_group'); ?></b> <a class="pull-right text-aqua"><?php echo $student['blood_group']; ?></a>
       </li>   
        <?php } ?>  
        <?php if ($this->customlib->checkfieldexist_transfer_certificate('height')) {?>    
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('height'); ?></b> <a class="pull-right text-aqua"><?php echo $student['height']; ?></a>
       </li>  
         <?php } ?> 
          <?php if ($this->customlib->checkfieldexist_transfer_certificate('weight')) {?>     
       <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('weight'); ?></b> <a class="pull-right text-aqua"><?php echo $student['weight']; ?></a>
       </li>  
    <?php } ?> 
    <?php if ($this->customlib->checkfieldexist_transfer_certificate('father_name')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('father_name'); ?></b> <a class="pull-right text-aqua"><?php echo $student['father_name']; ?></a>
       </li>  
    <?php } ?>

    <?php if ($this->customlib->checkfieldexist_transfer_certificate('father_phone')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('father_phone'); ?></b> <a class="pull-right text-aqua"><?php echo $student['father_phone']; ?></a>
       </li>  
    <?php } ?>
  
    <?php if ($this->customlib->checkfieldexist_transfer_certificate('father_occupation')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('father_occupation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['father_occupation']; ?></a>
       </li>  
    <?php } ?>

  
<?php if ($this->customlib->checkfieldexist_transfer_certificate('mother_name')) {?>
      <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mother_name'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mother_name']; ?></a>
      </li>  
<?php } ?>

<?php if ($this->customlib->checkfieldexist_transfer_certificate('mother_phone')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mother_phone'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mother_phone']; ?></a>
       </li>  
<?php } ?>

<?php if ($this->customlib->checkfieldexist_transfer_certificate('mother_occupation')) {?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('mother_occupation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['mother_occupation']; ?></a>
       </li>  
<?php } ?>

   <?php //if ($this->customlib->checkfieldexist_transfer_certificate('if_guardian_is')) { ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('guardian_name')) { ?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_name'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_name']; ?></a>
        </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('guardian_relation')) { ?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_relation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_relation']; ?></a>
       </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('guardian_email')) { ?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_email'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_email']; ?></a>
       </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('guardian_phone')) { ?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_phone'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_phone']; ?></a>
       </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('guardian_occupation')) { ?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_occupation'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_occupation']; ?></a>
       </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('guardian_address')) { ?>
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('guardian_address'); ?></b> <a class="pull-right text-aqua"><?php echo $student['guardian_address']; ?></a>
       </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('current_address')) {?>
        <li class="list-group-item listnoback">
            <b><?php echo $this->lang->line('current_address'); ?></b> <a class="pull-right text-aqua"><?php echo $student['current_address']; ?></a>
        </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('permanent_address')) {?>
        <li class="list-group-item listnoback">
            <b><?php echo $this->lang->line('permanent_address'); ?></b> <a class="pull-right text-aqua"><?php echo $student['permanent_address']; ?></a>
        </li> 
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('national_identification_no')) {?>   
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('national_identification_number'); ?></b> <a class="pull-right text-aqua"><?php echo $student['adhar_no']; ?></a>
        </li>
        <?php } ?>

        <?php if ($this->customlib->checkfieldexist_transfer_certificate('local_identification_no')) {?>   
        <li class="list-group-item listnoback">
        <b><?php echo $this->lang->line('local_identification_number'); ?></b> <a class="pull-right text-aqua"><?php echo $student['samagra_id']; ?></a>
        </li>
<?php } ?>


<?php 
            //***student custom fields data***//
            $cutom_fields_data = get_custom_table_values($student['id'], 'students');
            if (!empty($cutom_fields_data)) {
                foreach ($cutom_fields_data as $field_key => $field_value) {
                    if ($this->customlib->checkfieldexist_transfer_certificate($field_value->name)) {
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
      </ul>
     </div>
    </div>
   </div>

    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header ptbnull">
                <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('fill_other_details'); ?></h3>
                    <div class="box-tools pull-right">
                    </div>
            </div>
            <div class="">
                <form id="" action="<?php echo site_url('admin/transfercertificate/save_custom_fields') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" value="<?php echo $id; ?>">
                        <div class="box-body">                       
                            <div class="row">
                                <?php echo display_custom_fields('transfer_certificate',$id); ?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary submit_schsetting pull-right " data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
</div>

