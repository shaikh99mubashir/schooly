<?php 
$admin_session   = $this->session->userdata('admin');
$currency_symbol = $admin_session['currency_symbol'];
?>
<div class="content-wrapper">
 <section class="content">
  <div class="row">
   <!-- student resume detail end -->
   <div class="col-md-12">
    <div class="nav-tabs-custom box box-primary theme-shadow">
     <div class="box-header with-border">
      <h3 class="box-title titlefix"><?php echo $this->lang->line('cv_setting'); ?></h3>
     </div>
     <ul class="nav nav-tabs nav-tabs2" id="myTab">
      <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('cv_fields'); ?></a></li>
      <li><a href="#tab_2" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('cv_other_fields'); ?></a></li>
      <li><a href="#tab_3" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('student_panel_cv_setting'); ?></a></li>
     </ul>
     <div class="tab-content pb0">
     <!-- resume setting start -->
      <div class="tab-pane active" id="tab_1">
       <div class="row">
        <div class="col-md-12">
         <div class="nav-tabs-custom ">
          <div class="row">
           <div class="col-sm-12">
            <div class="download_label"><?php echo $this->lang->line('cv_setting'); ?></div>
            <table class="table table-striped table-bordered table-hover example tableswitch" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('cv_setting'); ?>">
             <thead>
              <tr>
               <th><?php echo $this->lang->line('name'); ?></th>
               <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
              </tr>
             </thead>
             <tbody>
              <?php
                        $sch_setting_array = json_decode(json_encode($sch_setting_detail), true);
                        if(!empty($fields)) {
                            foreach ($fields as $fields_key => $fields_value) {  
                                if (array_key_exists($fields_key, $sch_setting_array)) {
                                    if (($sch_setting_detail->$fields_key)) { ?>
              <tr>
               <td class="text-rtl-right" width="100%"><?php echo $fields_value; ?></td>
               <td class="text-right">
                <div class="material-switch pull-right">
                 <input id="field_<?php echo $fields_key ?>" name="<?php echo $fields_key; ?>" type="checkbox" data-role="field_<?php $fields_key;?>" class="chk" value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($inserted_fields, $fields_key)); ?> />
                 <label for="field_<?php echo $fields_key ?>" class="label-info-success"></label>
                </div>
               </td>
              </tr>
              <?php   }  } else {  ?>
              <tr>
               <td><?php echo $fields_value; ?></td>
               <td class="text-right">
                <div class="material-switch pull-right">
                 <input id="field_<?php echo $fields_key ?>" name="<?php echo $fields_key; ?>" type="checkbox" data-role="field_<?php $fields_key;?>" class="chk" value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($inserted_fields, $fields_key)); ?> />
                 <label for="field_<?php echo $fields_key ?>" class="label-info-success"></label>
                </div>
               </td>
              </tr>
              <?php }    }   }
                            if (!empty($custom_fields)) {
                                foreach ($custom_fields as $custom_fields) {
                                    $exist = $this->customlib->checkcustomfieldexist($custom_fields['name']);
                                    if ($exist == 1) {
                                        $value = $this->customlib->getfieldcustomstatus($custom_fields['name']);
                                    } else {
                                        $value = 0;
                                    }  ?>
              <tr>
               <td><?php echo $custom_fields['name']; ?></td>
               <td class="text-right">
                <div class="material-switch pull-right">
                 <input id="field_<?php echo $custom_fields['name']; ?>" name="<?php echo $custom_fields['name']; ?>" type="checkbox" data-role="field_<?php $custom_fields['name'];?>" class="chk" value="<?php echo $value; ?>" <?php if ($value == 1) {echo 'checked';}?> />
                 <label for="field_<?php echo $custom_fields['name']; ?>" class="label-info-success"></label>
                </div>
               </td>
              </tr>
              <?php } } ?>
             </tbody>
            </table>
           </div>
          </div>
         </div>
        </div>
       </div>
      </div>
    <!-- resume setting end -->
    
    <!-- resume addtional details fields setting end -->
      <div class="tab-pane" id="tab_2">
       <div class="row">
        <div class="col-md-12">
         <div class="nav-tabs-custom ">
          <div class="row">
           <div class="col-sm-12">
            <div class="download_label"><?php echo $this->lang->line('cv_setting'); ?></div>
            <table class="table table-striped table-bordered table-hover example tableswitch" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('cv_setting'); ?>">
             <thead>
              <tr>
               <th><?php echo $this->lang->line('name'); ?></th>
               <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
              </tr>
             </thead>
             <tbody>
              <?php
                    if(!empty($additional_fields)) {
                            foreach ($additional_fields as $fields_key => $fields_value) { ?>
              <tr>
               <td class="text-rtl-right" width="100%"><?php echo $this->lang->line($fields_value->name); ?></td>
               <td class="text-right">
                <div class="material-switch pull-right">
                 <input id="additional_field_<?php echo $fields_value->name ?>" name="<?php echo $fields_value->name; ?>" type="checkbox" class="chk_change_additional_fields_status" value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($additional_fields, $fields_value->name)); ?> />
                 <label for="additional_field_<?php echo $fields_value->name; ?>" class="label-info-success"></label>
                </div>
               </td>
              </tr>
              <?php }  }  ?>
             </tbody>
            </table>
           </div>
          </div>
         </div>
        </div>
       </div>
      </div>
    <!-- resume addtional details fields setting end -->

    <!-- student panel download setting -->
      <div class="tab-pane" id="tab_3">
       <form role="form" id="enable_download_setting" action="<?php echo site_url('admin/resume/enable_download_setting') ?>" class="form-horizontal" method="post">
        <div class="box-body">
        <div class="row form-group">
            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
            <label class="col-sm-3 col-lg-2 col-md-3"><?php echo $this->lang->line('enable_download'); ?></label>
            <div class="col-sm-9 col-lg-10 col-md-9">
                <div class="material-switch">
                    <input id="student_resume_download" name="student_resume_download" type="checkbox" value="1" <?php echo set_checkbox('student_resume_download', '1', ($result->student_resume_download==1)); ?> />
                    <label for="student_resume_download" class="label-info-success"></label>
                </div>
            </div>
        </div>
        </div>
        <div class="box-footer ">
            <div class="row">
             <div class="col-md-12">
              <button type="submit" name="submitbtn" id="submitbtn" value="submitbtn" class="btn btn-primary  pull-right" autocomplete="off"> <?php echo $this->lang->line('save'); ?></button>
             </div>
            </div>
        </div>
       </form> 
     </div>
 <!-- student panel download setting -->
     </div>
    </div>
   </div>
   <!-- student resume detail end -->
  </div>
 </section>
</div>
 

<?php
function findSelected($inserted_fields, $find){
    foreach ($inserted_fields as $inserted_key => $inserted_value) {
        if ($find == $inserted_value->name && $inserted_value->status) {
            return true;
        }
    }
    return false;
} ?>

<script type="text/javascript">
//editable resume fields//
$(document).ready(function() {
    $(".select2").select2();
});

 (function ($) {
     $(document).ready(function () { 
         $(document).on('click', '.chk', function(event) { 
         var name=$(this).attr('name');
         var status=1;
         if(this.checked) {
             status=1;
         } else {
             status=0;
         } 
         if(confirm("<?php echo $this->lang->line('confirm_status'); ?>")){
            changeStatus(name, status);
         }
         else{
            event.preventDefault();
         }
         
         }); 
     });
 
    function changeStatus(name, status) { 
         var base_url = '<?php echo base_url() ?>'; 
         $.ajax({
             type: "POST",
             url: base_url + "admin/resume/changeresumefieldsetting",
             data: {'name': name, 'status': status},
             dataType: "json",
             success: function (data) {
                 successMsg(data.msg);
 
                 if(name=='if_guardian_is'){
 
                     $("#field_guardian_relation").prop('disabled', 'disabled');
                     $("#field_guardian_name").prop('disabled', 'disabled');
                     $("#field_guardian_phone").prop('disabled', 'disabled');
                     $("#field_guardian_email").prop('disabled', 'disabled');
                     $("#field_guardian_occupation").prop('disabled', 'disabled');
                     $("#field_guardian_photo").prop('disabled', 'disabled');
                     $("#field_guardian_address").prop('disabled', 'disabled');
 
                     if(status==0)
                     {
                         $("#field_guardian_relation").prop('checked',false);
                         $("#field_guardian_name").prop('checked',false);
                         $("#field_guardian_phone").prop('checked',false);
                         $("#field_guardian_email").prop('checked',false);
                         $("#field_guardian_occupation").prop('checked',false);
                         $("#field_guardian_photo").prop('checked',false);
                         $("#field_guardian_address").prop('checked',false);
                     }else{
                         $("#field_guardian_relation").prop('checked',true);
                         $("#field_guardian_name").prop('checked',true);
                         $("#field_guardian_phone").prop('checked',true);
                         $("#field_guardian_email").prop('checked',true);
                         $("#field_guardian_occupation").prop('checked',true);
                         $("#field_guardian_photo").prop('checked',true);
                         $("#field_guardian_address").prop('checked',true);
                     }
                 }
             }
         });
     } 
  })(jQuery);
  
//editable resume fields//
</script>

<script>
   //additional setting fields//
   (function ($) {
     $(document).ready(function () { 
         $(document).on('click', '.chk_change_additional_fields_status', function(event) { 
         var name=$(this).attr('name');
         var status=1;
         if(this.checked) {
             status=1;
         } else {
             status=0;
         } 
         if(confirm("<?php echo $this->lang->line('confirm_status'); ?>")){
            change_additional_fields_status(name, status);
         }
         else{
            event.preventDefault();
         }
         }); 
     });
 
    function change_additional_fields_status(name, status) { 
         var base_url = '<?php echo base_url() ?>'; 
         $.ajax({
             type: "POST",
             url: base_url + "admin/resume/change_additional_fields_status",
             data: {'name': name, 'status': status},
             dataType: "json",
             success: function (data) {
                 successMsg(data.msg);
             }
         });
     } 
  })(jQuery); 
//additional setting fields//

// enable disable resume download setting
$(document).ready(function (e) {
     $('#enable_download_setting').on('submit', (function (e) {
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
                } else {
                    successMsg(data.message);
                }

            },
            error: function () {
            
            }
         });
     }));
 });
// enable disable resume download setting


</script>
