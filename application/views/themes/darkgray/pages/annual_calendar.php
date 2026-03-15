<link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css" />
<script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
<div class="row justify-content-center align-items-center flex-wrap d-flex pt20">
 <div class="col-md-6 col-lg-5 col-sm-5">
  <h3 class="entered mt0 pb20"><?php echo $this->lang->line('annual_calendar'); ?></h3>
 </div>
 <div class="col-md-6 col-lg-7 col-sm-7 text-lg-right text-md-right">
  <div class="pb20">
          <a href="#" id="list" class="btn btn-default btn-sm">
          <span class="fa fa-list"></span> <?php echo $this->lang->line('list'); ?></a>
          <a href="#" id="table" class="btn btn-default btn-sm">
          <span class="fa fa-th"></span> <?php echo $this->lang->line('table'); ?></a>
    </div>
 </div>
</div>

<div id="products">
  <div class="row justify-content-center align-items-stretch flex-wrap d-flex"> 
  <?php
  if(!empty($holiday_arr)){
  foreach($holiday_arr as $key=>$value){ if(!empty($value)){ ?>
    
      <div class="item col-lg-12 col-md-12 col-sm-12 w-100">
        <div class="thumbnail2">
          <div class="calender-wrap">
            <div class="">
				<h4>
					<?php  
						if($value[0]['is_default'] != 1) { echo $key; }else{ echo $this->lang->line(strtolower($key)); }
					?>
				</h4>
			</div>
             <?php foreach($value as $key=>$val){ ?>
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
             <?php  }  ?>

          </div>
        </div>  
      </div>  
     
  <?php 
    } }
    } 
    ?>
  </div> 
</div>

<div id="products_list" class="hide">
 <div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="item">
      <div class="table-responsive">
        <table class="table table-hover">
         <thead>
          <tr>
           <th width="20%"><?php echo $this->lang->line('date'); ?></th>
           <th width="20%"><?php echo $this->lang->line('holiday_type'); ?></th>
           <th width="60%"><?php echo $this->lang->line('description'); ?></th>
          </tr>
         </thead>
         <tbody>
      <?php
      if(!empty($all_holidays)){
      foreach($all_holidays as $key=>$val){ ?>
      <tr>
        <td><?php   echo date($this->customlib->getSchoolDateFormat(),strtotime($val['from_date']))." ".$this->lang->line('to')." ".date($this->customlib->getSchoolDateFormat(),strtotime($val['to_date'])); ?></td>
       <td><?php 
            if($val['is_default']==1){
                echo $this->lang->line(strtolower($val['type']));
            }else{
                echo $val['type'];
            }  ?></td>
        <td><?php echo $val['description'];?></td>
      </tr>

     <?php
      } } 
    ?>
          </tbody>
        </table>
      </div>
    </div>    
  </div>
 </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
      $('#list').click(function(event){
          event.preventDefault();
          $('#products .item').addClass('list-group-item1');
           $('#products').removeClass('hide');
                     $('#products_list').addClass('hide');

      });

      $('#grid').click(function(event){
          event.preventDefault();
          $('#products .item').removeClass('list-group-item1');
          $('#products .item').addClass('grid-group-item');
          $('#products').removeClass('hide');
                    $('#products_list').addClass('hide');

      });

      $('#table').click(function(event){
          event.preventDefault();
          $('#products').addClass('hide');
          $('#products_list').removeClass('hide');
      });

});
</script>
