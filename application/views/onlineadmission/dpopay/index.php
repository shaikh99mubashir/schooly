<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />
    <title><?php echo $setting->name; ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url('fronttheme.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/themes/front-main.css">
    <script src="<?php echo base_url(); ?>backend/dist/js/theme-color.js"></script>
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
                            <form action="<?php echo base_url(); ?>onlineadmission/dpopay/pay" method="post">
                                <table class="table2" width="100%">
                                    <tr>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
                                    </tr>
                                    <tr class="border_bottom">
                                        <td>
                                            <span class="title"><?php echo $this->lang->line('online_admission_form_fees'); ?></span>
                                        </td>
                                        <td class="text-right"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($amount); ?></td>
                                    </tr>
                                    <?php if ($this->customlib->getGatewayProcessingFees($amount) > 0) { ?>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right">
                                            <?php echo $this->lang->line('processing_fees'); ?>: <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($this->customlib->getGatewayProcessingFees($amount)); ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right">
                                            <?php echo $this->lang->line('total'); ?>:
                                            <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($amount + $this->customlib->getGatewayProcessingFees($amount)); ?>
                                        </td>
                                    </tr>
                                    <?php if (!empty($error)) { ?>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2">
                                            <div class="alert alert-danger" role="alert" style="margin-bottom:0">
                                                <?php
                                                    $messages = [];
                                                    $plain_error = is_string($error) ? $error : '';

                                                    if (is_array($error)) {
                                                        foreach ($error as $key => $val) {
                                                            if (is_string($val) && trim(strip_tags($val)) !== '') {
                                                                $messages[] = strip_tags($val);
                                                            }
                                                        }
                                                    } elseif (!empty($plain_error)) {
                                                        $messages[] = strip_tags($plain_error);
                                                    }

                                                    if (empty($messages)) {
                                                        $messages[] = $this->lang->line('something_went_wrong');
                                                    }
                                                ?>
                                                <ul style="padding-left:18px; margin:0">
                                                    <?php foreach ($messages as $msg) { ?>
                                                        <li><?php echo htmlspecialchars($msg); ?></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr class="bordertoplightgray">
                                        <td><?php echo ('Credit Card Number'); ?>:</td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="creditcardnumber" value="<?php echo set_value('creditcardnumber'); ?>" />
                                            <span class="alert-danger"><?php echo form_error('creditcardnumber'); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="bordertoplightgray">
                                        <td><?php echo ('Credit Card Expiry'); ?>:</td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="creditcardexpiry" value="<?php echo set_value('creditcardexpiry'); ?>" placeholder="MMYY">
                                            <span class="alert-danger"><?php echo form_error('creditcardexpiry'); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="bordertoplightgray">
                                        <td><?php echo ('Credit Card CVV'); ?>:</td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="creditcardcvv" value="<?php echo set_value('creditcardcvv'); ?>">
                                            <span class="alert-danger"><?php echo form_error('creditcardcvv'); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="bordertoplightgray">
                                        <td><?php echo ('Card Holder Name'); ?>:</td>
                                        <td class="text-right">
                                            <input type="text" class="form-control" name="cardholdername" value="<?php echo set_value('cardholdername'); ?>">
                                            <span class="alert-danger"><?php echo form_error('cardholdername'); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="bordertoplightgray">
                                        <td>
                                            <button type="submit" onclick="window.history.go(-1); return false;" name="search" value="" class="btn paybackbtn">
                                                <i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?>
                                            </button>
                                        </td>
                                        <td class="text-right">
                                            <button type="submit" name="search" value="" class="btn btn-info pull-right submit_button">
                                                <?php echo $this->lang->line('pay'); ?> <i class="fa fa fa-chevron-right"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

