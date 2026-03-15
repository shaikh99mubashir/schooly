<?php 
$student=$student_data; 
?>

<div style="width: 100%; margin: 0 auto;">
<table class="table mb0">
    <tbody>
 <tr>
 <td>
    <img src="<?php echo $this->media_storage->getImageURL('/uploads/transfer_certificate/'.$get_settings[0]['header_image']); ?>" style="height: 100px;width: 100%;">
 </td>
</tr>
<tr>
<td style="margin:10px;padding: 10px;">
    <h2><center><?php echo $this->lang->line('transfer_certificate');?><br> <?php if($is_regenerte==1){ echo ('<b>['. $this->lang->line('reissue') .']</b>');} ?></center></h2>
</td>
</tr>

<tr>
<td style="margin:10px;padding:10px;">
    <table width="100%">
        <tr>
            <?php foreach($getallfields as $key=>$value){ 
                if($value['name']=="tc_no"){
                    if ($this->customlib->checkfieldexist_transfer_certificate($value['name']) && $value['is_default']==1) { ?>                       
                        
                        <td style="text-align:left;">
                            <?php if($get_settings[0]['affiliation_no']){ ?>
                            <h4>
                                <?php echo $this->lang->line('affiliation_no'); ?> :
                                <?php echo $get_settings[0]['affiliation_no'];?>
                            </h4>
                            <?php } ?>
                        </td>
						
						<td style="text-align:right;">
                            <h4>
                                <?php echo $this->lang->line('tc_no');?> :
                                <?php echo $print_next_tc_no;?>
                            </h4>
                        </td>

            <?php }}} ?>
        </tr>
    </table>
</td>
</tr>


<tr>
<td>
   <table  width="100%" class="denifittable" >   
	 
	
        <tr>
            <td width="30"><?php echo 1; ?></td> 
            <td><strong><?php echo $this->lang->line('name'); ?></strong></td>
            <td><?php echo $student_name=$this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting_detail->middlename,$sch_setting_detail->lastname);?>
            </td>
        </tr>	
<?php
  $sch_setting_array = json_decode(json_encode($sch_setting_detail), true);
    $sl=1;    
  foreach($getallfields as $key=>$value){  
    if ($this->customlib->checkfieldexist_transfer_certificate($value['name']) && $value['is_default']==1 && $value['name'] != 'tc_no') { 
        $sch_setting_field=$value['name'];
		
		if (array_key_exists("$sch_setting_field", $sch_setting_array)){
			if (($sch_setting_detail->$sch_setting_field)) {
        $sl++;
        ?>
        <tr>
            <td width="30"><?php echo $sl; ?></td> 
            <td><strong><?php echo $this->lang->line($value['lang_key']); ?></strong></td>
            <td><?php 
                if($value['name']=="admission_no"){
                    echo $student['admission_no'];
                }else if($value['name']=="roll_no"){
                    echo $student['roll_no'];
                }else if($value['name']=="admission_date"){
                    echo (isset($student['admission_date'])) ? $this->customlib->dateformat($student['admission_date']) : "";
                }else if($value['name']=="middlename"){
                    echo $student['middlename'];
                }else if($value['name']=="lastname"){
                    echo $student['lastname'];
                }else if($value['name']=="rte"){
                    echo $student['rte'];
                }else if($value['name']=="student_photo"){
                    $image=base_url().$student['image'];
                    echo "<img src='$image' height='150px' width='150px'>";
                }else if($value['name']=="mobileno"){
                    echo $student['mobileno'];
                }else if($value['name']=="student_email"){
                    echo $student['email'];
                }else if($value['name']=="religion"){
                    echo $student['religion'];
                }else if($value['name']=="cast"){
                    echo $student['cast'];
                }else if($value['name']=="dob"){
                    echo (isset($student['dob'])) ? $this->customlib->dateformat($student['dob']) : "";
                }else if($value['name']=="gender"){
                    echo $student['gender'];
                }else if($value['name']=="current_address"){
                    echo $student['current_address'];
                }else if($value['name']=="permanent_address"){
                    echo $student['permanent_address'];
                }else if($value['name']=="category"){
                    echo $student['category'];
                }else if($value['name']=="is_blood_group"){
                    echo $student['blood_group'];
                }else if($value['name']=="bank_account_no"){
                    echo $student['bank_account_no'];
                }else if($value['name']=="bank_name"){
                    echo $student['bank_name'];
                }else if($value['name']=="ifsc_code"){
                    echo $student['ifsc_code'];
                }else if($value['name']=="guardian_is"){
                    echo $student['guardian_is'];
                }else if($value['name']=="father_name"){
                    echo $student['father_name'];
                }else if($value['name']=="father_phone"){
                    echo $student['father_phone'];
                }else if($value['name']=="father_occupation"){
                    echo $student['father_occupation'];
                }else if($value['name']=="mother_name"){
                    echo $student['mother_name'];
                }else if($value['name']=="mother_phone"){
                    echo $student['mother_phone'];
                }else if($value['name']=="mother_occupation"){
                    echo $student['mother_occupation'];
                }else if($value['name']=="guardian_name"){
                    echo $student['guardian_name'];
                }else if($value['name']=="guardian_relation"){
                    echo $student['guardian_relation'];
                }else if($value['name']=="guardian_phone"){
                    echo $student['guardian_phone'];
                }else if($value['name']=="guardian_occupation"){
                    echo $student['guardian_occupation'];
                }else if($value['name']=="guardian_address"){
                    echo $student['guardian_address'];
                }else if($value['name']=="guardian_email"){
                    echo $student['guardian_email'];
                }else if($value['name']=="student_height"){
                    echo $student['height'];
                }else if($value['name']=="student_weight"){
                    echo $student['weight'];
                }else if($value['name']=="national_identification_no"){
                    echo $student['adhar_no'];
                }else if($value['name']=="local_identification_no"){
                    echo $student['samagra_id'];
                }else if($value['name']=="is_student_house"){
                    echo $student['house_name'];
                }
                ?>
            </td>   
        </tr>
        <?php } }
    } 
  } //end of foreach loop ?>
      <!-- custom fields -->
        <?php 
            //***student custom fields data***//
            $cutom_fields_data = get_custom_table_values($student['id'], 'transfer_certificate');
            if (!empty($cutom_fields_data)) {
                foreach ($cutom_fields_data as $field_key => $field_value) {  
                    if ($this->customlib->checkfieldexist_transfer_certificate($field_value->name)) {
                        $sl++;
                     ?> 
                    <tr>
                        <td><?php echo $sl; ?></td> 
                        <td><strong><?php echo $field_value->name; ?></strong></td>
                        <td><?php
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
                }
            } 
        ?>   
    <!-- custom fields -->
<!-- //=================== -->
</table>
</td>
</tr>
</tbody>
</table>


<!-- signature section -->
<table width="100%">
    <tr>
         <?php
        foreach($get_settings as $skey=>$svalue){
            if($svalue['class_teacher_signature']!=""){ ?>
                <td width="33%" style="padding:32px 0px 20px">
                    <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/'.$svalue['class_teacher_signature']) ?>" class="" alt="" width="" height="60px">
					<br>
					<strong><?php echo $this->lang->line('class_teacher_signature');?></strong>
                </td>
            <?php } ?>
            <?php if($svalue['checked_by']!=""){ ?>
                <td width="33%" style="padding:32px 0px 20px;text-align: center;">
                    <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/'.$svalue['checked_by']) ?>" class="" alt="" width="" height="60px">
					<br>
					<strong><?php echo $this->lang->line('checked_by');?></strong>
                </td>
            <?php } ?>
            <?php if($svalue['signature_of_principle']!=""){ ?>
                <td width="33%" style="padding:32px 0px 20px; text-align: right;">
                    <img src="<?php echo $this->media_storage->getImageURL('uploads/transfer_certificate/'.$svalue['signature_of_principle']) ?>" class="" alt="" width="" height="60px">
					<br>
					<strong><?php echo $this->lang->line('principle_signature');?></strong>
                </td>
            <?php } ?>
        <?php
        }
        ?>
    </tr>
</table>
<!-- signature section -->

</div>
<div style="width: 100%; margin: 0 auto;position: fixed;bottom: 0;left: 0;">
<b><?php echo $get_settings[0]['footer_content'];// $this->setting_model->get_transfer_certificate_footer(); ?></b>
</div>