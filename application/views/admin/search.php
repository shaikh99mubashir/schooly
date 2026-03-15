<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> <?php echo $this->lang->line('search_student'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                    <div class="nav-tabs-custom theme-shadow">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('student_list'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details_view'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <div class="download_label"><?php echo $this->lang->line('student_list'); ?></div>
                                 <table class="table table-striped table-bordered table-hover header-student-list" data-export-title="<?php echo $this->lang->line('student_list'); ?>">
                                    <thead> 
                                        <tr>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                             <?php if ($sch_setting->roll_no) { ?>
                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <?php if ($sch_setting->father_name) { ?>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                            <?php if ($sch_setting->category) { ?>
                                                <th><?php echo $this->lang->line('category'); ?></th>
                                            <?php }if ($sch_setting->mobile_no) { ?>
                                                <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                            <?php } ?>

                                            <?php
                                            if (!empty($fields)) {

                                                foreach ($fields as $fields_key => $fields_value) {
                                                    ?>
                                                    <th><?php echo $fields_value->name; ?></th>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab_2">
                            
                            </div>
                        </div>
                    </div>
                
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
     
     var search_text = <?= json_encode($search_text); ?>;

     if(search_text!=""){
        search_text = search_text
     }else{
        search_text=0
     }
     
     $.ajax({
           url: base_url+'admin/admin/search_text',
           type: "POST",
           dataType:'JSON',
           data: {search_text:'<?php echo $search_text; ?>'}, // serializes the form's elements.
              beforeSend: function () {               
              
               },
              success: function(response) { // your success handler
                
                if(!response.status){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value); 
                    });
                }else{
                 initDatatable_page('header-student-list','admin/admin/dtstudentlist',response.params,[],50,[
              {
                                    targets: [0],
                                    orderable: true,
                                    className: 'dt-body-left dt-head-left'
                                },
                                {
                                    targets: [-2],
                                    orderable: true,
                                    className: 'dt-body-right'
                                },
                                {
                                    targets: [-1],
                                    orderable: false,
                                    className: 'dt-right dt-body-right'
                                }
                            
                ],true,[],"data",'landscape');
               
                }
              },
             error: function() { // your error handler
                
             },
             complete: function() {
             
             }
         });      

});


</script>