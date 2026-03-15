<h1><?php echo $page['title']; ?></h1>
<p>
	<?php if($page['event_venue']){ echo $this->lang->line('venue') .': '. $page['event_venue']; } ?>
	<br>
	<?php 
		if($page['event_start']){ echo $this->lang->line('date') .': '. date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($page['event_start'])); } 
		if($page['event_end']){ echo ' - '. date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($page['event_end'])); } 
	?>
    <?php
    if($page['date']){ echo $this->lang->line('date') .': '. date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($page['date'])); } 
    ?>
</p>
<p><?php echo $page['description']; ?></p>
<div class="mediarow">
    <div class="row">
        <?php
if (isset($page['page_contents'])) {
    echo "<div class='gallery'>";
    foreach ($page['page_contents'] as $page_content_key => $page_content_value) {

        $url = base_url($page_content_value->dir_path . $page_content_value->img_name);

        if ($page_content_value->file_type == "video") {
            $url = $page_content_value->vid_url;
        }
        ?>
                <div class="col-sm-6 col-md-4 col-lg-4 img_div_modal">
                    <div class="galleryfancy">
                        <div class="gallheight">
                            <a href="<?php echo $url ?>" data-toggle="lightbox" data-gallery="mixedgallery"  data-title="a" >
                                <img  alt="" src="<?php echo $url ?>">
                                <div class="content-overlay"></div>
                                <div class="overlay-details fadeIn-bottom">
                                    <i class="fa fa-search-plus"></i>
                                </div></a>
                        </div>
                    </div>
                </div><!--./col-md-3-->
                <?php
}
    echo "</div>";
}
?>
    </div>
</div>