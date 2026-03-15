<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('content'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                <?php
if(isset($content->share_date) && strtotime(date('Y-m-d')) >= strtotime($content->share_date) && (IsNullOrEmptyString($content->valid_upto) || (!IsNullOrEmptyString($content->valid_upto) && strtotime(date('Y-m-d')) <= strtotime($content->valid_upto)))){
    ?>
        <h4 class="mt0">
                           <?php echo $content->title; ?>
                      </h4>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4"><label><?php echo $this->lang->line('upload_date'); ?></label> : <?php echo $this->customlib->dateformat($content->share_date); ?></div>
    <div class="col-lg-4 col-md-4 col-sm-4"><label><?php echo $this->lang->line('valid_upto'); ?></label> : <?php echo $this->customlib->dateformat($content->valid_upto); ?></div>

    <?php if ($superadmin_restriction == 'enabled' || $content->role_id != 7) {?>
        <div class="col-lg-4 col-md-4 col-sm-4"><label><?php echo $this->lang->line('shared_by'); ?></label> : <?php echo $this->customlib->getStaffFullName($content->name, $content->surname, $content->employee_id); ?></div>

    <?php }?>

    <div class="col-md-3">
        <p><label><?php echo $this->lang->line('share_date'); ?></label>: <?php echo $this->customlib->dateformat($content->share_date); ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12"><label><?php echo $this->lang->line('description'); ?></label> : <?php echo $content->description; ?></div>
</div>
      <div class="row">
                                    <div class="col-md-12">
                                         <h4 class="box-title"><?php echo $this->lang->line('attachments'); ?></h4>

                                                        <?php

    if (!empty($content->upload_contents)) {
        ?>
 <ul class="list-group content-share-list">
                                                          <?php

        foreach ($content->upload_contents as $content_key => $content_value) {

            ?>
 <li class="list-group-item" data-style="button" style="cursor: pointer;">

    <span class="div_image" 
                    data-recordid="<?php echo $content_value->upload_content_id; ?>"  
                    data-realname="<?php echo $content_value->real_name; ?>"  
                    data-shortname="<?php echo $this->media_storage->fileview($content_value->img_name); ?>"  
                    data-filetype="<?php echo $content_value->file_type; ?>"   
                    data-name="<?php echo ($content_value->file_type == "video") ? $content_value->vid_url: $content_value->img_name; ?>"  
                    data-path="<?php echo $content_value->dir_path; ?>" >  <a href="#">


        <?php
if ($content_value->file_type == 'xls' || $content_value->file_type == 'xlsx') {
                ?>
     <img src="<?php echo base_url('backend/images/excelicon.png'); ?>">
<?php
} elseif ($content_value->file_type == 'ppt' || $content_value->file_type == 'pptx') {
                ?>
     <img src="<?php echo base_url('backend/images/pptxicon.png'); ?>">
<?php
} elseif ($content_value->file_type == 'doc' || $content_value->file_type == 'docx') {
                ?>
     <img src="<?php echo base_url('backend/images/wordicon.png'); ?>">
<?php
} elseif ($content_value->file_type == "csv") {
                ?>
     <img src="<?php echo base_url('backend/images/txticon.png'); ?>">
<?php
} elseif ($content_value->file_type == "pdf") {
                ?>
     <img src="<?php echo base_url('backend/images/pdficon.png'); ?>">
<?php
} elseif ($content_value->file_type == "text/plain") {
                ?>
     <img src="<?php echo base_url('backend/images/txticon.png'); ?>">
<?php
} elseif ($content_value->file_type == "zip" || $content_value->file_type == "rar") {
                ?>
     <img src="<?php echo base_url('backend/images/zipicon.png'); ?>">
<?php
} elseif ($content_value->file_type == 'video' || $content_value->file_type == 'gif' || $content_value->file_type == 'jpeg' || $content_value->file_type == 'jpg' || $content_value->file_type == 'jpe' || $content_value->file_type == 'jp2' || $content_value->file_type == 'j2k' || $content_value->file_type == 'jpf' || $content_value->file_type == 'jpg2' || $content_value->file_type == 'jpx' || $content_value->file_type == 'jpm' || $content_value->file_type == 'mj2' || $content_value->file_type == 'mjp2' || $content_value->file_type == 'png' || $content_value->file_type == 'tiff' || $content_value->file_type == 'tif') {
                ?>
     <img src="<?php echo base_url($content_value->thumb_path . $content_value->thumb_name); ?>">
<?php
} elseif ($content_value->file_type == '3g2' || $content_value->file_type == '3gp' || $content_value->file_type == 'mp4' || $content_value->file_type == 'm4a' || $content_value->file_type == 'f4v' || $content_value->file_type == 'flv' || $content_value->file_type == 'webm') {
                ?>
   <img src="<?php echo base_url('backend/images/video-icon.png'); ?>">
<?php
} else {
                ?>
     <img src="<?php echo base_url('backend/images/docsicon.png'); ?>">
<?php
}
            if ($content_value->file_type == "video") {
                ?>
          <a href="<?php echo $content_value->vid_url; ?>" target="_blank">
             <?php echo $content_value->vid_title; ?>   
          </a>

    
         </li>
      <?php
} else {
                ?>

           <?php echo $content_value->real_name; ?></a></span>
          <a data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download'); ?>" href="<?php echo site_url('site/download_content/' . $content_value->upload_content_id . "/" . $this->enc_lib->encrypt($content_value->share_content_id)) ?>">
             <?php //echo ($content_value->file_type == "video") ? $content_value->vid_title : ($content_value->real_name); ?> <i class="fa fa-download"></i> </a>
         </li>

      <?php
}  

        }
        ?>
                                                        </ul>
                                                      <?php

    } else {
        ?>
<div class="alert alert-info">
  <?php echo $this->lang->line('no_record_found'); ?>
</div>
                                                          <?php
}

    ?>

                                    </div>
                        </div>
  <?php
} else {
    ?>
   <div class="alert alert-danger"><?php echo $this->lang->line('sorry_this_link_is_invalid_or_expired_please_contact_to_system_admin'); ?></div>
                                          <?php
}
 
?>  

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- view modal for pdf and other document presentation new code -->
<div id="viewModel" class="modal fade bg-transparent-alpha" role="dialog">
  <div class="modal-dialog full-width mt0">
    <div class="modal-content m-0 bg-transparent modal-body-scroll">
        <div class="modal-gradient">
          <div class="p0 border0">
            <div class="d-flex pdficon">
                <a href="#" data-dismiss="modal"><i class="fa fa-arrow-left"></i></a>
                <span class="text-white text-nowrap2 model_file_name"></span>
            </div>
            <a href="#" class="pdfdownload-icon"><i class="fa fa-download"></i></a>
            <button type="button" class="popupclose" data-dismiss="modal">&times;</button>
          </div>
        </div>
        <div class="h-50"></div>
            <div class="modal-body p0 w-75 mx-auto text-center w-sm-100 ">
            </div>
        </div>
    </div>
  </div>
<!-- view modal for pdf and other document presentation new code -->

<script type="text/javascript">
var branch_base_url="<?php echo $branch_url; ?>";

$(document).on('click','.div_image',function(){
    let fileType=$(this).data('filetype');
    let real_name=$(this).data('realname');
    let file_upload_name=$(this).data('name');
    let filepath=$(this).data('path');
    let recordId=$(this).data('recordid');

    $('.pdfdownload-icon').attr('href', baseurl  + "user/content/download_content/"+recordId);
    $('.model_file_name').text(real_name);
    let modal_view=false;
      
        if(fileType == "jpg" || fileType == "jpeg" ||  fileType == "png"    || fileType ==  "svg" || fileType == "webp" || fileType == "gif"){
                    modal_view=true;;
                var img = $('<img />', {
                  id: 'image',
                   width: 'auto',
                    height: 'auto',
                    class: 'img-fluid',
                  src: branch_base_url+filepath+file_upload_name,
                  alt: real_name
                });
        $('#viewModel .modal-body').html(img)
        }else if(fileType == "pdf"){
        modal_view=true;
        var pdf = $('<embed />', {
                  src: branch_base_url+filepath+file_upload_name+"#toolbar=0",
                  width: '100%',
                  height: '100vh',

                });
        $('#viewModel .modal-body').html(pdf)

        }else if(fileType == "mp4" || fileType == "webm" || fileType == "3gp" || fileType == "m4a" ){
        modal_view=true;
       var video = $('<video />', {
                  src: branch_base_url+filepath+file_upload_name,
                   width: '100%',
                  height: '80vh',
                  controls: 'controls',

                });
        $('#viewModel .modal-body').html(video)

        }else if(fileType == "video" ){
        modal_view=true;
        var youtubeID = YouTubeGetID(file_upload_name);

          content_popup = '<object data="https://www.youtube.com/embed/' + youtubeID + '" width="100%" height="400"></object>';
        $('#viewModel .modal-body').html(content_popup);

        }else if(fileType == "mp4" || fileType == "webm" || fileType == "3gp" || fileType == "m4a" ){
        modal_view=true;
       var video = $('<video />', {
                  src: branch_base_url+filepath+file_upload_name,
                  controls: 'controls',

                });
        $('#viewModel .modal-body').html(video)

        }
        if(modal_view){
        $('#viewModel').modal('show');
        }
});


</script>