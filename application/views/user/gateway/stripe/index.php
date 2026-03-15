<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />
    <title><?php echo $this->customlib->getAppName(); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url('theme.css'); ?>">
    <link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>
    <script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>
    <style type="text/css">
  


        .table2 tr.border_bottom td {
            box-shadow: none;
            border-radius: 0;
            border-bottom: 1px solid var(--bs-with-border-color);
        }

        .table2 td {
            padding-bottom: 3px;
            padding-top: 6px;
        }

        .title {
            color: #0084B4;
            font-weight: 600 !important;
            font-size: 15px !important;
            ;
            display: inline;

        }

        .product-description {
            display: block;
            color: #999;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .text-fine {
            color: #bf4f4d;
        }

        .button-between {
            display: flex;
            justify-content: space-between;
            /*margin-top: 1rem;*/
        }

        .position-relative {
            position: relative;
        }

        .modal_inner_loader2 {
            display: none;
            left: 0px;
            top: 0px;
            z-index: 9999;
            position: absolute;
            width: 100%;
            height: 102%;
            opacity: .8;
            background: no-repeat rgb(0 0 0 / 9%);
            position: absolute;

        }

        .modal_inner_loader2:before {
            position: absolute;
            font-family: 'FontAwesome';
            top: calc(100% - 53%);
            left: calc(100% - 53%);
            content: "\f110";
            font-size: 3em;
            animation: fa-spin 2s infinite linear;
        }
    </style>

    <script type="text/javascript">
        var baseurl = "<?php echo base_url(); ?>";
    </script>
    <script>
        window.themeSettings = <?php echo json_encode($this->customlib->getCurrentThemeSetting() ?? new stdClass()); ?>;
    </script>


    <script src="<?php echo base_url(); ?>backend/dist/js/theme-color.js"></script>

</head>
<?php
$img = base_url('uploads/school_content/logo/' . $setting[0]['image']);
?>

<body>
    <div class="container">
 
        <div class="row">
            <div class="paddtop20">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <img src="<?php echo $img; ?>">
                </div>
                <div class="col-md-6 col-md-offset-3 mt20">
                    <div class="paymentbg">
                        <div class="invtext"><?php echo $this->lang->line('fees_payment_details'); ?></div>
                        <div class="position-relative">

                            <!-- Display the payment processing -->

                            <div class="modal_inner_loader2"></div>
                            <div class="padd2 paddtzero">
                                <table class="table2" width="100%">
                                    <tr>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount'); ?></th>
                                    </tr>
                                    <?php
                                    foreach ($student_fees_master_array as $fees_key => $fees_value) {
                                        //print_r($fees_value);die;
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="title"><?php if ($fees_value['is_system']) {
                                                                        echo $this->lang->line($fees_value['fee_group_name']);
                                                                    } else {
                                                                        echo $fees_value['fee_group_name'];
                                                                    } ?> </span>
                                                <span class="product-description">
                                                    <?php if ($fees_value['is_system']) {
                                                        echo $this->lang->line($fees_value['fee_type_code']);
                                                    } else {
                                                        echo $fees_value['fee_type_code'];
                                                    } ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $fees_value['amount_balance'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="border_bottom">
                                            <td>
                                                <span class="text-fine"><?php echo $this->lang->line('fine'); ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat((float) $fees_value['fine_balance'], 2, '.', ''); ?></td>
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
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?>: <?php echo $setting[0]['currency_symbol'] . amountFormat((float)(($params['fine_amount_balance'] + $params['total']) - $params['applied_fee_discount'] + $params['gateway_processing_charge']), 2, '.', ''); ?></td>
                                    </tr>

                                </table>
                                <div class="divider"></div>

                                <div id="stripe-payment-message" class="hidden"></div>

                                <form id="stripe-payment-form" class="paddtlrb pt0" action="<?php echo site_url('user/gateway/stripe/complete'); ?>" method="POST">
                                    <input type='hidden' id='publishable_key' value='<?php echo $params['api_publishable_key']; ?>'>
                                    <input type='hidden' id='currency' value='<?php echo $params['invoice']->currency_name; ?>'>
                                    <input type='hidden' id='description' value='<?php echo 'Online fees deposit'; ?>'>

                                    <input type="hidden" name="student_id" value="<?php echo $params['student_id']; ?>">
                                    <input type="hidden" name="total" id="amount" value="<?php echo (convertBaseAmountCurrencyFormat($params['fine_amount_balance'] + $params['total'] - $params['applied_fee_discount'] + $params['gateway_processing_charge'])); ?>">

                                    <div id="stripe-payment-element">
                                        <!--Stripe.js will inject the Payment Element here to get card details-->
                                    </div>
                                    <div class="button-between mt10">
                                        <button type="button" onclick="window.history.go(-1); return false;" name="search" value="" class="btn paybackbtn"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back') ?></button>
                                        <button type="submit" class="pay btn btn-primary" id="submit-button" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <i class="fa fa-money"></i> Pay Now</button>
                                        <div id="payment-reinitiate" class="hidden">
                                            <button class="btn btn-info pull-right submit_button" type="button" onclick="reinitiateStripe()"> <i class="fa fa-money"></i> Reinitiate Payment</button>
                                        </div>
                                    </div>

                            </div>

                            </form>

                            <!-- Display the payment reinitiate button -->

                        </div>
                        <!-- <div class="loader" style="display: block;"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://js.stripe.com/v3/"></script>
<script src="<?php echo base_url('backend/js/stripe-checkout.js') ?>" defer></script>
<script type="text/javascript">


</script>

</html>