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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url('fronttheme.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/themes/front-main.css">
        <script src="<?php echo base_url(); ?>backend/dist/js/theme-color.js"></script>
        <link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet"/>
        <script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">

                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>">

                    </div>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('payment_details'); ?> </div>
                            <div class="padd2 paddtzero">
                            <form  action="<?php echo site_url('onlineadmission/stripe/complete'); ?>" method="POST"> 
                                    <table class="table2" width="100%">
                                        <tr>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
                                        </tr>

                                        <tr class="border_bottom">
                                            <td> 
                                                <span class="title"><?php echo $this->lang->line("online_admission_form_fees"); ?></span></td>
                                            <td class="text-right"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat( $amount); ?></td>
                                        </tr>
                                        <?php
                                        if($this->customlib->getGatewayProcessingFees($amount)>0){
                                            ?>
                                             <tr class="bordertoplightgray">
                                                <td> 
                                                <span class="title"><?php echo $this->lang->line("processing_fees"); ?></span></td>
                                        <td  class="text-right"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($this->customlib->getGatewayProcessingFees($amount)); ?></td>
                                    </tr>
                                            <?php
                                        }
                                        ?>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($amount+$this->customlib->getGatewayProcessingFees($amount)); ?></td>
                                    </tr> 
                                        
                                    </table>
                                </form>
                            <div class="divider"></div>
                            <div id="stripe-payment-message" class="hidden"></div>
                            <form id="stripe-payment-form" class="paddtlrb p-2" action="<?php echo site_url('onlineadmission/stripe/complete'); ?>" method="POST">
                                <input type='hidden' id='publishable_key' value='<?php echo $api_publishable_key; ?>'>
                                <input type='hidden' id='currency' value='<?php echo $currency_name; ?>'>
                                 <input type='hidden' id='baseurl' value='<?php echo site_url(); ?>'>
                                
                                <input type='hidden' id='description' value='<?php echo 'Online Admission fees deposit'; ?>'>

                                <input type="hidden" name="student_id" value="<?php //echo $this->customlib->getStudentSessionUserID(); ?>">
                                <input type="hidden" name="total" id="amount" value="<?php echo ((number_format((float)(convertBaseAmountCurrencyFormat($amount+$this->customlib->getGatewayProcessingFees($amount))), 2, '.', '')) * 100); ?>">
                                  
                                        <div id="stripe-payment-element">
                                            <!--Stripe.js will inject the Payment Element here to get card details-->
                                        </div>
                                        <div class="button-between pt10">
                                            <button type="button" onclick="window.history.go(-1); return false;" name="search" value="" class="btn paybackbtn"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back') ?></button>
                                            <button type="submit" class="pay btn btn-info pull-right submit_button" id="submit-button"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <i class="fa fa-money"></i> Pay Now</button>
                                            <div id="payment-reinitiate" class="hidden" >
                                <button class="btn btn-primary" type="button" onclick="reinitiateStripe()"> <i class="fa fa-money"></i>  Reinitiate Payment</button>
                            </div>
                                </div>
                                </div>    
                            </form>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </body>
    <script src="https://js.stripe.com/v3/"></script>
 <script src="<?php echo base_url('backend/js/stripe-checkout-admission.js') ?>" defer></script>
</html>