<div class="content-wrapper">
 <!-- Main content -->
 <section class="content">
  <div class="row">
   <div class="col-md-12">
    <div class="nav-tabs-custom theme-shadow">
     <div class="box-header with-border">
      <h3 class="box-title titlefix"><?php echo $this->lang->line('student_profile_setting'); ?></h3>
     </div>
     <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('student_profile_update'); ?></a></li>
      <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('dashboard_setting'); ?></a></li>
     </ul>
     <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
       <div class="">
        <form action="<?php echo site_url('student/profilesetting') ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
            <div class="form-group row">
                 
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3"><?php echo $this->lang->line('allow_editable_form_fields'); ?></label>
                        <div class="col-sm-1">                                                   

                            <div class="material-switch">
                                <input id="student_profile_edit" name="student_profile_edit" type="checkbox" class="enableprofile" value="1" <?php echo set_checkbox('student_profile_edit', '1', ($result->student_profile_edit=='1')); ?> />
                                <label for="student_profile_edit" class="label-info-success"></label>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-primary submit_schsetting " data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('save'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                        </div>
                        <div class="col-sm-7"></div>
                    </div>
                </div>         
            </div>
            

        </form>
        <div id="tblclm">
         <h4 class="box-title"><?php echo $this->lang->line('student_profile_update'); ?></h4>
         <div class="download_label"><?php echo $this->lang->line('student_profile_update'); ?></div>
         <table class="table table-striped table-bordered table-hover table-switch" cellspacing="0" width="100%">
          <thead>
           <tr>
            <th><?php echo $this->lang->line('name'); ?></th>
            <th class="noExport text-right"><?php echo $this->lang->line('action'); ?></th>
           </tr>
          </thead>
          <tbody>
           <?php
                                            $sch_setting_array = json_decode(json_encode($sch_setting_detail), true);
                                            if (!empty($fields)) {
                                                $hide = 0;
                                                foreach ($fields as $fields_key => $fields_value) {
                                                    if (array_key_exists($fields_key, $sch_setting_array)) {
                                                        if (($sch_setting_detail->$fields_key)) {
                                                    ?>
           <tr>
            <td><?php echo $fields_value; ?></td>
            <td class="text-right">
             <div class="material-switch pull-right">
              <input id="field_<?php echo $fields_key ?>" name="<?php echo $fields_key; ?>" type="checkbox" data-role="field_<?php $fields_key?>" class="chk" value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($inserted_fields, $fields_key)); ?> />
              <label for="field_<?php echo $fields_key ?>" class="label-info-success"></label>
             </div>
            </td>
           </tr>
           <?php   }
                                                    }else{
                                                    
                                                        $hide = 0;
                                                        if ($fields_key == 'if_guardian_is') {
                                                            if ($sch_setting_detail->guardian_name == 0) {
                                                                $hide = 1;
                                                            }
                                                        }
                                                        if ($hide == 0) {
                                                        ?>
           <tr>
            <td><?php echo $fields_value; ?></td>
            <td class="text-right">
             <div class="material-switch pull-right">
              <input id="field_<?php echo $fields_key ?>" name="<?php echo $fields_key; ?>" type="checkbox" data-role="field_<?php $fields_key?>" class="chk" value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($inserted_fields, $fields_key)); ?> />
              <label for="field_<?php echo $fields_key ?>" class="label-info-success"></label>
             </div>
            </td>
           </tr>
           <?php
                                                        }
                                                    }
                                                }
                                            }
                                            if (!empty($custom_fields)) {
                                                foreach ($custom_fields as $custom_fields) {
                                                    $exist = $this->customlib->checkprofilesettingfieldexist($custom_fields['name']);
                                                        if ($exist == 1) {
                                                            $value = $this->customlib->checkprofilesettingfieldexist($custom_fields['name']);
                                                        } else {
                                                            $value = 0;
                                                        }
                                                ?>
           <tr>
            <td><?php echo $custom_fields['name']; ?></td>
            <td class="text-right">
             <div class="material-switch pull-right">
              <input id="field_<?php echo $custom_fields['name']; ?>" name="<?php echo $custom_fields['name']; ?>" type="checkbox" data-role="field_<?php $custom_fields['name'];?>" class="chk" value="<?php echo $value; ?>" <?php if ($value == 1) {echo 'checked';}?> />
              <label for="field_<?php echo $custom_fields['name']; ?>" class="label-info-success"></label>
             </div>
            </td>
           </tr>
           <?php 
                                                }
                                            }  
                                            ?>
          </tbody>
         </table>
        </div>
       </div>
      </div>

      <div class="tab-pane" id="tab_2">
       <div class="">
        <div class="row">
         <div class="col-sm-12">
          <div class="download_label"><?php echo $this->lang->line('student_profile_setting'); ?>student profile setting --r</div>
          <table class="table table-striped table-bordered table-hover example tableswitch"  data-export-title="<?php echo $this->lang->line('student_list'); ?>" cellspacing="0" width="100%">
           <thead>
            <tr>
             <th><?php echo $this->lang->line('name'); ?></th>
             <th class="noExport"><?php echo $this->lang->line('student'); ?></th>
             <th></th>
             <th class="noExport"><?php echo $this->lang->line('parent'); ?></th>
            </tr>
           </thead>
           <tbody>
            <?php
            foreach($studentpermissionList as $key=>$value){

             if($value->name=='welcome_student'){
            ?>
            <tr>
             <td class="text-rtl-right" width="100%"><?php echo $this->lang->line("$value->name"); ?></td>
             <td class="text-right">
              <div class="material-switch pull-right">
               <input id="field_<?php echo $value->name; ?>" name="<?php echo $value->name; ?>" type="checkbox" data-role="field_<?php $value->name;?>" class="chk_module"  data-type="student"
               value="" <?php echo set_checkbox($value->name, $value->name, ($value->is_student==1 ? true : false)); ?> />
               <label for="field_<?php echo $value->name; ?>" class="label-info-success"></label>
              </div>
             </td>
             <th></th>
            <td class="text-right">
               <div class="material-switch pull-right">
               <input id="parent_field_<?php echo $value->name; ?>" name="<?php echo $value->name; ?>" type="checkbox" data-role="parent_field_<?php $value->name;?>"
                class="chk_module"  data-type="parent"
               value="" <?php echo set_checkbox($value->name, $value->name, ($value->is_parent==1 ? true : false)); ?> />
               <label for="parent_field_<?php echo $value->name; ?>" class="label-info-success"></label>
              </div>
            </td>
            </tr>
            <?php 
            }else{   if($this->module_lib->hasActive($value->short_code) ){ ?>
            <tr>
            <td class="text-rtl-right" width="100%"><?php echo $this->lang->line("$value->name"); ?></td>
             <td class="text-right">
              <div class="material-switch pull-right">
               <input id="field_<?php echo $value->name; ?>" name="<?php echo $value->name; ?>" type="checkbox" data-role="field_<?php $value->name;?>" class="chk_module"  data-type="student"
               value="" <?php echo set_checkbox($value->name, $value->name, ($value->is_student==1 ? true : false)); ?> />
               <label for="field_<?php echo $value->name; ?>" class="label-info-success"></label>
              </div>
            </td>
            <th></th>
            <td class="text-right">
               <div class="material-switch pull-right">
               <input id="parent_field_<?php echo $value->name; ?>" name="<?php echo $value->name; ?>" type="checkbox" data-role="parent_field_<?php $value->name;?>"
                class="chk_module"  data-type="parent"
               value="" <?php echo set_checkbox($value->name, $value->name, ($value->is_parent==1 ? true : false)); ?> />
               <label for="parent_field_<?php echo $value->name; ?>" class="label-info-success"></label>
              </div>
            </td>
            </tr>
            <?php } }

        }
            ?>
           </tbody>
          </table>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </section>
</div>

<?php

function findSelected($inserted_fields, $find)
{
    foreach ($inserted_fields as $inserted_key => $inserted_value) {
        if ($find == $inserted_value->name && $inserted_value->status) {
            return true;
        }
    }
    return false;
}
?>

<script type="text/javascript">
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
            url: base_url + "student/changeprofilesetting",
            data: {'name': name, 'status': status},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
            }
        });
    }
</script>

<script>
   
    $(function(){
        if ($('.enableprofile').prop('checked')==true){

           $("#tblclm").css('display','block');
          }else{
            $("#tblclm").css('display','none');
          }
    });
 </script>

 <script>
    $(".enableprofile").click(function () {
        if ($(this).prop('checked')==true){
           $("#tblclm").css('display','block');
          }else{
            $("#tblclm").css('display','none');
          }      
    });


</script>

<script type="text/javascript">
$(document).ready(function() {
    $(".select2").select2();
});

 (function ($) {
     $(document).ready(function () { 
         $(document).on('click', '.chk_module', function(event) { 
         var name=$(this).attr('name');
         var status=1;
         if(this.checked) {
             status=1;
         } else {
             status=0;
         } 
        var status_type=$(this).data('type');

        if(confirm("<?php echo $this->lang->line('confirm_status'); ?>")){
            save_modules_status(name, status,status_type);
        }else{
            event.preventDefault();
        }
        }); 
     });
 
    function save_modules_status(name, status,status_type) { 
         var base_url = '<?php echo base_url() ?>'; 
         $.ajax({
             type: "POST",
             url: base_url + "student/save_student_dashboard_settings",
             data: {'name': name, 'status': status,'status_type':status_type},
             dataType: "json",
             success: function (data) {
                 successMsg(data.msg);
             }
         });
     } 
})(jQuery);
</script>
