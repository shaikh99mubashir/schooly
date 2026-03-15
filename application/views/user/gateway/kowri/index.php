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
            .kowri-logo {
                max-width: 200px;
                height: auto;
            }
            .payment-option-card:hover {
                border-color: #0084B4 !important;
                box-shadow: 0 2px 8px rgba(0, 132, 180, 0.2) !important;
                transform: translateY(-2px);
            }
            .payment-option-card.selected {
                border-color: #0084B4 !important;
                background-color: var(--bs-input-bg) !important;
                box-shadow: 0 2px 8px rgba(0, 132, 180, 0.3) !important;
            }
            .payment-option-card input[type="radio"] {
                transform: scale(1.2);
            }
            .payment-option-card input[type="radio"]:checked + label {
                color: #0084B4;
                font-weight: 600;
            }
            
            /* Compact Layout Styles */
            .payment-option-card {
                min-height: 70px !important;
                padding: 12px !important;
                margin: 5px 0 !important;
                border: 1px solid var(--bg-gray-450) !important;
                border-radius: 6px !important;
                cursor: pointer !important;
                transition: all 0.3s ease !important;
                background: var(--bs-card-bg) !important;
                display: flex !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                position: relative !important;
                z-index: 1 !important;
            }
            
            .payment-option-card:hover {
                border-color: #0084B4 !important;
                box-shadow: 0 2px 8px rgba(0, 132, 180, 0.2) !important;
                transform: translateY(-1px) !important;
            }
            
            /* .payment-option-card.selected {
                border-color: #0084B4 !important;
                background-color: #f8f9fa !important;
                box-shadow: 0 2px 8px rgba(0, 132, 180, 0.3) !important;
            }
             */
            .payment-option-info {
                display: flex !important;
                align-items: flex-start !important;
                flex: 1 !important;
                min-width: 0 !important;
                margin-right: 10px !important;
            }
            
            .payment-option-logo {
                width: 40px !important;
                height: 40px !important;
                margin-right: 12px !important;
                flex-shrink: 0 !important;
                margin-top: 2px !important;
            }
            
            .payment-option-details {
                flex: 1 !important;
                min-width: 0 !important;
                padding-top: 2px !important;
            }
            
            .payment-option-details h5 {
                font-size: 15px !important;
                margin: 0 0 4px 0 !important;
                font-weight: 600 !important;
                color: var(--bs-body-color) !important;
                line-height: 1.3 !important;
            }
            
            .payment-option-details p {
                font-size: 13px !important;
                margin: 0 0 3px 0 !important;
                color: var(--bs-footer-color) !important;
                line-height: 1.4 !important;
            }
            
            .payment-option-limits {
                font-size: 12px !important;
                margin-top: 3px !important;
                color: #888 !important;
                line-height: 1.3 !important;
            }
            
            .payment-option-select {
                margin-left: 10px !important;
                flex-shrink: 0 !important;
            }
            
            .payment-options-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 12px !important;
                margin: 15px 0 !important;
            }
            
            @media (min-width: 1400px) {
                .payment-options-grid {
                    grid-template-columns: 1fr 1fr 1fr !important;
                }
            }
            
            @media (max-width: 768px) {
                .payment-options-grid {
                    grid-template-columns: 1fr !important;
                    gap: 8px !important;
                }
            }
            
            .payment-option-card.wide {
                grid-column: span 2 !important;
            }
            
            .compact-form {
                max-height: 70vh !important;
                overflow-y: auto !important;
            }
            
            .payment-section {
                margin: 10px 0 !important;
            }
            
            /* Additional Fields Styling */
            .additional-fields {
                margin-top: 10px !important;
                padding: 10px !important;
                background: var(--bs-input-bg) !important;
                border-radius: 6px !important;
                border: 1px solid var(--bs-card-border-light-gray) !important;
            }
            
            .form-group {
                margin-bottom: 10px !important;
            }
            
            .form-group label {
                font-size: 12px !important;
                font-weight: 600 !important;
                color: var(--bs-footer-color) !important;
                margin-bottom: 3px !important;
            }
            
            /* .form-control {
                height: 35px !important;
                font-size: 12px !important;
                padding: 6px 10px !important;
                border: 1px solid #ddd !important;
                border-radius: 4px !important;
            } */
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
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting[0]['image']); ?>">
                    </div>                    
                    <div class="col-md-8 col-md-offset-2 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('fees_payment_details'); ?> </div>
                            <?php if ($api_error) {
                                ?> 
                                <div class="alert alert-danger">
                                    <h4><i class="icon fa fa-warning"></i> Payment Error!</h4>
                                    <?php
                                    // Display the main error message prominently
                                    if (isset($api_error['error'])) {
                                        echo '<div class="alert alert-warning" style="margin: 10px 0; padding: 15px; border-left: 4px solid #f0ad4e;">';
                                        echo '<h5 style="margin: 0 0 10px 0; color: #8a6d3b;"><i class="fa fa-exclamation-triangle"></i> ' . htmlspecialchars($api_error['error']) . '</h5>';
                                        echo '</div>';
                                    }
                                    
                                    // Display additional error details
                                    if (isset($api_error['message'])) {
                                        echo '<p><strong>Error Details:</strong> ' . htmlspecialchars($api_error['message']) . '</p>';
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
                                    if (isset($api_error['error_description'])) {
                                        echo '<p><strong>Description:</strong> ' . htmlspecialchars($api_error['error_description']) . '</p>';
                                    }
                                    ?>
                                    <div class="alert alert-info" style="margin: 15px 0;">
                                        <h6 style="margin: 0 0 10px 0;"><i class="fa fa-info-circle"></i> What does this mean?</h6>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            <li><strong>Card not supported:</strong> The selected payment method (CARD) is not available for this transaction. Try using Mobile Money or other available payment options.</li>
                                            <li><strong>Amount issues:</strong> The payment amount might be outside the allowed range for the selected payment method.</li>
                                            <li><strong>Account issues:</strong> Your Kowri account might need additional configuration for card payments.</li>
                                        </ul>
                                    </div>
                                    
                                    
                                    <p><strong>Next Steps:</strong> Please try a different payment method or contact support if the problem persists.</p>
                                </div>
                            <?php }
                            ?> 
                            <div class="padd2 paddtzero">
                                <form action="<?php echo base_url(); ?>user/gateway/kowri/pay" method="post">
                                    <table class="table2" width="100%">
                                        <tr>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
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
                                        <?php
                                    }
                                    ?>
                                     <tr class="border_bottom">
                                            <td>
                                                <span class="text-text-success"><?php echo $this->lang->line('discount'); ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $params['applied_fee_discount'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="border_bottom">
                                            <td>
                                                <span class="text-text-success"><?php echo $this->lang->line('processing_fees'); ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $params['gateway_processing_charge'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="bordertoplightgray">
                                            <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $setting[0]['currency_symbol'] . amountFormat((float)(($params['fine_amount_balance'] + $params['total']) - $params['applied_fee_discount']+$params['gateway_processing_charge']), 2, '.', ''); ?></td>
                                        </tr>
										
										
										
                                        <tr class="bordertoplightgray">
                                            <td><?php echo $this->lang->line('phone_number'); ?>:</td>
                                            <td class="text-right"> <input type="text" class="form-control"  name="phone" value="<?php echo set_value('phone', isset($form_data['phone']) ? $form_data['phone'] : ''); ?>" /><span class="alert-danger"><?php echo form_error('phone');?></span></td>
                                        </tr>
                                        
                                        <tr class="bordertoplightgray">
                                            <td><?php echo $this->lang->line('email'); ?>:</td>
                                            <td class="text-right"> <input type="email" class="form-control"  name="email" value="<?php echo set_value('email', isset($form_data['email']) ? $form_data['email'] : ''); ?>" /><span class="alert-danger"><?php echo form_error('email');?></span></td>
                                        </tr>
										
                                        <!-- PayNow Card Payment Info -->
                                        <tr class="bordertoplightgray" id="card_details_row" style="display: none;">
                                            <td colspan="2">
                                                <div class="additional-fields">
                                                    <h6 style="margin: 0 0 10px 0; font-weight: 600;">
                                                        <i class="fa fa-credit-card"></i> PayNow Card Payment
                                                    </h6>
                                                    <div class="alert alert-info" style="margin: 0; padding: 10px; border-radius: 6px;">
                                                        <i class="fa fa-info-circle"></i>
                                                        <strong>Secure Card Payment:</strong> You will be redirected to a secure payment page where you can enter your card details safely. No card information is collected on this page.
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="bordertoplightgray" id="momo_details_row" style="display: none;">
                                            <td colspan="2">
                                                <div class="additional-fields">
                                                    <h6 style="margin: 0 0 10px 0; font-weight: 600;">
                                                        <i class="fa fa-mobile"></i> MTN Mobile Money
                                                    </h6>
                                                    <div class="form-group">
                                                        <label>Mobile Money Number</label>
                                                        <input type="text" class="form-control" name="momo_phone" placeholder="Enter your MTN Mobile Money number" value="<?php echo isset($form_data['momo_phone']) ? $form_data['momo_phone'] : set_value('phone'); ?>">
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fa fa-info-circle"></i> Enter your MTN Mobile Money registered number
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="bordertoplightgray" id="mobile_money_details_row" style="display: none;">
                                            <td colspan="2">
                                                <div class="additional-fields">
                                                    <h6 style="margin: 0 0 10px 0; font-weight: 600;">
                                                        <i class="fa fa-mobile"></i> Mobile Money Number
                                                    </h6>
                                                    <div class="form-group">
                                                        <label>Mobile Money Number</label>
                                                        <input type="text" class="form-control" name="mobile_money_phone" placeholder="Enter your mobile money number" value="<?php echo isset($form_data['mobile_money_phone']) ? $form_data['mobile_money_phone'] : set_value('phone'); ?>">
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fa fa-info-circle"></i> Enter your mobile money registered number
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Payment Method Specific Instructions -->
                                        <tr class="bordertoplightgray" id="payment_instructions_row" style="display: none;">
                                            <td colspan="2">
                                                <div class="alert alert-info" style="margin: 0; padding: 10px; border-radius: 6px;">
                                                    <i class="fa fa-info-circle"></i>
                                                    <span id="payment_instructions_text"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Payment Options Section -->
                                        <tr class="bordertoplightgray">
                                            <td colspan="2">
                                                <h4 style="color: #0084B4; margin-bottom: 8px; font-size: 16px;">
                                                    <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('select_payment_method'); ?>
                                                </h4>
                                                <div class="payment-options-grid">
                                                    <!-- PayNow Card Option -->
                                                    <div class="payment-option-card" onclick="selectPaymentOption('CARD', 'PayNow Card Payment')">
                                                        <div class="payment-option-info">
                                                            <div class="payment-option-logo" style="background: linear-gradient(45deg, #007bff, #0056b3); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px;">
                                                                💳 CARD
                                                            </div>
                                                            <div class="payment-option-details">
                                                                <h5>PayNow Card Payment</h5>
                                                                <p>Credit/Debit Card Payment</p>
                                                                <div class="payment-option-limits">
                                                                    <i class="fa fa-info-circle"></i> Secure card payment processing
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="payment-option-select">
                                                            <input type="radio" name="payment_method" value="CARD" id="payment_card" <?php echo (isset($form_data['payment_method']) && $form_data['payment_method'] == 'CARD') ? 'checked' : ''; ?>>
                                                            <label for="payment_card">Select</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- PayNow MOMO Option -->
                                                    <div class="payment-option-card" onclick="selectPaymentOption('MOMO', 'PayNow MOMO')">
                                                        <div class="payment-option-info">
                                                            <div class="payment-option-logo" style="background: linear-gradient(45deg, #ffc107, #ff8f00); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px;">
                                                                📱 MOMO
                                                            </div>
                                                            <div class="payment-option-details">
                                                                <h5>PayNow MOMO</h5>
                                                                <p>MTN Mobile Money via PayNow</p>
                                                                <div class="payment-option-limits">
                                                                    <i class="fa fa-info-circle"></i> MTN Mobile Money payment
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="payment-option-select">
                                                            <input type="radio" name="payment_method" value="MOMO" id="payment_momo" <?php echo (isset($form_data['payment_method']) && $form_data['payment_method'] == 'MOMO') ? 'checked' : ''; ?>>
                                                            <label for="payment_momo">Select</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
										
                                        <tr class="bordertoplightgray">
                                            <td><button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn paybackbtn"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button></td>
                                            <td class="text-right"> <button type="submit"  name="search"  value="" class="btn btn-info pull-right submit_button" id="payButton" disabled><?php echo $this->lang->line('proceed_to_payment'); ?>  <i class="fa fa fa-chevron-right"></i></button>  </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <div class="text-center mt20">
                                <img src="<?php echo base_url(); ?>/backend/images/kowri.png" class="kowri-logo" alt="Kowri Payment Gateway">
                                <p class="text-muted"><?php echo $this->lang->line('payment_gateway_for_african_countries'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        
        <script>
        function selectPaymentOption(provider, name) {
            // Remove selected class from all cards
            document.querySelectorAll('.payment-option-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            const targetCard = event ? event.currentTarget : document.querySelector(`input[value="${provider}"]`).closest('.payment-option-card');
            if (targetCard) {
                targetCard.classList.add('selected');
            }
            
            // Check the radio button
            const radioButton = targetCard ? targetCard.querySelector('input[type="radio"]') : document.querySelector(`input[value="${provider}"]`);
            if (radioButton) {
                radioButton.checked = true;
            }
            
            // Enable the pay button
            document.getElementById('payButton').disabled = false;
            document.getElementById('payButton').innerHTML = '<?php echo $this->lang->line('pay_with'); ?> ' + name + ' <i class="fa fa fa-chevron-right"></i>';
            
            // Add hidden input for selected payment method
            let hiddenInput = document.querySelector('input[name="selected_payment_provider"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selected_payment_provider';
                document.querySelector('form').appendChild(hiddenInput);
            }
            hiddenInput.value = provider;
            
            // Show/hide additional fields based on payment method
            showPaymentMethodFields(provider);
        }
        
        function showPaymentMethodFields(provider) {
            console.log('showPaymentMethodFields called with provider:', provider);
            
            // Hide all additional fields first
            document.getElementById('card_details_row').style.display = 'none';
            document.getElementById('momo_details_row').style.display = 'none';
            document.getElementById('mobile_money_details_row').style.display = 'none';
            document.getElementById('payment_instructions_row').style.display = 'none';
            
            // Show relevant fields and instructions based on payment method
            if (provider === 'CARD') {
                console.log('Showing card details for CARD payment');
                document.getElementById('card_details_row').style.display = 'table-row';
                document.getElementById('payment_instructions_row').style.display = 'table-row';
                document.getElementById('payment_instructions_text').innerHTML = 
                    'You will be redirected to a secure payment page to enter your card details. No card information is collected on this page.';
            } else if (provider === 'MOMO') {
                console.log('Showing MOMO details for MOMO payment');
                document.getElementById('momo_details_row').style.display = 'table-row';
                document.getElementById('payment_instructions_row').style.display = 'table-row';
                document.getElementById('payment_instructions_text').innerHTML = 
                    'Please enter your MTN Mobile Money registered number. You will receive a prompt on your phone to complete the payment.';
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedMethod) {
                e.preventDefault();
                alert('<?php echo $this->lang->line('please_select_payment_method'); ?>');
                return false;
            }
            
            // Validate additional fields based on payment method
            const provider = selectedMethod.value;
            
            if (provider === 'CARD') {
                // No validation needed for PayNow card payments - user will be redirected to secure page
                console.log('PayNow card payment selected - no validation needed');
            } else if (provider === 'MOMO') {
                const momoPhone = document.querySelector('input[name="momo_phone"]').value;
                if (!momoPhone) {
                    e.preventDefault();
                    alert('Please enter your MTN Mobile Money number');
                    return false;
                }
            }
        });
        
        // Card formatting functions removed - not needed for PayNow redirect payments
        
        // Initialize - disable pay button initially
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('payButton').disabled = true;
            
            // Check if there's a previously selected payment method (form error scenario)
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedMethod) {
                const provider = selectedMethod.value;
                const methodName = selectedMethod.closest('.payment-option-card').querySelector('h5').textContent;
                selectPaymentOption(provider, methodName);
                
                // Show additional fields based on selected payment method
                showPaymentMethodFields(provider);
            }
            
            // Card input event listeners removed - not needed for PayNow redirect payments
        });
        </script>
    </body>
</html>
