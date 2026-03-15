<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
        <link rel="stylesheet" href="<?php echo base_url('theme.css'); ?>">
        <style type="text/css">
            .table2 tr.border_bottom td {
                box-shadow: none;
                border-radius: 0;
                border-bottom: 1px solid  var(--bs-with-border-color);
            }
            .table2 td {
                padding-bottom: 3px;
                padding-top: 6px;
            }
            .title{
                color: #0084B4;
                font-weight: 600 !important;
                font-size: 15px !important;;
                display: inline;

            }
            .product-description {
                display: block;
                color: #999;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            .text-fine{
                color: #bf4f4d;
            }
        </style> 
        <script src="<?php //echo base_url(); ?>backend/dist/js/theme-color.js"></script>
         <script>
        // === Load PHP theme settings safely ===
        const rawThemeSettings = <?php echo json_encode($this->customlib->getCurrentThemeSetting() ?? new stdClass()); ?>;
        const themeSettings = rawThemeSettings || {};
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
                    console.log(themeClassNavigation);
                    classList.add(themeClassNavigation);
                }
            }

            // === Apply layout and update icons ===
            function applyLayoutAndIcons() {
                const content = document.querySelector("section.content");
                const layoutIcon = document.getElementById('content-icon');
                const shadowIcon = document.getElementById('iconskins');
                const navigationIcon = document.getElementById('icon_theme_navigation');

                if (content && !content.classList.contains(savedLayout)) {
                    content.classList.add(savedLayout);
                }

                if (navigationIcon) {
                    const isCollapsed = themeClassNavigation === 'sidebar-collapse';

                    navigationIcon.classList.toggle('fa-bars', isCollapsed);
                    navigationIcon.classList.toggle('fa-brands', !isCollapsed);
                    navigationIcon.classList.toggle('fa-elementor', !isCollapsed);
                }

                if (layoutIcon) {
                    layoutIcon.classList.toggle('fa-compress', savedLayout === 'container-fluid');
                    layoutIcon.classList.toggle('fa-expand', savedLayout !== 'container-fluid');
                }

                if (shadowIcon) {
                    shadowIcon.classList.toggle('fa-border-none', themeShadow === 'shadow-applied');
                    shadowIcon.classList.toggle('fa-border-all', themeShadow !== 'shadow-applied');
                }

                // Stop observing once applied
                if (content || layoutIcon || shadowIcon) {
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
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting[0]['image']); ?>">
                    </div> 
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('fees_payment_details');?></div>
                            <?php if (isset($api_error) && $api_error) {
                                ?> 
                                <div class="alert alert-danger">
                                    <h4><i class="icon fa fa-warning"></i> Payment Error!</h4>
                                    <?php
                                    if (isset($api_error['message'])) {
                                        echo '<p><strong>Error:</strong> ' . htmlspecialchars($api_error['message']) . '</p>';
                                    }
                                    if (isset($api_error['error'])) {
                                        echo '<p><strong>Error:</strong> ' . htmlspecialchars($api_error['error']) . '</p>';
                                    }
                                    if (isset($api_error['code'])) {
                                        echo '<p><strong>Error Code:</strong> ' . htmlspecialchars($api_error['code']) . '</p>';
                                    }
                                    if (isset($api_error['status'])) {
                                        echo '<p><strong>Status:</strong> ' . htmlspecialchars($api_error['status']) . '</p>';
                                    }
                                    if (isset($api_error['details'])) {
                                        echo '<p><strong>Details:</strong> ' . htmlspecialchars($api_error['details']) . '</p>';
                                    }
                                    ?>
                                    <p>Please check your payment details and try again. If the problem persists, please contact support.</p>
                                </div>
                                <div class="paddtlrb" style="margin-top: 20px;">
                                    <button type="button" onclick="window.history.go(-1); return false;" name="search" value="" class="btn paybackbtn"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back')?></button>
                                </div>
                            <?php } else { ?>
                            <div class="padd2 paddtzero">
                                <table class="table2" width="100%">
                                    <tr>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount')?></th>
                                    </tr>
                                    <?php
                                    foreach ($student_fees_master_array as $fees_key => $fees_value) {
                                        ?>
                                        <tr>
                                           <td>
                                                <span class="title"><?php if ($fees_value['is_system']) {
                echo $this->lang->line($fees_value['fee_group_name']);
            } else {
                echo $fees_value['fee_group_name'] ;
            }?> </span>
                                                <span class="product-description">
                                                    <?php  if ($fees_value['is_system']) {
                echo $this->lang->line($fees_value['fee_type_code']);
            } else {
                echo $fees_value['fee_type_code'];
            } ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $fees_value['amount_balance'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="border_bottom">
                                            <td> 
                                                <span class="text-fine"><?php echo $this->lang->line('fine'); ?></span></td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $fees_value['fine_balance'], 2, '.', ''); ?></td>
                                        </tr>
										<tr class="border_bottom">
                                            <td>
                                                <span class="text-text-success"><?php echo $this->lang->line('discount'); ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $params['applied_fee_discount'], 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    
                                        <tr class="border_bottom">
                                            <td>
                                                <span class="text-text-success"><?php echo $this->lang->line('processing_fees'); ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $params['gateway_processing_charge'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="bordertoplightgray">
                                            <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $setting[0]['currency_symbol'] . amountFormat((float)(($params['fine_amount_balance'] + $params['total']) - $params['applied_fee_discount']+$params['gateway_processing_charge']), 2, '.', ''); ?></td>
                                        </tr>
                                </table>
                                <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
                                <div class="divider"></div>
                                <form class="paddtlrb" action="<?php echo site_url('user/gateway/paypal/complete') ?>" method="POST" id="paypalForm">                                   
                                   
                                    <input type="text" hidden="" name="student_id" value="<?php echo $params['student_id']; ?>">
                                    <input type="text" hidden="" name="total" value="<?php echo $params['total']; ?>">

                                    <button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn paybackbtn"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back')?></button>    
                                    <button type="button"  class="btn btn-info pull-right submit_button"><i class="fa fa fa-money"></i> <?php echo $this->lang->line('pay_with_paypal')?> </button>                           
                                </form> 
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $(".submit_button").click(function (e) {
                var url = "<?php echo site_url('user/gateway/paypal/checkout') ?>";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#paypalForm").serialize(),
                    dataType: "Json",
                    success: function (response)
                    {

                        if (response.status == "success") {
                            $('form#paypalForm').submit();
                        } else if (response.status == "fail") {
                            $.each(response.error, function (index, value) {
                                var errorDiv = '.' + index + '_error';
                                $(errorDiv).empty().append(value);
                            });
                        }
                    }
                });

                e.preventDefault();
            });
        });
    </script>
</body>
</html>       