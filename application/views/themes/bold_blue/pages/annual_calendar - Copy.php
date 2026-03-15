<link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
<div class="row justify-content-center align-items-center flex-wrap d-flex pt20">
 <div class="col-md-6 col-lg-5 col-sm-5">
  <h3 class="entered mt0 pb10"><?php echo $this->lang->line('annual_calendar'); ?></h3>
 </div>
 <div class="col-md-6 col-lg-7 col-sm-7 text-lg-right">
 </div>
</div>
<?php
if(!empty($holiday_arr)){
foreach($holiday_arr as $key=>$value){ if(!empty($value)){ ?>
<div class="calender-wrap">
  <div class="row">
  <div class="col-md-12"><h4><?php echo $key; ?></h4></div>
   <?php foreach($value as $key=>$val){ ?>
   <div class="col-md-12 calender-inner">
   <div class="calender-inner">
      <p>
      <strong>
      <?php      
        echo date($this->customlib->getSchoolDateFormat(),strtotime($val['from_date']))." ".$this->lang->line('to')." ".date($this->customlib->getSchoolDateFormat(),strtotime($val['to_date']));
      ?>
     </strong>
        &nbsp; - &nbsp; <?php echo $val['description'];?>
      </p>
   </div>  
   </div>
   <?php  }  ?>
  </div>
</div>
<?php 
} }
} 
?>

