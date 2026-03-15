<?php $cookie_consent = $this->customlib->cookie_consent();
if (!empty($cookie_consent)) {?>
<div id="cookieConsent" class="cookieConsent">
    <?php echo $cookie_consent; ?> <a href="<?php echo base_url() . "page/cookie-policy" ?>" target="_blank" ></a> <a onclick="setsitecookies()" class="cookieConsentOK"><?php echo $this->lang->line('accept') ?></a>
</div>
<?php }?>

<footer>
<?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course')) { ?>        
    <script src="<?php echo base_url(); ?>backend/js/online_course.js"></script>           
<?php } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <h3 class="fo-title"><?php echo $this->lang->line('follow_us'); ?></h3>
                <ul class="company-social">
                    <?php $this->view('/themes/default/social_media');?>
                </ul>
            </div><!--./col-md-7-->
            <div class="col-md-4 col-sm-4">
                <h3 class="fo-title"><?php echo $this->lang->line('links'); ?></h3>
                <ul class="f1-list">
                    <?php
foreach ($footer_menus as $footer_menu_key => $footer_menu_value) {

    $cls_menu_dropdown = "";
    if (!empty($footer_menu_value['submenus'])) {
        $cls_menu_dropdown = "dropdown";
    }
    ?>
                        <li class="<?php echo $cls_menu_dropdown; ?>">
                            <?php
$top_new_tab = '';
    $url         = '#';
    if ($footer_menu_value['open_new_tab']) {
        $top_new_tab = "target='_blank'";
    }
    if ($footer_menu_value['ext_url']) {
        $url = $footer_menu_value['ext_url_link'];
    } else {
        $url = site_url($footer_menu_value['page_url']);
    }
    ?>
                            <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo $footer_menu_value['menu']; ?></a>

                            <?php
?>
                        </li>
                        <?php
}
?>
                </ul>
            </div><!--./col-md-5-->
            <div class="col-md-4 col-sm-4">
                <h3 class="fo-title"><?php echo $this->lang->line('feedback'); ?></h3>
                <?php
if (in_array('complain', json_decode($front_setting->sidebar_options))) {
    ?>
                    <div class="complain">
                        <a href="<?php echo site_url('page/complain') ?>"><i class="fa fa-pencil-square"></i><?php echo $this->lang->line('complain'); ?></a>
                    </div><!--./complain-->
                    <?php
}
?>
            </div><!--./col-md-3-->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="infoborderb"></div>
            </div><!--./col-md-12-->
            <div class="col-md-4">
                <div class="contacts-item">
                    <div class="cleft"><i class="fa fa-phone"></i></div>
                    <div class="cright">
                        <a href="#" class="content-title"><?php echo $school_setting->phone; ?></a>

                    </div>
                </div>
            </div><!--./col-md-4-->
            <div class="col-md-4">
                <div class="contacts-item">
                    <div class="cleft"><i class="fa fa-envelope"></i></div>
                    <div class="cright">
                        <a href="mailto:<?php echo $school_setting->email; ?>" class="content-title"><?php echo $school_setting->email; ?></a>
                    </div>
                </div>
            </div><!--./col-md-4-->
            <div class="col-md-4">
                <div class="contacts-item">
                    <div class="cleft"><i class="fa fa-map-marker"></i></div>
                    <div class="cright">
                        <a href="#" class="content-title"><?php echo $this->lang->line('address'); ?></a>
                        <p class="sub-title"><?php echo $school_setting->address; ?></p>
                    </div>
                </div>
            </div><!--./col-md-4-->
        </div>
    </div><!--./container-->
    <div class="copy-right">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <p><?php echo $front_setting->footer_text; ?></p>
                </div>
            </div><!--./row-->
        </div><!--./container-->
    </div><!--./copy-right-->
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</footer>

<?php if($setting_data[0]['front_side_whatsapp']){ 
	$waurl = "https://wa.me/";
	$mobile = $setting_data[0]['front_side_whatsapp_mobile'];	
	 
	$url = $waurl.$mobile;
	$today = strtotime(date("H:i:s")); 
	
	$show_hide = 1;
	if($setting_data[0]['front_side_whatsapp_from'] != '' && $setting_data[0]['front_side_whatsapp_to'] != ''){
		$front_side_whatsapp_from = strtotime($setting_data[0]['front_side_whatsapp_from']);
		$front_side_whatsapp_to = strtotime($setting_data[0]['front_side_whatsapp_to']);
		if($today>=$front_side_whatsapp_from && $today<=$front_side_whatsapp_to){
			$show_hide = 1;
		}else{
			$show_hide = 0;
		}
	}
	
	if($show_hide){
?>

<a href="<?php echo $url; ?>" class="bootom-whatsapp" target="_blank">
<svg height="38px" width="38px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
<path style="fill:#fff;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
    S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"></path>
<path style="fill:#55CD6C;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
    c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
    c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"></path>
<path style="fill:#fff;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
    c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
    c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
    c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
    c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
    l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"></path>
</svg></a>

<?php } } ?>

<script>
    function setsitecookies() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>welcome/setsitecookies",
            data: {},
            success: function (data) {
                $('.cookieConsent').hide();
            }
        });
    }

    function check_cookie_name(name)
    {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) {
            console.log(match[2]);
            $('.cookieConsent').hide();
        }
        else{
           $('.cookieConsent').show();
        }
    }
    check_cookie_name('sitecookies');
</script>