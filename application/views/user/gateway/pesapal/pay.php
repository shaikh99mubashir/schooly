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
        <style type="text/css">
            .error-box {
                background-color: #f8d7da;
                border: 2px solid #f5c6cb;
                border-radius: 6px;
                color: #721c24;
                padding: 20px;
                margin: 20px auto;
                max-width: 800px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                font-size: 14px;
            }
            .error-box .error-title {
                font-weight: bold;
                font-size: 18px;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
            }
            .error-box .error-title i {
                margin-right: 10px;
                font-size: 24px;
            }
            .error-box .error-message {
                margin: 10px 0;
                line-height: 1.8;
                font-size: 15px;
            }
            .error-box .error-message strong {
                color: #721c24;
                font-weight: 600;
            }
            .error-container {
                padding: 30px 20px;
                background: #ededed;
                min-height: 100vh;
            }
            iframe {
                border: 1px solid #ddd;
                border-radius: 4px;
                background: #fff;
            }
            .iframe-wrapper {
                position: relative;
                margin-top: 20px;
            }
            .error-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255,255,255,0.95);
                z-index: 10;
                display: none;
            }
        </style> 
    </head>
    <body style="background: #ededed;">
        <div class="error-container">
            <div class="container">
                <?php if(!empty($api_error)){ ?>
                    <div class="error-box">
                        <div class="error-title">
                            <i class="fa fa-exclamation-triangle"></i>
                            <?php echo $this->lang->line('error'); ?>: <?php echo $this->lang->line('payment_gateway_error'); ?>
                        </div>
                        <div class="error-message">
                            <?php 
                            foreach($api_error as $value){
                                foreach ($value as $key => $value1) {
                                    if($key == 'consumer_key_unknown'){
                                        echo "<strong>".$this->lang->line('problem').":</strong> ".$this->lang->line('consumer_key_unknown')."<br>";
                                        echo "<strong>".$this->lang->line('advice').":</strong> ".$this->lang->line('please_check_pesapal_credentials_in_payment_settings');
                                    } else {
                                        echo $value1;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <div id="iframe-error-box" class="error-box" style="display: none;">
                    <div class="error-title">
                        <i class="fa fa-exclamation-triangle"></i>
                        <?php echo $this->lang->line('error'); ?>: <?php echo $this->lang->line('payment_gateway_error'); ?>
                    </div>
                    <div class="error-message" id="iframe-error-message"></div>
                    <div style="margin-top: 15px;">
                        <button onclick="window.history.back();" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </button>
                    </div>
                </div>
                <div class="iframe-wrapper">
                    <div id="error-overlay" class="error-overlay"></div>
                    <iframe id="pesapal-iframe" src="<?php echo $iframe_src;?>" width="100%" height="720px" scrolling="auto" frameBorder="0" onerror="showIframeError()">
                        <p><?php echo $this->lang->line('unable_to_load_the_payment_page'); ?></p>
                    </iframe>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            // Monitor iframe for errors
            var iframe = document.getElementById('pesapal-iframe');
            var errorBox = document.getElementById('iframe-error-box');
            var errorMessage = document.getElementById('iframe-error-message');
            var errorOverlay = document.getElementById('error-overlay');
            var errorDetected = false;
            var checkInterval = null;
            
            // Function to show error box and redirect to index
            function showIframeError(message) {
                if(errorDetected) return; // Prevent multiple triggers
                errorDetected = true;
                
                // Clear any interval
                if(checkInterval) {
                    clearInterval(checkInterval);
                }
                
                // Redirect to index page with error parameter
                var errorMsg = 'consumer_key_unknown';
                if(message) {
                    // Extract error from message if available
                    if(message.includes('consumer_key_unknown')) {
                        errorMsg = 'consumer_key_unknown';
                    }
                }
                
                // Redirect to index page with error
                window.location.href = '<?php echo base_url("user/gateway/pesapal"); ?>?error=' + encodeURIComponent(errorMsg);
            }
            
            // Listen for postMessage from iframe (if Pesapal supports it)
            window.addEventListener('message', function(event) {
                // Verify origin for security
                if(event.origin !== 'https://www.pesapal.com' && event.origin !== 'https://pesapal.com') return;
                
                if(event.data && (event.data.error || event.data.type === 'error')) {
                    var errorMsg = '<strong>Problem:</strong> ' + (event.data.message || event.data.error || 'consumer_key_unknown') + '<br>';
                    errorMsg += '<strong>Advice:</strong> Please check your Pesapal Consumer Key and Consumer Secret in Payment Settings.';
                    showIframeError(errorMsg);
                }
            }, false);
            
            // Monitor iframe URL changes (if accessible)
            var lastUrl = '';
            var urlCheckCount = 0;
            
            // Check iframe content periodically
            checkInterval = setInterval(function() {
                urlCheckCount++;
                if(urlCheckCount > 10) {
                    clearInterval(checkInterval);
                    return;
                }
                
                try {
                    var currentUrl = iframe.src;
                    if(currentUrl !== lastUrl) {
                        lastUrl = currentUrl;
                        
                        // Check if URL contains error indicators
                        if(currentUrl.includes('error') || currentUrl.includes('invalid') || currentUrl.includes('unknown')) {
                            showIframeError();
                            return;
                        }
                    }
                    
                    // Try to access iframe content
                    try {
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        if(iframeDoc && iframeDoc.body) {
                            var bodyText = iframeDoc.body.innerText || iframeDoc.body.textContent || '';
                            
                            // Check for error messages
                            if(bodyText && (
                                bodyText.includes('consumer_key_unknown') || 
                                bodyText.includes('Problem:') || 
                                (bodyText.includes('consumer_key') && bodyText.length < 500) ||
                                (bodyText.includes('Advice:') && bodyText.length < 500)
                            )) {
                                
                                // Extract error message
                                var errorMatch = bodyText.match(/Problem:\s*([^\n|]+)/);
                                var adviceMatch = bodyText.match(/Advice:\s*([^\n]+)/);
                                
                                var fullError = '';
                                if(errorMatch) {
                                    fullError += '<strong>Problem:</strong> ' + errorMatch[1].trim() + '<br>';
                                } else {
                                    fullError += '<strong>Problem:</strong> consumer_key_unknown<br>';
                                }
                                if(adviceMatch && adviceMatch[1].trim() !== '>>|') {
                                    fullError += '<strong>Advice:</strong> ' + adviceMatch[1].trim();
                                } else {
                                    fullError += '<strong>Advice:</strong> Please check your Pesapal Consumer Key and Consumer Secret in Payment Settings. Make sure they are correctly configured.';
                                }
                                
                                showIframeError(fullError);
                                return;
                            }
                        }
                    } catch(e) {
                        // Cross-origin - expected
                    }
                } catch(e) {
                    // Access denied
                }
            }, 1500);
            
            // Try to access iframe content after load
            iframe.onload = function() {
                setTimeout(function() {
                    try {
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        if(iframeDoc && iframeDoc.body) {
                            var bodyText = iframeDoc.body.innerText || iframeDoc.body.textContent || '';
                            
                            // Check if page is blank or has minimal content (likely an error page)
                            if(bodyText && bodyText.length < 500 && (
                                bodyText.includes('consumer_key_unknown') || 
                                bodyText.includes('Problem:') || 
                                bodyText.includes('Advice:')
                            )) {
                                var errorMatch = bodyText.match(/Problem:\s*([^\n|]+)/);
                                var adviceMatch = bodyText.match(/Advice:\s*([^\n]+)/);
                                
                                var fullError = '';
                                if(errorMatch) {
                                    fullError += '<strong>Problem:</strong> ' + errorMatch[1].trim() + '<br>';
                                } else {
                                    fullError += '<strong>Problem:</strong> consumer_key_unknown<br>';
                                }
                                if(adviceMatch && adviceMatch[1].trim() !== '>>|') {
                                    fullError += '<strong>Advice:</strong> ' + adviceMatch[1].trim();
                                } else {
                                    fullError += '<strong>Advice:</strong> Please check your Pesapal Consumer Key and Consumer Secret in Payment Settings.';
                                }
                                
                                showIframeError(fullError);
                            }
                        }
                    } catch(e) {
                        // Cross-origin restrictions - expected
                    }
                }, 3000);
            };
            
            // Handle iframe load errors
            iframe.onerror = function() {
                showIframeError();
            };
            
            // Fallback: if iframe takes too long or shows blank/error, show error
            setTimeout(function() {
                if(!errorDetected) {
                    try {
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        if(iframeDoc && iframeDoc.body) {
                            var bodyText = iframeDoc.body.innerText || iframeDoc.body.textContent || '';
                            if(bodyText.length < 200 && (bodyText.includes('Problem:') || bodyText.includes('consumer_key'))) {
                                showIframeError();
                            }
                        }
                    } catch(e) {
                        // Can't access
                    }
                }
            }, 5000);
        </script>
    </body>
</html>

