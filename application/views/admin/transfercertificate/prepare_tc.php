<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

?>
<div class="content-wrapper">
 <section class="content-header">
  <h1><i class="fa fa-newspaper-o"></i></h1>
 </section> 
 <section class="content">
  <?php if ($this->session->flashdata('msg')) { ?>
  <?php echo $this->session->flashdata('msg');
            $this->session->unset_userdata('msg');
        ?>
  <?php } ?>
  <div class="row">
   <div class="col-md-12">
    <div class="box box-primary">
     <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
	  
			
													
     </div>
     <div class="box-body">
      <div class="row">
       <form role="form" action="<?php echo site_url('admin/transfercertificate/prepare_tc') ?>" method="post" class="">
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="col-sm-6">
         <div class="form-group">
          <label><?php echo $this->lang->line('class'); ?> </label><small class="req"> *</small>
          <select autofocus="" id="class_id" name="class_id" class="form-control">
           <option value=""><?php echo $this->lang->line('select'); ?></option>
           <?php
            foreach ($classlist as $class) {  ?>
           <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
           <?php } ?>
          </select>
          <span class="text-danger"><?php echo form_error('class_id'); ?></span>
         </div>
        </div>
        <div class="col-sm-6">
         <div class="form-group">
          <label><?php echo $this->lang->line('section'); ?></label>
          <select id="section_id" name="section_id" class="form-control">
           <option value=""><?php echo $this->lang->line('select'); ?></option>
          </select>
          <span class="text-danger"><?php echo form_error('section_id'); ?></span>
         </div>
        </div>
        <div class="col-sm-12">
         <div class="form-group">
          <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
         </div>
        </div>
       </form>
      </div>
     </div>
     <?php
    if (isset($resultlist)) {  ?>
     <form method="post" action="<?php //echo base_url('admin/resume/printresume') ?>" id="printallresume">
      <div class="" id="duefee">
       <div class="box-header ptbnull"></div>
       <div class="box-header ptbnull">
        <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
        <button class="btn btn-info btn-sm printSelected pull-right hidden" type="submit" name="generate" title="generate multiple certificate" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait') ; ?>"> <?php echo $this->lang->line('bulk_download'); ?></button>
       </div>
       <div class="box-body table-responsive overflow-visible">
        <div class="download_label"><?php echo $this->lang->line('student_list'); ?></div>
        <div class="tab-pane active table-responsive no-padding" id="tab_1">
         <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('student_list');?>">
          <thead>
           <tr>
            <th class="dt-body-left dt-head-left"><?php echo $this->lang->line('admission_no'); ?></th>
            <th><?php echo $this->lang->line('student_name'); ?></th>
            <th><?php echo $this->lang->line('date_of_birth'); ?></th>
            <th><?php echo $this->lang->line('gender'); ?></th>
            <th><?php echo $this->lang->line('category'); ?></th>
            <th><?php echo $this->lang->line('mobile_number'); ?></th>
            <th><?php echo $this->lang->line('action'); ?></th>
           </tr>
          </thead>
          <tbody>
           <?php if (empty($resultlist)) {

           } else {
             
            $count = 1;
            foreach ($resultlist as $student) {
            ?>
           <tr>
            <td class="dt-body-left dt-head-left"><?php echo $student['admission_no']; ?></td>
            <td><a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $student_name=$this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting->middlename,$sch_setting->lastname); ?></a>
            </td>
            <td>
            <?php 
            if(!empty($student['dob'])){ 
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob'])); 
            } ?>
            </td>
            <td><?php if($student['gender']){ echo $this->lang->line(strtolower($student['gender'])); } ?></td>
            <td><?php echo $student['category']; ?></td>
            <td><?php echo $student['mobileno']; ?></td>
            <td>
            <a href="<?php echo base_url('admin/transfercertificate/edit_custom_field/'.$student['id']) ?>" target="_blank" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>" ><i class="fa fa-reorder"></i></a>
            </td>
           </tr>
           <?php
           $count++;
            }
        }
        ?>
          </tbody>
         </table>
        </div>
       </div>
      </div>
     </form>
     <?php }  ?>
    </div>
   </div>
  </div>
 </section>
</div>

<script type="text/javascript">
 $(document).on('click','.download_pdf',function(){
    var admission_no = $(this).attr('data-admission_no');
    var student_name = $(this).attr('data-student_name');
    let $button_     = $(this);
    var student_id   = $button_.data('student_id');
    var student_session_id   = $button_.data('student_session_id');
    var is_regenerte         = $("#is_regenerte_"+student_session_id).is(':checked');

    if(is_regenerte==true){
        is_regenerte=1;
    }else{
        is_regenerte=0;
    }

    var action       = ($button_.data('action'));
  
     $.ajax({
        type: 'POST',
        url: baseurl+'admin/transfercertificate/printpdfresume',
        data: {
            'type':action,
            'student_id':student_id,
            'student_session_id':student_session_id,
            'is_regenerte':is_regenerte,
        },         
        beforeSend: function() { 
           $button_.button('loading');    
        },
         xhr: function () {// Seems like the only way to get access to the xhr object
            var xhr = new XMLHttpRequest();
            xhr.responseType = 'blob'
            return xhr;
        },
       success: function (data, jqXHR, response) {    
               var blob = new Blob([data], {type: 'application/pdf'});
               var link = document.createElement('a');
               link.href = window.URL.createObjectURL(blob);
               link.download =  student_name+'_'+admission_no;
               document.body.appendChild(link);
               link.click();
               document.body.removeChild(link);
               $button_.button('reset');
        },
        error: function(xhr) { // if error occured
            $button_.button('reset');
        },
        complete: function() {             
            $button_.button('reset');
        }
    });
});
 
 //get the section
 $(document).ready(function () {
    var class_id = $('#class_id').val();
    var section_id = '<?php echo set_value('section_id') ?>';
    getSectionByClass(class_id, section_id);
    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                $('#section_id').append(div_data);
            }
        });
    });
     });
 //get the section
 function getSectionByClass(class_id, section_id) {       
    if (class_id != "" && section_id != "") {
        $('#section_id').html("");
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                });
                $('#section_id').append(div_data);
            }
        });
    }
}
 

</script>