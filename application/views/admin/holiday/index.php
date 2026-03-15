<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
 <section class="content">
  <div class="box box-primary">
   <div class="box-header with-border">
    <h3 class="box-title">  <?php echo $this->lang->line('annual_calendar'); ?></h3>
		<div class="box-tools pull-right">
			<?php if ($this->rbac->hasPrivilege('annual_calendar', 'can_add')) { ?>
			<button type="button" onclick="add_holiday()" class="btn btn-sm btn-primary" data-toggle="tooltip"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>  
			<?php } ?>
          </div>
   </div>
   <!-- Seaching section Start-->
   <form class="assign_teacher_form" action="<?php echo base_url(); ?>admin/holiday" method="post" enctype="multipart/form-data">
    <div class="box-body">
     <div class="row">
      <div class="col-md-12">
       <?php if ($this->session->flashdata('msg')) { ?>
       <?php  echo $this->session->flashdata('msg');
              $this->session->unset_userdata('msg');
        ?>
       <?php } ?>
       <?php echo $this->customlib->getCSRF(); ?>
      </div>
      <div class="col-md-5 col-lg-4 col-sm-6">
       <div class="form-group">
        <label><?php echo $this->lang->line('type'); ?>   </label><small class="req"> *</small>
        <select autofocus="" id="search_holiday_type" name="search_holiday_type" class="form-control">
         <option value=""><?php echo $this->lang->line('select'); ?> </option>
         <?php
         foreach($holiday_type as $key=>$value){ ?>
          <option value="<?php echo $value['id'];?>" <?php if($search_holiday_type == $value['id']){ echo  "selected";} ?> ><?php 
            if($value['is_default']==1){
                echo $this->lang->line(strtolower($value['type']));
            }else{
                echo $value['type'];
            } 
         ?></option>
         <?php }   ?>

        </select>
        <span class="class_id_error text-danger"><?php echo form_error('search_holiday_type'); ?></span>
       </div>
      </div>	  
	  <div class="col-md-2 col-lg-2 col-sm-3">
       <div class="form-group pl10">
           <label class="displayblock opacity d-sm-none">&nbsp;</label>
        <button type="submit" id="search_filter" name="search" value="search_filter" class="btn btn-primary smallbtn28 btn-sm float-sm-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>        
       </div>
      </div>	  
     </div>      
    </div>
   </form>
   <!-- Seaching section End-->
   <div class="row">
    <div class="col-md-12">
     <div class="box-header with-border">
      <h3 class="box-title"><?php echo $this->lang->line('calendar_list'); ?></h3>
      <div class="box-tools pull-right">
      </div>
     </div>
     <div class="box-body table-responsive">
      <div class="download_label"><?php echo $this->lang->line('calendar_list'); ?></div>
      <div>
       <table class="table table-hover table-striped table-bordered example" data-export-title="<?php echo $this->lang->line('calendar_list');?>">
        <thead>
         <tr >
          <th class="white-space-nowrap"><?php echo $this->lang->line('date'); ?></th>
          <th><?php echo $this->lang->line('type'); ?></th>          
          <th><?php echo $this->lang->line('description'); ?></th>
          <th class="white-space-nowrap"><?php echo $this->lang->line('created_by'); ?></th>
          <th class="white-space-nowrap" width="5%"><?php echo $this->lang->line('front_site'); ?></th>
          <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
         </tr>
        </thead>
        <tbody>
        <?php
 
        foreach ($holidaylist as $value) {  
            $from_date=date($this->customlib->getSchoolDateFormat(),strtotime($value['from_date']));
            $to_date=date($this->customlib->getSchoolDateFormat(),strtotime($value['to_date']));            
        ?>
        <tr>
        <td class="white-space-nowrap">
			<?php  echo date($this->customlib->getSchoolDateFormat(),strtotime($value['from_date'])). ' '. $this->lang->line('to') .' '. date($this->customlib->getSchoolDateFormat(),strtotime($value['to_date'])); ?></td>

          <td><?php if($value['is_default'] != 1) { echo $value['type']; }else{ echo $this->lang->line(strtolower($value['type'])); } ?></td>
          <td><?php echo $value['description']; ?></td>
          <td class="white-space-nowrap"> 
			<?php 				
				if ($staffrole->id != 7) {
                    if ($superadmin_restriction == 'disabled') {
                        if ($value['created_by'] == $login_staff_id) {                            
                           echo $value['name']." ".$value['surname']." (".$value['employee_id'].")"; 
                        }
                    } else {
                        echo $value['name']." ".$value['surname']." (".$value['employee_id'].")"; 
                    }
                } else {
                    echo $value['name']." ".$value['surname']." (".$value['employee_id'].")"; 
                }				 		  
			?>
			</td>	  
		  <td class="white-space-nowrap"><?php 
			if($value['front_site']==1){
				echo $this->lang->line('yes');			
			}else {
				echo $this->lang->line('no');
			}  ?></td>
			
			<td class="pull-right white-space-nowrap"> 
				<?php if ($this->rbac->hasPrivilege('annual_calendar', 'can_edit')) { ?>
					<a onclick="get('<?php echo $value['id']; ?>')" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('edit') ?>"><i class="fa fa-pencil"></i> </a>
				<?php } if ($this->rbac->hasPrivilege('annual_calendar', 'can_delete')) { ?>
					<a onclick="delete_holiday('<?php echo $value['id']; ?>');" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('delete') ?>" class="btn btn-primary btn-xs"><i class="fa fa-trash"></i> </a>
				<?php } ?>
			</td>
         </tr>
         <?php } ?>
        </tbody>
       </table>
      </div>
     </div>
    </div>
   </div>
  </div>
 </section>
</div>

<div class="modal fade" id="holiday_modal" tabindex="-1" role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
 <div class="modal-dialog " role="document">
  <div class="modal-content modal-media-content">
   <div class="modal-header modal-media-header">
    <button type="button" class="close" data-dismiss="modal" onclick="refresh()">&times;</button>
    <h4 class="box-title" id="title"></h4>
   </div>
   <form role="form" id="add_holiday" method="post" enctype="multipart/form-data" action="">
    <div class="modal-body pb0">
       <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
         <div class="form-group">
          <input type="hidden" name="id" id="id">
          <label><?php echo $this->lang->line('type'); ?></label><small class="req"> *</small>	  
          <br>
			  <ul class="stepradiolist row gy-0">
        <?php
        foreach($holiday_type as $key=>$value){ ?>
          <li class="col-lg-4 col-md-12 col-sm-4">
          <label><input class="valign-top" type="radio" id="radio_<?php echo $value['id']; ?>" name="holiday_type" value="<?php echo $value['id']; ?>"
           onchange="showDate(this.value)"/>
           <div class="stepimage"><?php 
            if($value['is_default']==1){
                echo $this->lang->line(strtolower($value['type']));
            }else{
                echo $value['type'];
            }
            ?></div></label>
          </li>
        <?php }  ?>			
       </ul>
        </div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-6 relative" id="date_one">
         <div class="form-group ">
          <label id="date_text"><?php echo $this->lang->line('from_date'); ?> </label><small class="req"> *</small>
          <div class="relative"><input id="from_date" name="from_date" placeholder="" type="text" class="form-control date" value="" /></div>
         </div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-6" id="date_two">
         <div class="form-group relative">
          <label><?php echo $this->lang->line('to_date'); ?></label><small class="req"> *</small>
          <input id="to_date" name="to_date" placeholder="" type="text" class="form-control date" value="" />
         </div>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
         <div class="form-group">
          <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small>	  
          <textarea rows="5" id="description" name="description" placeholder="" type="text" class="form-control"></textarea>
         </div>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
         <div class="form-group">
          <label for="exampleInputEmail1"><?php echo $this->lang->line('front_site'); ?></label>	  
			    <div class="material-switch ">
				  <input id="front_site" name="front_site" type="checkbox" class="chk" value="checked" />
				  <label for="front_site" class="label-info-success"></label>
          </div>
         </div>
        </div>
       </div>
    </div>
    <div class="modal-footer">
    <button class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait" value=""><?php echo $this->lang->line('save'); ?></button>
   </form>
  </div>
 </div>
</div>
</div>

<script type="text/javascript">	
  
 $("#add_holiday").on('submit', (function (e) {
      e.preventDefault();
      var $this = $(this).find("button[type=submit]:focus");
      $.ajax({
          
          url: "<?php echo site_url("admin/holiday/add") ?>",
          type: "POST",
          data: new FormData(this),
          dataType: 'json',
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function () {
              $this.button('loading'); 
          },
          success: function (res)
          {
             if (res.status == "fail") {                
                  var message = "";
                  $.each(res.error, function (index, value) {
                      message += value;
                  });
                  errorMsg(message);
  
              } else {
                  successMsg(res.message);
                  window.location.reload(true);
              }
          },
          error: function (xhr) { // if error occured
              alert("Error occured.please try again");
              $this.button('reset');
          },
          complete: function () {
              $this.button('reset');
          }
      });
 }));  
 
 function add_holiday() {
      $('#add_holiday').trigger("reset");
      $('input[name="holiday_type"]').prop('checked', false);
      $("#front_site").attr("checked" , false );
      $('#title').html('<?php echo $this->lang->line('add_holiday'); ?>');
      $('#holiday_modal').modal({
          backdrop: 'static',
          keyboard: false,
          show: true
      });
      // showDate('');
  }
 
		function get(id) {

			var base_url = '<?php echo base_url() ?>';
			$('#title').html('<?php echo $this->lang->line('edit_holiday'); ?>');
				$('#holiday_modal').modal({
							backdrop: 'static',
							keyboard: false,
							show: true
				});		 
				  
			$.ajax({
				url: base_url+'admin/holiday/getholiday',
				type: "POST",
				data: {id: id},
				dataType: 'json',
				beforeSend: function(){
					 
				},
				success: function (data) {
					if (data.status == 0) {                     
                         errorMsg(message);
                    } else { 
						// showDate(data.result.holiday_type);
                        $('#from_date').val(data.result.from_date);
                        $('#id').val(data.result.id); 
                        $('#description').val(data.result.description); 
                        $('#to_date').val(data.result.to_date);
						 
						if(data.result.front_site == '1'){
							$('#front_site').attr('checked', 'checked'); 
                        }                         
						
						$("#radio_"+data.result.holiday_type).attr('checked', 'checked'); 
						$('#myModal').modal('show');						 
					}					 
				}, 
				error: function () {
					 
				},
				complete: function(){
                    
                }
			});			  
		}
 
 function delete_holiday(id) {
      var confirmation = confirm('<?php echo $this->lang->line('delete_confirm') ?>');
      if (confirmation == true) {
          $.ajax({
              url: "<?php echo site_url("admin/holiday/delete_holiday") ?>",
              type: "POST",
              data: {'id': id},
              dataType: "json",
              success: function (res)
              {
                  if (res.status == 0) {
                      errorMsg(res.error);
                  } else {
                      successMsg(res.success);
                      window.location.reload(true);
                  }
              }
          });
      }
  }
 
</script>


