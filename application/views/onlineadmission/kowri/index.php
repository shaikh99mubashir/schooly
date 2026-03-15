<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $setting->name;?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css"> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url('fronttheme.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/themes/front-main.css">
        <script src="<?php echo base_url(); ?>backend/dist/js/theme-color.js"></script>
        <link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet"/>
        <script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>

        <style type="text/css">
           
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
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>">
                    </div>                    
                    <div class="col-md-8 col-md-offset-2 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('online_admission_form_fees'); ?> </div>
                            <br>
                            <?php if ($api_error) {
                                ?> 
                                <div class="alert alert-danger">
                                    <h4><i class="icon fa fa-warning"></i> Payment Error!</h4>
                                    <?php
                                    if (isset($api_error['message'])) {
                                        echo '<p><strong>Error:</strong> ' . htmlspecialchars($api_error['message']) . '</p>';
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
                                    if (isset($api_error['error'])) {
                                        echo '<p><strong>Error:</strong> ' . htmlspecialchars($api_error['error']) . '</p>';
                                    }
                                    if (isset($api_error['error_description'])) {
                                        echo '<p><strong>Description:</strong> ' . htmlspecialchars($api_error['error_description']) . '</p>';
                                    }
                                    ?>
                                    <p>Please check your payment details and try again. If the problem persists, please contact support.</p>
                                </div>
                            <?php }
                            ?> 
                            <div class="padd2 paddtzero">
                                <form action="<?php echo base_url(); ?>onlineadmission/kowri/pay" method="post">
                                    <table class="table2" width="100%">
                                        <tr>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="title"><?php echo $this->lang->line('online_admission_form_fees'); ?></span>
                                                <span class="product-description">
                                                    <?php echo $this->lang->line('online_admission_form_fees_description'); ?>
                                                </span>
                                            </td>
                                            <td class="text-right"><?php echo $this->customlib->get_currencySymbol() . amountFormat((float) $amount, 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="bordertoplightgray">
                                            <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $this->customlib->get_currencySymbol() . amountFormat((float) $amount, 2, '.', ''); ?></td>
                                        </tr>
                                        
                                        <tr class="bordertoplightgray">
                                            <td><?php echo $this->lang->line('phone_number'); ?>:</td>
                                            <td class="text-right"> <input type="text" class="form-control" name="phone" value="<?php echo set_value('phone', $phone); ?>" /><span class="alert-danger"><?php echo form_error('phone');?></span></td>
                                        </tr>
                                        
                                        <tr class="bordertoplightgray">
                                            <td><?php echo $this->lang->line('email'); ?>:</td>
                                            <td class="text-right"> <input type="email" class="form-control"  name="email" value="<?php echo set_value('email', $email); ?>" /><span class="alert-danger"><?php echo form_error('email');?></span></td>
                                        </tr>
                                        
                                        <!-- Additional Data Collection for Different Payment Methods -->
                                        <tr class="bordertoplightgray" id="card_details_row" style="display: none;">
                                            <td colspan="2">
                                                <div class="additional-fields">
                                                    <h6 style="margin: 0 0 10px 0; color: #333; font-weight: 600;">
                                                        <i class="fa fa-credit-card"></i> Card Details
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Card Number</label>
                                                                <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Expiry Date</label>
                                                                <input type="text" class="form-control" name="card_expiry" placeholder="MM/YY" maxlength="5">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>CVV</label>
                                                                <input type="text" class="form-control" name="card_cvv" placeholder="123" maxlength="4">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fa fa-shield"></i> Your card details are encrypted and secure
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="bordertoplightgray" id="momo_details_row" style="display: none;">
                                            <td colspan="2">
                                                <div class="additional-fields">
                                                    <h6 style="margin: 0 0 10px 0; color: #333; font-weight: 600;">
                                                        <i class="fa fa-mobile"></i> MTN Mobile Money
                                                    </h6>
                                                    <div class="form-group">
                                                        <label>Mobile Money Number</label>
                                                        <input type="text" class="form-control" name="momo_phone" placeholder="Enter your MTN Mobile Money number" value="<?php echo set_value('phone', $phone); ?>">
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
                                                    <h6 style="margin: 0 0 10px 0; color: #333; font-weight: 600;">
                                                        <i class="fa fa-mobile"></i> Mobile Money Number
                                                    </h6>
                                                    <div class="form-group">
                                                        <label>Mobile Money Number</label>
                                                        <input type="text" class="form-control" name="mobile_money_phone" placeholder="Enter your mobile money number" value="<?php echo set_value('phone', $phone); ?>">
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
                                                            <input type="radio" name="payment_method" value="CARD" id="payment_card">
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
                                                            <input type="radio" name="payment_method" value="MOMO" id="payment_momo">
                                                            <label for="payment_momo">Select</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <hr>
                                        <tr class="bordertoplightgray">
                                            <td><button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn paybackbtn"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button>  </td>
                                            <td class="text-right"><button type="submit"  name="search"  value="" class="btn btn-info pull-right submit_button" id="payButton" disabled><?php echo $this->lang->line('proceed_to_payment'); ?>  <i class="fa fa fa-chevron-right"></i></button>  </td>
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
            // Hide all additional fields first
            document.getElementById('card_details_row').style.display = 'none';
            document.getElementById('momo_details_row').style.display = 'none';
            document.getElementById('mobile_money_details_row').style.display = 'none';
            document.getElementById('payment_instructions_row').style.display = 'none';
            
            // Show relevant fields and instructions based on payment method
            if (provider === 'CARD') {
                document.getElementById('card_details_row').style.display = 'table-row';
                document.getElementById('payment_instructions_row').style.display = 'table-row';
                document.getElementById('payment_instructions_text').innerHTML = 
                    'Please enter your card details. Your payment will be processed securely through our payment gateway.';
            } else if (provider === 'MOMO') {
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
                const cardNumber = document.querySelector('input[name="card_number"]').value;
                const cardExpiry = document.querySelector('input[name="card_expiry"]').value;
                const cardCvv = document.querySelector('input[name="card_cvv"]').value;
                
                if (!cardNumber || !cardExpiry || !cardCvv) {
                    e.preventDefault();
                    alert('Please fill in all card details (Card Number, Expiry Date, CVV)');
                    return false;
                }
                
                // Basic card number validation
                if (cardNumber.replace(/\s/g, '').length < 16) {
                    e.preventDefault();
                    alert('Please enter a valid 16-digit card number');
                    return false;
                }
                
                // Basic expiry validation
                if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
                    e.preventDefault();
                    alert('Please enter expiry date in MM/YY format');
                    return false;
                }
                
                // Basic CVV validation
                if (cardCvv.length < 3) {
                    e.preventDefault();
                    alert('Please enter a valid CVV (3-4 digits)');
                    return false;
                }
            } else if (provider === 'MOMO') {
                const momoPhone = document.querySelector('input[name="momo_phone"]').value;
                if (!momoPhone) {
                    e.preventDefault();
                    alert('Please enter your MTN Mobile Money number');
                    return false;
                }
            }
        });
        
        // Card number formatting
        function formatCardNumber(input) {
            let value = input.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            input.value = formattedValue;
        }
        
        // Expiry date formatting
        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
        }
        
        // CVV formatting
        function formatCVV(input) {
            let value = input.value.replace(/\D/g, '');
            input.value = value;
        }
        
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
            
            // Add event listeners for formatting
            const cardNumberInput = document.querySelector('input[name="card_number"]');
            const cardExpiryInput = document.querySelector('input[name="card_expiry"]');
            const cardCvvInput = document.querySelector('input[name="card_cvv"]');
            
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function() { formatCardNumber(this); });
            }
            if (cardExpiryInput) {
                cardExpiryInput.addEventListener('input', function() { formatExpiryDate(this); });
            }
            if (cardCvvInput) {
                cardCvvInput.addEventListener('input', function() { formatCVV(this); });
            }
        });
        </script>
    </body>
</html>
