<!DOCTYPE html>
<html <?php echo $this->customlib->getRTL(); ?>>

<?php
$is_lock_panel   = check_lock_enabled();
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$role            = $this->customlib->getUserRole();

?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="theme-color" content="#424242" />
        <link rel="stylesheet" href="<?php echo base_url('theme.css'); ?>">
		<script>
        // === Load PHP theme settings safely ===
        const rawThemeSettings = <?php echo json_encode($this->customlib->getCurrentThemeSetting() ?? new stdClass()); ?>;
        const themeSettings = rawThemeSettings || {};
        // console.log(themeSettings);
        (function() {

            // === Extract settings with fallback defaults ===
            const themeBackground = themeSettings.theme_background || 'light-mode';
            const savedLayout = themeSettings.theme_content || 'container-fluid';
            const themeShadow = themeSettings.theme_shadow || '';
            const themeColor = themeSettings.theme_color || '#7367f0';
            const themeNavigation = themeSettings.theme_navigation || "expanded";

            const themeClass = themeBackground === 'dark' ? 'dark' : 'light-mode';
            const themeClassNavigation = themeNavigation === 'collapsed' ? 'sidebar-collapse' : '';

            // === Apply theme, shadow, and navigation classes to <body> ===
            function applyBodyClasses() {
                if (!document.body) return;																											   

                const classList = document.body.classList;		

                // Remove existing theme and shadow classes
                classList.remove('light-mode', 'dark', 'sidebar-collapse');
                [...classList].forEach(cls => {
                    if (cls.startsWith('shadow')) classList.remove(cls);
                });															
																			  

                classList.add(themeClass);												   
																																 

                if (themeShadow) {
                    classList.add(themeShadow);
                }
				 

                if (themeClassNavigation) {
                    classList.add(themeClassNavigation);
                }
            }

            // === Apply layout and update icons ===
            function applyLayoutAndIcons() {
                const content = document.querySelector("section.content");



                if (content && !content.classList.contains(savedLayout)) {
                    content.classList.add(savedLayout);
                }


                // Stop observing once applied
                if (content) {
                    layoutObserver.disconnect();
                }
            }

            // === Watch for body and content appearance if not yet in DOM ===
            if (document.body) {
                applyBodyClasses();
            } else {
                new MutationObserver((mutations, observer) => {
                    if (document.body) {
                        applyBodyClasses();
                        observer.disconnect();
                    }
                }).observe(document.documentElement, {
                    childList: true
                });
            }

            const layoutObserver = new MutationObserver(applyLayoutAndIcons);

            layoutObserver.observe(document.documentElement, {
                childList: true,
                subtree: true,
            });

            // Try applying immediately in case elements are already in DOM
            applyLayoutAndIcons();

        })();
    </script>	
		 <?php
    $this->load->view('layout/theme-color');
    ?>	 
		
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">

        <?php
$sch_setting = $this->setting_model->getSetting();
$this->load->view('layout/theme');
?>

        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css">

        <?php
if ($this->customlib->getRTL() != "") {
    ?>
            <!-- Bootstrap 3.3.5 RTL -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
            <!-- Theme RTL style -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/admin-rtl.min.css" />
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />

            <?php
} else {

}
?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/sweet-alert/sweetalert2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
        <!--file dropify-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
        <!--file nprogress-->
        <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">
        <!--print table-->
        <!--<link href="<?php echo base_url(); ?>backend/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet"> -->
        <!--language css-->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/bootstrap-select.min.css">
        <!--print table mobile support-->
		
		
        <!--<link href="<?php echo base_url(); ?>backend/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">-->
		
		
        <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
        <script language="javascript" src="<?php echo base_url('backend/custom/jquery-2.2.4.js'); ?>"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>

        <!-- date.js script commented on date 2025-09-23 by webfeb-->
        <!-- <script src="<?php //echo base_url(); ?>backend/datepicker/date.js"></script> -->
        <!-- date.js script commented on date 2025-09-23 by webfeb-->

        <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>

        <!-- fullCalendar -->
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css" media="print">
         <link rel="stylesheet" href="<?php echo base_url() ?>backend/dist/css/lucide-static@0.543.0/lucide.css">
        <script type="text/javascript">
            var baseurl = "<?php echo base_url(); ?>";
            var start_week="<?php echo $this->customlib->getStartWeek(); ?>";
        </script>

        <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasModule('online_course')) {?>
        <script src="<?php echo base_url(); ?>backend/js/studentcourse.js"></script>
        <?php }?>

    </head>
    <script>
         var baseurl = "<?php echo base_url(); ?>";
    </script>

    <body class="hold-transition skin-blue fixed sidebar-mini">
         <?php
if ($this->config->item('SSLK') == "") {
    ?>
 <div class="topaleart">
    <div class="slidealert">
    <div class="alert alert-dismissible topaleart-inside">

   <p class="palert"><strong>Alert!</strong> You are using unregistered version of Smart School.</p>
</div></div>
</div>
                    <?php
}
?>
<script>

   
</script>
        <div class="wrapper">
            <header class="main-header" id="alert">
<?php
if ($role == 'guest') {
    $function = 'user/studentcourse/profile';
} elseif ($role == 'student') {
    $function = 'user/user/dashboard';
} elseif ($role == 'parent') {
    $function = 'user/user/dashboard';
}?>
                <a href="<?php echo base_url(); ?><?php echo $function; ?>" class="logo">
                
                    <span class="logo-mini"><img src=" <?php echo base_url('uploads/school_content/admin_small_logo/'. $this->setting_model->getAdminsmalllogo());?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                    
                    <span class="logo-lg"><img src="<?php echo base_url('uploads/school_content/admin_logo/'.$this->setting_model->getAdminlogo());?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span> 
                    
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a onclick="collapseSidebar()" href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="col-lg-4 col-md-3 col-sm-2 col-xs-4">
                        <span href="#" class="sidebar-session">
                            <?php echo $this->setting_model->getCurrentSchoolName(); ?>
                        </span>
                    </div>
                    <div class="col-lg-8 col-md-9 col-sm-10 col-xs-8">
                        <div class="pull-right">

                            <div class="navbar-custom-menu">
                                <?php  $language_count    = $this->customlib->get_active_language_count();
                                    if ($language_count > 1) { ?>

                                    <div class="langdiv">
                                        <select class="languageselectpicker" onchange="set_languages(this.value)"  type="text" id="languageSwitcher" class="form-control search-form search-form3 langselect" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('language') ?>"  >

                                           <?php $this->load->view('student/languageSwitcher')?>

                                        </select>
                                    </div>
                                <?php } ?>
                                   <?php  $currency_count    = $this->customlib->get_active_currency_count();
                                    if ($currency_count > 1) {?>

                                    <div class="currency-icon-list" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('currency') ?>">
                                        <select class="languageselectpicker" type="text" id="currencySwitcher" >

                                           <?php $this->load->view('student/currencySwitcher')?>

                                        </select>
                                    </div>

                                <?php } ?>

                                    <ul class="nav navbar-nav headertopmenu">
                                   <?php

if ($this->studentmodule_lib->hasActive('multi_class')) {
    ?>
       <li class="cal15"><a href="#" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_class') ?>"><span data-toggle="modal" data-target="#classSwitchModal"><i class="fa fa-exchange" aria-hidden="true"></i></span></a></li>
   <?php

}
?>
                                     <?php if ($this->studentmodule_lib->hasActive('calendar_to_do_list')) {
    ?>
                                    <li class="cal15 d-sm-none <?php echo ($is_lock_panel) ? "disable-link" : "" ?>"><a data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('calendar') ?>" href="<?php echo base_url() ?>user/calendar/"><i class="fa fa fa-calendar"></i></a></li>

                                    <li data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('task') ?>" class="dropdown <?php echo ($is_lock_panel) ? "disable-link" : "" ?>">
                                        <a href="#" class="dropdown-toggle todoicon" data-toggle="dropdown">
                                            <i class="fa fa-check-square-o"></i>
                                            <?php
$userdata = $this->customlib->getLoggedInUserData();
    $count    = $this->customlib->countincompleteTask($userdata["id"]);
    if ($count > 0) {
        ?>

                                                <span class="todo-indicator"><?php echo $count ?></span>
                                            <?php }?>
                                        </a>
                                        <ul class="dropdown-menu menuboxshadow">

                                            <li class="todoview plr10 ssnoti"><?php echo $this->lang->line('today_you_have') . " " . $count . " " . $this->lang->line('pending_task') ?><a href="<?php echo base_url() ?>user/calendar/" class="pull-right pt0"> <?php echo $this->lang->line('view_all'); ?></a></li>
                                            <li>
                                                <ul class="todolist">
                                                    <?php
$tasklist = $this->customlib->getincompleteTask($userdata["id"]);
    foreach ($tasklist as $key => $value) {
        ?>
                                                        <li><div class="checkbox">
                                                                <label><input type="checkbox" id="newcheck<?php echo $value["id"] ?>" onclick="markc('<?php echo $value["id"] ?>')" name="eventcheck"  value="<?php echo $value["id"]; ?>"><?php echo $value["event_title"] ?></label>
                                                            </div></li>
                                                    <?php }?>

                                                </ul>
                                            </li>
                                        </ul>
                                    </li>

                                <li class="dropdown d-lg-none d-sm-block ellipsis-px-3">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu min-w-full sm-drop-down">
                                        <li><a href="<?php echo base_url() ?>user/calendar/"><i class="fa fa-calendar"></i></a></li>
                                        <?php if ($this->module_lib->hasActive('chat') && $this->studentmodule_lib->hasActive('chat')) { ?>
                                        <li><a href="<?php echo base_url() ?>user/chat"><i class="fa fa-comment-o"></i></a></li>
                                        <?php } ?>										
										
<?php 
	if($sch_setting->student_panel_whatsapp){ 
	$waurl = "https://wa.me/";
	$mobile = $sch_setting->student_panel_whatsapp_mobile;	
	$url = $waurl.$mobile;
	$today = strtotime(date("H:i:s"));	
	$show_hide = 1;
	
	if($sch_setting->student_panel_whatsapp_from != '' && $sch_setting->student_panel_whatsapp_to != ''){
		
		$student_panel_whatsapp_from = strtotime($sch_setting->student_panel_whatsapp_from);
		$student_panel_whatsapp_to = strtotime($sch_setting->student_panel_whatsapp_to);
		
		if($today>=$student_panel_whatsapp_from && $today<=$student_panel_whatsapp_to){
			$show_hide = 1;
		}else{
			$show_hide = 0;
		}
	}
	
	if($show_hide){
?>

<li class="cal15 whatsapp-icon-bg"><a href="<?php echo $url; ?>" target="_blank" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('whatsapp_link') ?>">
<svg height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
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
</svg></a></li>

<?php } } ?>  

                                    </ul>
                                </li>    

                                <?php }

                                if($this->module_lib->hasActive('chat') && $this->studentmodule_lib->hasActive('chat')){ ?>
                                    <li class="cal15 d-sm-none <?php echo ($is_lock_panel) ? "disable-link" : "" ?>"><a data-placement="bottom" data-toggle="tooltip" title="" href="<?php echo base_url() ?>user/chat" data-original-title="<?php echo $this->lang->line('chat'); ?>" class="todoicon"><i class="fa fa-comment-o"></i> <span class="total_chat_msg text-white badge bg-red topbadges"><?php
                                    $msg_count=$this->customlib->get_student_parent_chat_msg_count();
                                   echo  count($msg_count);
                                ?></span></a></li>
                                <?php }


$student_data = $this->customlib->getLoggedInUserData(); 
if (!empty($student_data["image"])) {
    if($student_data['role'] == 'guest'){                    
        $file = base_url() . "uploads/guest_images/" . $student_data["image"] . img_time();                                    
    }else{                   
        $file = base_url() . $student_data["image"] . img_time();               
    }                
}else{
    if ($student_data['gender'] == 'Female') {
        $file = base_url() . "uploads/student_images/default_female.jpg" . img_time();
    } elseif ($student_data['gender'] == 'Male') {
        $file = base_url() . "uploads/student_images/default_male.jpg" . img_time();
    }else{
        $file = base_url() . "uploads/student_images/no_image.png";  
    }                
}
?>
                                
<?php  
	if($sch_setting->student_panel_whatsapp){ 
	$waurl = "https://wa.me/";
	$mobile = $sch_setting->student_panel_whatsapp_mobile;			
	$url = $waurl.$mobile;
	$today = strtotime(date("H:i:s"));	
	$show_hide = 1;
	
	if($sch_setting->student_panel_whatsapp_from != '' && $sch_setting->student_panel_whatsapp_to != ''){
		
		$student_panel_whatsapp_from = strtotime($sch_setting->student_panel_whatsapp_from);
		$student_panel_whatsapp_to = strtotime($sch_setting->student_panel_whatsapp_to);
	
		if($today>=$student_panel_whatsapp_from && $today<=$student_panel_whatsapp_to){
			$show_hide = 1;
		}else{
			$show_hide = 0;
		}
	}
	
	if($show_hide){
?>
<li class="cal15 whatsapp-icon-bg d-sm-none"><a target="_blank" href="<?php echo $url; ?>" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('whatsapp_link') ?>">
<svg height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
     viewBox="0 0 512 512" xml:space="preserve">
<path style="fill:#fff;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
    S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"/>
<path style="fill:#55CD6C;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
    c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
    c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"/>
<path style="fill:#fff;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
    c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
    c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
    c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
    c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
    l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"/>
</svg></a></li> 

<?php } } ?> 
   
                                    <li class="dropdown user-menu">
                                        <a class="dropdown-toggle" style="padding: 15px 13px;" data-toggle="dropdown" href="#" aria-expanded="false">
                                            <?php if ($sch_setting->student_photo) {
    ?>
                                                <img src="<?php echo $file . img_time(); ?>" class="topuser-image" alt="User Image">
                                                <?php
}?>
                                        </a>
                                        <ul class="dropdown-menu dropdown-user menuboxshadow">

                                            <li>
                                                <div class="sstopuser">
                                                    <div class="ssuserleft">
                                                   <a href="<?php echo base_url() ?><?php echo $function; ?>">
                                                    <?php if ($sch_setting->student_photo) {
    ?>
                                                        <img src="<?php echo $file . img_time(); ?>" alt="User Image"></a>
                                                    <?php }?>

                                                    </div>

                                                    <div class="sstopuser-test">
                                                        <h4 class="text-capitalize mb0"><a href="<?php echo base_url(); ?>user/user/profile"><?php echo $this->customlib->getStudentSessionUserName(); ?></a></h4>
                                                        <h5 class="mt0"><?php
if ($this->customlib->getUserRole() == 'student') {
    echo $this->lang->line("student");
} elseif ($this->customlib->getUserRole() == 'parent') {
    echo $this->lang->line("parent");
} elseif ($this->customlib->getUserRole() == 'guest') {
    echo $this->lang->line("guest");
}
?></h5>
<a class="" href="<?php echo base_url(); ?>user/user/changepass"><i class="fa fa-key"></i> <?php echo $this->lang->line('change_password'); ?></a>

                                                        <?php
if ($this->module_lib->hasModule('google_authenticator') && $this->module_lib->hasActive('google_authenticator')) {

    // if (is_active_2fa() && $this->auth->addonchk('ssglc', false)) {
        ?>

                                                            <p class="sspass"><a href="<?php echo site_url('user/gauthenticate/setup') ?>"><i class="fa fa-cog"></i><?php echo $this->lang->line('setting'); ?></a></p>

                                                        <?php
// }
}
?>

                                                    </div>
                                                    <div class="divider"></div>
                                                    
                                                    <div class="sspass">
                                                         <!-- <a href="<?php //echo base_url(); ?>user/user/profile" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-user"></i><?php //echo $this->lang->line('my_profile'); ?></a> -->
                                                          <?php
                                                        $getfrontcmssetting =$this->customlib->getfrontcmssetting();
                                                        if($getfrontcmssetting->is_active_front_cms){  ?>
                                                        <a style="" href="<?php echo base_url(); ?>" target="_blank" class="forgot"><i class="fa fa-empire"></i><?php echo $this->lang->line('front_site'); ?></a>
                                                        <?php } ?>
                                                        
                                                    
                                                       
                                                        <a href="<?php echo base_url(); ?>site/logout"><i class="fa fa-sign-out fa-fw"></i><?php echo $this->lang->line('logout'); ?></a>
                                                    </div>
                                                </div><!--./sstopuser--></li>

                                        </ul>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar" id="alert2">

                <?php
if ($role == 'student' || $role == 'parent') {
    ?>
                <section class="sidebar <?php echo ($is_lock_panel) ? "disable-sidebar" : "" ?>">

                    <ul class="sessionul fixedmenu">
                        <li class="removehover accurrent">
                            <a data-toggle="modal" data-target="#user_sessionModal"><span><?php echo $this->lang->line('current_session') . ": " . $this->setting_model->getCurrentSessionName(); ?></span><i class="fa fa-pencil pull-right"></i></a>
                        </li>
                    </ul>
                    <ul class="sidebar-menu verttop38" id="sibe-box">
                        <li class="treeview <?php echo set_Topmenu('dashboard'); ?>">
                            <a href="<?php echo base_url(); ?>user/user/dashboard">
                                <i class="fa fa-television"></i> <span> <?php echo $this->lang->line('dashboard'); ?></span>
                            </a>
                        </li>

                        <li class="treeview <?php echo set_Topmenu('my_profile'); ?>"><a href="<?php echo base_url(); ?>user/user/profile"><i class="fa fa-user-plus ftlayer"></i> <span><?php echo $this->lang->line('my_profile'); ?></span></a></li>

                        <?php if ($this->module_lib->hasActive('fees_collection') && $this->studentmodule_lib->hasActive('fees')) {?>
                            <li class="treeview <?php echo set_Topmenu('fees'); ?>"><a href="<?php echo base_url(); ?>user/user/getfees"><i class="fa fa-money ftlayer"></i> <span><?php echo $this->lang->line('fees'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course') && $this->studentmodule_lib->hasActive('online_course') && $this->auth->addonchk('ssoclc', false)) {?>
                            <li class="treeview <?php echo set_topmenu('user/studentcourse'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse"><i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('online_course'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasModule('zoom_live_classes') && $this->module_lib->hasActive('zoom_live_classes') && $this->studentmodule_lib->hasActive('live_classes') && $this->auth->addonchk('sszlc', false)) {?>
                            <li class="treeview <?php echo set_topmenu('Conference'); ?>"><a href="<?php echo base_url('user/conference'); ?>"><i class="fa fa-video-camera ftlayer"></i> <?php echo $this->lang->line('zoom_live_classes'); ?></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasModule('gmeet_live_classes') && $this->module_lib->hasActive('gmeet_live_classes') && $this->studentmodule_lib->hasActive('gmeet_live_classes') && $this->auth->addonchk('ssglc', false)) {?>
                            <li class="<?php echo set_topmenu('Gmeet'); ?>"><a href="<?php echo base_url('user/gmeet'); ?>"><i class="fa fa-video-camera ftlayer"></i> <?php echo $this->lang->line('gmeet') . " " . $this->lang->line('live_class'); ?></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('class_timetable')) {?>
                            <li class="<?php echo set_Topmenu('Time_table'); ?>"><a href="<?php echo base_url(); ?>user/timetable"><i class="fa fa-calendar-plus-o ftlayer"></i> <span><?php echo $this->lang->line('class_timetable'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('lesson_plan') && $this->studentmodule_lib->hasActive('lesson_plan')) {?>
                            <li class="treeview <?php echo set_Topmenu('syllabus'); ?>"><a href="<?php echo base_url(); ?>user/syllabus"><i class="fa fa fa-list-alt ftlayer"></i> <span><?php echo $this->lang->line('lesson_plan'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('syllabus_status')) {?>
                             <li class="<?php echo set_Topmenu('syllabus/status'); ?>"><a href="<?php echo base_url(); ?>user/syllabus/status"><i class="fa fa-list-ol ftlayer"></i> <span><?php echo $this->lang->line('syllabus_status'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('homework') && $this->studentmodule_lib->hasActive('homework')) {?>
                            <li class="<?php echo set_Topmenu('Homework'); ?>"><a href="<?php echo base_url(); ?>user/homework"><i class="fa fa-flask ftlayer"></i> <span><?php echo $this->lang->line('homework'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('online_examination') && $this->studentmodule_lib->hasActive('online_examination')) {?>
                            <li class="treeview <?php echo set_Topmenu('Onlineexam'); ?>"><a href="<?php echo site_url('user/onlineexam'); ?>"><i class="fa fa-rss ftlayer"></i> <span><?php echo $this->lang->line('online_exam'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('apply_leave')) {?>
                              <li class="<?php echo set_Topmenu('apply_leave'); ?>"><a href="<?php echo base_url(); ?>user/apply_leave"><i class="fa fa-check-square ftlayer"></i> <span><?php echo $this->lang->line('apply_leave'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('visitor_book')) {?>
                              <li class="<?php echo set_Topmenu('visitors'); ?>"><a href="<?php echo base_url(); ?>user/visitors"><i class="fa fa-check-square ftlayer"></i> <span><?php echo $this->lang->line('visitor_book'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('download_center') && $this->studentmodule_lib->hasActive('download_center')) {?>
                            <li class="treeview <?php echo set_Topmenu('Downloads'); ?>">
                                <a href="#">
                                    <i class="fa fa-download ftlayer"></i> <span><?php echo $this->lang->line('download_center'); ?></span> <i class="icon-chevron-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('content/index'); ?>">
                                        <a href="<?php echo base_url(); ?>user/content/list"><i class="icon-circle-small"></i><?php echo $this->lang->line('contents'); ?></a>
                                    </li>

                                    <li class="<?php echo set_Submenu('video_tutorial/index'); ?>"><a href="<?php echo base_url(); ?>user/video_tutorial"><i class="icon-circle-small"></i> <?php echo $this->lang->line('video_tutorial'); ?></a></li>
                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('student_attendance') && $this->studentmodule_lib->hasActive('attendance')) {?>
                            <li class="treeview <?php echo set_Topmenu('Attendence'); ?>"><a href="<?php echo base_url(); ?>user/attendence"><i class="fa fa-calendar-check-o ftlayer"></i> <span><?php echo $this->lang->line('attendance'); ?></span></a></li>
                        <?php }?>
                        
                        <?php  
                        if ($this->module_lib->hasModule('cbseexam') && $this->module_lib->hasActive('cbseexam') && $this->studentmodule_lib->hasActive('cbseexam')   ) { ?>	
							
							<li class="treeview <?php echo set_Topmenu('cbse_exam'); ?>">
                                <a href="#">
                                    <i class="fa fa-wpforms"></i> <span><?php echo $this->lang->line('cbse_exam'); ?></span> <i class="icon-chevron-left pull-right"></i>
                                </a> 
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('user/cbse/cbse_exam_result'); ?>"><a href="<?php echo site_url('user/cbse/exam/result'); ?>"><i class=" icon-circle-small"></i> <?php echo $this->lang->line('exam_result'); ?></a></li>
									
                                    <li class="<?php echo set_Submenu('user/cbse/cbse_exam_timetable'); ?>"><a href="<?php echo site_url('user/cbse/exam/timetable'); ?>"><i class="fa icon-circle-small"></i> <?php echo $this->lang->line('exam_schedule'); ?></a></li>
                                     
                                </ul>
                            </li>

                        <?php  }  ?>
                        
                        <?php if ($this->module_lib->hasActive('examination') && $this->studentmodule_lib->hasActive('examinations')) {?>
                        <li class="treeview <?php echo set_Topmenu('Examinations'); ?>">
                                <a href="#">
                                    <i class="fa fa-map-o ftlayer"></i> <span><?php echo $this->lang->line('examinations'); ?></span> <i class="icon-chevron-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('examSchedule/index'); ?>"><a href="<?php echo base_url(); ?>user/examschedule"><i class="fa icon-circle-small"></i> <?php echo $this->lang->line('exam_schedule'); ?></a></li>
                                    <li class="<?php echo set_Submenu('examresult/index'); ?>"><a href="<?php echo base_url(); ?>user/exam/examresult"><i class="fa icon-circle-small"></i> <?php echo $this->lang->line('exam_result'); ?></a></li>
                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('notice_board')) {
        ?>

                            <li class="treeview <?php echo set_Topmenu('notification'); ?>">
                                <a href="<?php echo base_url(); ?>user/notification">
                                    <i class="fa fa-envelope ftlayer"></i> <span><?php echo $this->lang->line('notice_board'); ?></span>
                                    <?php
$ntf = $this->customlib->getUserunreadNotification();
        if ($ntf) {
            ?>
                                        <small class="label pull-right bg-red" style="margin-top: -7px;">
                                            <?php echo $ntf; ?>
                                        </small>
                                        <?php
}
        ?>
                                </a>
                            </li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('teachers_rating')) {?>
                            <li class="<?php echo set_Topmenu('Teachers'); ?>"><a href="<?php echo base_url(); ?>user/teacher"><i class="fa fa-user-secret ftlayer"></i> <span><?php echo $this->lang->line('teachers_reviews'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('library') && $this->studentmodule_lib->hasActive('library')) {?>
                            <li class="treeview <?php echo set_Topmenu('Library'); ?>">
                                <a href="#">
                                    <i class="fa fa-book ftlayer"></i> <span><?php echo $this->lang->line('library'); ?></span> <i class="icon-chevron-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('book/index'); ?>">
                                        <a href="<?php echo base_url(); ?>user/book">
                                            <i class="icon-circle-small"></i> <?php echo $this->lang->line('books'); ?></a>
                                    </li>
                                    <li class="<?php echo set_Submenu('book/issue'); ?>">
                                        <a href="<?php echo base_url(); ?>user/book/issue">
                                            <i class="icon-circle-small"></i> <?php echo $this->lang->line('book_issued'); ?></a>
                                    </li>
                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('transport') && $this->studentmodule_lib->hasActive('transport_routes')) {?>
                            <li class="<?php echo set_Submenu('route/index'); ?>"><a href="<?php echo base_url(); ?>user/route"><i class="fa fa-bus ftlayer"></i> <span><?php echo $this->lang->line('transport_routes'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('hostel') && $this->studentmodule_lib->hasActive('hostel_rooms')) {?>
                            <li class="<?php echo set_Submenu('hostelroom/index'); ?>"><a href="<?php echo base_url(); ?>user/hostelroom"><i class="fa fa-building-o ftlayer"></i> <span><?php echo $this->lang->line('hostel_rooms'); ?></span></a></li>
                        <?php }?>
                    </ul>

                </section>
                <?php } elseif ($role == 'guest') {?>
                <section class="sidebar">
                    <ul class="sidebar-menu " id="sibe-box">
                        <li class="<?php echo set_Topmenu('user/guestprofile'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse/profile"><i class="fa fa-user-plus ftlayer"></i> <span><?php echo $this->lang->line('my_profile'); ?></span></a></li>

                        <li class="<?php echo set_topmenu('user/studentcourse'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse"><i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('online_course'); ?></span></a></li>

                        <li class="<?php echo set_topmenu('user/purchasehistory'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse/purchasehistory"><i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('purchase_history'); ?></span></a></li>

                    </ul>
                 </section>
                <?php }?>
            </aside>
            <script>
                 function set_languages(lang_id){

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>user/user/user_language/"+lang_id,
                    data: {},
                    //dataType: "json",
                    success: function (data) {
                        successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
                       window.location.reload('true');

                    }
                });

                     }
            </script>