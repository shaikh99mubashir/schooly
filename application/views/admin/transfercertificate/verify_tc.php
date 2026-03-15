
<div class="content-wrapper">
 <section class="content-header">
  <h1><i class="fa fa-newspaper-o"></i></h1>
 </section> 
 <section class="content">
   
  <div class="row">
   <div class="col-md-12">
    <div class="box box-primary">
     <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('verify_tc'); ?></h3>
     </div>
     <div class="box-body">
	 
		<div class="row">
                            <div class="col-md-12">
                                <form role="form" action="<?php echo site_url('admin/transfercertificate/verify_tc') ?>" method="post" class="form-inline">
                                    <?php echo $this->customlib->getCSRF(); ?>                                   
									<div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('enter_tc_no'); ?>                                          </label><small class="req"> *</small>
                                            <input id="student_tc_no" name="student_tc_no" class="form-control" value="<?php echo $student_tc_no;?>">
                                            <span class="text-danger"><?php echo form_error('student_tc_no'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group align-text-top">
                                        <div class="col-sm-12">
                                            <button type="submit" name="search" value="" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
						
						
      
     </div>
    <?php if(!empty($check_is_tc_exist) && !empty($resultlist)){ ?>
     <div class="row">
        <div class="col-md-12">
            <?php echo $html;?>
        </div>
     </div>
    <?php }elseif(!empty($student_tc_no)){
            ?>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12 alert alert-danger">
					No Record Found
				</div>
			</div>
		</div>
		<?php
    } ?>
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
        url: baseurl+'admin/transfercertificate/print_transfer_certificate',
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

</script>