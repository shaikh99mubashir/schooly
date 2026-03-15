<div class="row">
<?php foreach($attachment_list as $key=>$value){ ?>
<div class='col-lg-2 col-sm-4 col-md-3 col-xs-6 img_div_modal image_div' >      
       <div class='fadeoverlay'>
        <div class='fadeheight'>  
        <a href="<?php echo base_url('./uploads/communicate/email_template_images/'.$value['attachment']);?>" class="btn btn-xs pull-right" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>" download>
        <img  src="<?php echo base_url('./uploads/communicate/email_template_images/'.$value['attachment']);?>">
       </a>   
        </div>   
       </div>
        <p class='fadeoverlay-para'><?php echo $value['attachment_name'];?></p>
</div>
 <?php } ?>
 </div>