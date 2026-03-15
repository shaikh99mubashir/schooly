<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();  ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10">
                <div class="nav-tabs-custom box box-primary theme-shadow">
                     <div class="box-header with-border">
                       <h3 class="box-title titlefix"><?php echo $this->lang->line('payment_methods'); ?></h3>
                    </div>
                    <ul class="nav nav-tabs nav-tabs2">
                        <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('paypal'); ?></a></li>
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('stripe'); ?></a></li>
                        <li><a href="#tab_3" data-toggle="tab"><?php echo $this->lang->line('payu'); ?></a></li>
                        <li><a href="#tab_4" data-toggle="tab"><?php echo $this->lang->line('ccavenue'); ?></a></li>
                        <li><a href="#tab_5" data-toggle="tab"><?php echo $this->lang->line('instamojo'); ?></a></li>
                        <li><a href="#tab_6" data-toggle="tab"><?php echo $this->lang->line('paystack'); ?></a></li>
                        <li><a href="#tab_7" data-toggle="tab"><?php echo $this->lang->line('razorpay'); ?></a></li>
                        <li><a href="#tab_8" data-toggle="tab"><?php echo $this->lang->line('paytm'); ?></a></li>
                        <li><a href="#tab_9" data-toggle="tab"><?php echo $this->lang->line('midtrans'); ?></a></li>
                        <li><a href="#tab_10" data-toggle="tab"><?php echo $this->lang->line('pesapal'); ?></a></li>
                        <li><a href="#tab_11" data-toggle="tab"><?php echo $this->lang->line('flutter_wave'); ?> </a></li>
                        <li><a href="#tab_12" data-toggle="tab"><?php echo $this->lang->line('ipay_africa'); ?></a></li>
                        <li><a href="#tab_13" data-toggle="tab"><?php echo $this->lang->line('jazzcash'); ?></a></li>
                        <li><a href="#tab_14" data-toggle="tab"><?php echo $this->lang->line('billplz'); ?></a></li>
                        <li><a href="#tab_15" data-toggle="tab"><?php echo $this->lang->line('sslcommerz'); ?></a></li>
                        <li><a href="#tab_16" data-toggle="tab"><?php echo $this->lang->line('walkingm'); ?></a></li>
                        <li><a href="#tab_17" data-toggle="tab"><?php echo $this->lang->line('mollie'); ?></a></li>
                        <li><a href="#tab_18" data-toggle="tab"><?php echo $this->lang->line('cashfree'); ?></a></li>
                        <li><a href="#tab_19" data-toggle="tab"><?php echo $this->lang->line('payfast'); ?></a></li>
                        <li><a href="#tab_20" data-toggle="tab"><?php echo $this->lang->line('toyyibPay'); ?></a></li>
                        <li><a href="#tab_21" data-toggle="tab"><?php echo $this->lang->line('twocheckout'); ?></a></li>
                        <li><a href="#tab_22" data-toggle="tab"><?php echo $this->lang->line('skrill'); ?></a></li>
                        <li><a href="#tab_23" data-toggle="tab"><?php echo $this->lang->line('payhere'); ?></a></li>
                        <li><a href="#tab_24" data-toggle="tab"><?php echo $this->lang->line('onepay'); ?></a></li>
                        <li><a href="#tab_25" data-toggle="tab"><?php echo $this->lang->line('dpo_pay'); ?></a></li>
                         <li><a href="#tab_26" data-toggle="tab"><?php echo $this->lang->line('momo_pay'); ?></a></li>
                        <li style="display:none"><a href="#tab_27" data-toggle="tab"><?php echo $this->lang->line('kowri'); ?></a></li>

                    </ul>
                    <div class="tab-content pb0">
                        <!-- /.tab-pane -->
                        <div class="tab-pane active" id="tab_1">
                            <form role="form" id="paypal" action="<?php echo site_url('admin/paymentsettings/paypal') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $paypal_result = check_in_array('paypal', $paymentlist); ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('paypal_username'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input autofocus="" id="name" name="paypal_username" placeholder="" type="text" class="form-control" value="<?php echo isset($paypal_result->api_username) ? $paypal_result->api_username : ""; ?>" />
                                                        <span class=" text text-danger paypal_username_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('paypal_password'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input id="name" name="paypal_password" placeholder="" type="password" class="form-control"  value="<?php echo isset($paypal_result->api_password) ? $paypal_result->api_password : ""; ?>" />
                                                        <span class=" text text-danger paypal_password_error"></span>
                                                    </div></div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('paypal_signature'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="name" name="paypal_signature" placeholder="" type="text" class="form-control"  value="<?php echo isset($paypal_result->api_signature) ? $paypal_result->api_signature : ""; ?>" />
                                                        <span class=" text text-danger paypal_signature_error"></span>
                                                    </div>
                                                </div>
                                                  <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input checked="checked" name="charge_type" class="finetype" onclick="get_payment_type('paypal_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($paypal_result->charge_type) && $paypal_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                        </div>
														<div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($paypal_result->charge_type) && $paypal_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('paypal_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)     
                                                                </label>
                                                            </div>
														</div>
														<div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($paypal_result->charge_type) && $paypal_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('paypal_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="paypal_charge_value" name="paypal_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($paypal_result->charge_value) ? $paypal_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger paypal_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.paypal.com/in/home" target="_blank">
                                                    <h5><?php echo $this->lang->line('multinational_payment_gateway'); ?></h5>
                                                    <img src="<?php echo $this->media_storage->getImageURL('backend/images/paypal.png'); ?>" width="200"><p>https://www.paypal.com</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 pl-sm-6">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary paypal_save ml-n1 float-sm-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
						
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <form role="form" id="stripe" id="stripe" action="<?php echo site_url('admin/paymentsettings/stripe') ?>" class="form-horizontal" method="post">
                                <div class="box-body minheight149">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $stripe_result = check_in_array('stripe', $paymentlist);?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('stripe_api_secret_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="api_secret_key" value="<?php echo isset($stripe_result->api_secret_key) ? $stripe_result->api_secret_key : ""; ?>">
                                                        <span class=" text text-danger api_secret_key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">
                                                        <?php echo $this->lang->line('stripe_publishable_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="api_publishable_key" value="<?php echo isset($stripe_result->api_publishable_key) ? $stripe_result->api_publishable_key : ""; ?>">
                                                        <span class=" text text-danger api_publishable_key_error"></span>
                                                    </div>
                                                </div>
                                                  <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input  checked="checked" name="charge_type" class="finetype" onclick="get_payment_type('stripe_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($stripe_result->charge_type) && $stripe_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>   
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($stripe_result->charge_type) && $stripe_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('stripe_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($stripe_result->charge_type) && $stripe_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('stripe_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="stripe_charge_value" name="stripe_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($stripe_result->charge_value) ? $stripe_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger stripe_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://stripe.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('multinational_payment_gateway'); ?></h5>
                                                    <img src="<?php echo $this->media_storage->getImageURL('backend/images/stripe.png') ?>" width="200"><p>https://stripe.com</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary stripe_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
						
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <form role="form" id="payu" id="custom" action="<?php echo site_url('admin/paymentsettings/payu') ?>" class="form-horizontal" method="post">
                                <div class="box-body minheight149">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $payu_result = check_in_array('payu', $paymentlist); ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('payu_money_key'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="key" value="<?php echo isset($payu_result->api_secret_key) ? $payu_result->api_secret_key : ""; ?>">
                                                        <span class="text text-danger key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('payu_money_salt'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="salt" value="<?php echo isset($payu_result->salt) ? $payu_result->salt : ""; ?>">
                                                        <span class="text text-danger salt_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input  checked="checked"  name="charge_type" class="finetype" onclick="get_payment_type('payu_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($payu_result->charge_type) && $payu_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($payu_result->charge_type) && $payu_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('payu_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                        </div>
                                                          <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($payu_result->charge_type) && $payu_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('payu_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="payu_charge_value" name="payu_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($payu_result->charge_value) ? $payu_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger payu_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.payumoney.com" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india'); ?></h5>
                                                    <img src="<?php echo $this->media_storage->getImageURL('backend/images/paym.png'); ?>" width="200"><p>https://www.payumoney.com</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary payu_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
						
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_4">
                            <form role="form" id="ccavenue"  action="<?php echo site_url('admin/paymentsettings/ccavenue') ?>" class="form-horizontal" method="post">
                                <div class="box-body minheight149">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $ccavenue_result = check_in_array('ccavenue', $paymentlist); ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('ccavenue_merchant_id'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="ccavenue_secret" value="<?php echo isset($ccavenue_result->api_secret_key) ? $ccavenue_result->api_secret_key : ""; ?>">
                                                        <span class="text text-danger ccavenue_secret_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('ccavenue_working_key'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="ccavenue_salt" value="<?php echo isset($ccavenue_result->salt) ? $ccavenue_result->salt : ""; ?>">
                                                        <span class="text text-danger ccavenue_salt_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('access_code'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="ccavenue_api_publishable_key" value="<?php echo isset($ccavenue_result->api_publishable_key) ? $ccavenue_result->api_publishable_key : ""; ?>">
                                                        <span class="text text-danger ccavenue_api_publishable_key_error"></span>
                                                    </div>
                                                </div>

                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?><small class="req"> </small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                         <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('ccavenue_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($ccavenue_result->charge_type) && $ccavenue_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>

                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($ccavenue_result->charge_type) && $ccavenue_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('ccavenue_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>

                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($ccavenue_result->charge_type) && $ccavenue_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('ccavenue_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="ccavenue_charge_value" name="ccavenue_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($ccavenue_result->charge_value) ? $ccavenue_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger ccavenue_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->


                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.ccavenue.com" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india'); ?></h5>
                                                    
                                                    <img src="<?php echo $this->media_storage->getImageURL('backend/images/ccavenue.png'); ?>" width="200"><p>https://www.ccavenue.com</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary ccavenue_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
						
                        <div class="tab-pane" id="tab_5">
                            <form role="form" id="instamojo"  action="<?php echo site_url('admin/paymentsettings/instamojo') ?>" class="form-horizontal" method="post">
                                <div class="box-body minheight149">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$instamojo_result = check_in_array('instamojo', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('private_api_key'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="instamojo_apikey" value="<?php echo isset($instamojo_result->api_secret_key) ? $instamojo_result->api_secret_key : ""; ?>">
                                                        <span class="text text-danger instamojo_apikey_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('private_auth_token'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="instamojo_authtoken" value="<?php echo isset($instamojo_result->api_publishable_key) ? $instamojo_result->api_publishable_key : ""; ?>">
                                                        <span class="text text-danger instamojo_authtoken_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('private_salt'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="instamojo_salt" value="<?php echo isset($instamojo_result->salt) ? $instamojo_result->salt : ""; ?>">
                                                        <span class="text text-danger instamojo_salt_error"></span>
                                                    </div>
                                                </div>
                                                  <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('instamojo_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($instamojo_result->charge_type) && $instamojo_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($instamojo_result->charge_type) && $instamojo_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('instamojo_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($instamojo_result->charge_type) && $instamojo_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('instamojo_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="instamojo_charge_value" name="instamojo_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($instamojo_result->charge_value) ? $instamojo_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger instamojo_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.instamojo.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india'); ?></h5>
                                                    
                                                    <img src="<?php echo $this->media_storage->getImageURL('backend/images/instamojo.png'); ?>" width="200"> <p>https://www.instamojo.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary instamojo_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
						
                        <div class="tab-pane " id="tab_6">
                            <form role="form" id="paystack" action="<?php echo site_url('admin/paymentsettings/paystack') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$paystack_result = check_in_array('paystack', $paymentlist);
 
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('paystack_secret_key'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="paystack_secretkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paystack_result->api_secret_key) ? $paystack_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger paystack_secretkey_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('paystack_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($paystack_result->charge_type) && $paystack_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($paystack_result->charge_type) && $paystack_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('paystack_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                             <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($paystack_result->charge_type) && $paystack_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('paystack_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="paystack_charge_value" name="paystack_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($paystack_result->charge_value) ? $paystack_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger paystack_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://paystack.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries'); ?></h5>                                            
                                                    
                                                    <img src="<?php echo $this->media_storage->getImageURL('backend/images/paystack.png'); ?>" width="200"><p>https://paystack.com</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary paystack_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_7">
                            <form role="form" id="razorpay" action="<?php echo site_url('admin/paymentsettings/razorpay') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$razorpay_result = check_in_array('razorpay', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('razorpay_key_id'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="razorpay_keyid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($razorpay_result->api_publishable_key) ? $razorpay_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger razorpay_keyid_error"></span>
                                                    </div>  </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('razorpay_key_secret'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="razorpay_secretkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($razorpay_result->api_secret_key) ? $razorpay_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger razorpay_secretkey_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('razorpay_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($razorpay_result->charge_type) && $razorpay_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($razorpay_result->charge_type) && $razorpay_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('razorpay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($razorpay_result->charge_type) && $razorpay_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('razorpay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="razorpay_charge_value" name="razorpay_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($razorpay_result->charge_value) ? $razorpay_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger razorpay_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://razorpay.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/razorpay.jpg<?php echo img_time(); ?>" width="200"><p>https://razorpay.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary razorpay_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="tab_8">
                            <form role="form" id="paytm" action="<?php echo site_url('admin/paymentsettings/paytm') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$paytm_result = check_in_array('paytm', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="paytm_merchantid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->api_publishable_key) ? $paytm_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger paytm_merchantid_error"></span>
                                                    </div>  </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="paytm_merchantkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->api_secret_key) ? $paytm_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger paytm_merchantkey_error"></span>
                                                    </div>  </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('website'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="paytm_website" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->paytm_website) ? $paytm_result->paytm_website : ""; ?>" />
                                                        <span class=" text text-danger paytm_website_error"></span>
                                                    </div>  </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('indusrty_type'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="paytm_industrytype" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->paytm_industrytype) ? $paytm_result->paytm_industrytype : ""; ?>" />
                                                        <span class=" text text-danger paytm_industrytype_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('paytm_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($paytm_result->charge_type) && $paytm_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($paytm_result->charge_type) && $paytm_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('paytm_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($paytm_result->charge_type) && $paytm_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('paytm_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="paytm_charge_value" name="paytm_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($paytm_result->charge_value) ? $paytm_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger paytm_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://paytm.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/paytm.jpg<?php echo img_time(); ?>" width="200"><p>https://paytm.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary paytm_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_9">
                            <form role="form" id="midtrans" action="<?php echo site_url('admin/paymentsettings/midtrans') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                                <?php
$midtrans_result = check_in_array('midtrans', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('server_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="midtrans_serverkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($midtrans_result->api_secret_key) ? $midtrans_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger midtrans_serverkey_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('midtrans_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($midtrans_result->charge_type) && $midtrans_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($midtrans_result->charge_type) && $midtrans_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('midtrans_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                             <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($midtrans_result->charge_type) && $midtrans_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('midtrans_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="midtrans_charge_value" name="midtrans_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($midtrans_result->charge_value) ? $midtrans_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger midtrans_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://midtrans.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_indonesia'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/midtrans.jpg<?php echo img_time(); ?>" width="200"><p>https://midtrans.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_10">
                            <form role="form" id="pesapal" action="<?php echo site_url('admin/paymentsettings/pesapal') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$pesapal_result = check_in_array('pesapal', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('consumer_key'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="pesapal_consumer_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($pesapal_result->api_publishable_key) ? $pesapal_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger pesapal_consumer_key_error"></span>
                                                    </div>  </div>
                                                    <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('consumer_secret'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="pesapal_consumer_secret" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($pesapal_result->api_secret_key) ? $pesapal_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger pesapal_consumer_secret_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('pesapal_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($pesapal_result->charge_type) && $pesapal_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                             <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($pesapal_result->charge_type) && $pesapal_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('pesapal_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                             <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($pesapal_result->charge_type) && $pesapal_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('pesapal_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="pesapal_charge_value" name="pesapal_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($pesapal_result->charge_value) ? $pesapal_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger pesapal_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.pesapal.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/pesapal.jpg<?php echo img_time(); ?>" width="200"><p>https://www.pesapal.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                            <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                         <div class="tab-pane " id="tab_11">
                            <form role="form" id="flutterwave" action="<?php echo site_url('admin/paymentsettings/flutterwave') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$flutterwave_result = check_in_array('flutterwave', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('public_key'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="public_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($flutterwave_result->api_publishable_key) ? $flutterwave_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger public_key_error"></span>
                                                    </div>  </div>
                                                   <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('secret_key'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($flutterwave_result->api_secret_key) ? $flutterwave_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger secret_key_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                         <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('flutterwave_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($flutterwave_result->charge_type) && $flutterwave_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($flutterwave_result->charge_type) && $flutterwave_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('flutterwave_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                             <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($flutterwave_result->charge_type) && $flutterwave_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('flutterwave_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="flutterwave_charge_value" name="flutterwave_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($flutterwave_result->charge_value) ? $flutterwave_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger flutterwave_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://flutterwave.com/us/" target="_blank">
                                                    <h5><?php echo $this->lang->line('multinational_payment_gateway'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/flutterwave.png<?php echo img_time(); ?>" width="200"><p>https://flutterwave.com/us</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                             <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                              <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
						
                        <div class="tab-pane " id="tab_12">
                            <form role="form" id="ipayafrica" action="<?php echo site_url('admin/paymentsettings/ipayafrica') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$ipayafrica_result = check_in_array('ipayafrica', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('vendorid'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="ipayafrica_vendorid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($ipayafrica_result->api_publishable_key) ? $ipayafrica_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger ipayafrica_vendorid_error"></span>
                                                    </div>  </div>
                                                    <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('hashkey'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="ipayafrica_hashkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($ipayafrica_result->api_secret_key) ? $ipayafrica_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger ipayafrica_hashkey_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('ipayafrica_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($ipayafrica_result->charge_type) && $ipayafrica_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                             <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($ipayafrica_result->charge_type) && $ipayafrica_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('ipayafrica_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($ipayafrica_result->charge_type) && $ipayafrica_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('ipayafrica_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="ipayafrica_charge_value" name="ipayafrica_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($ipayafrica_result->charge_value) ? $ipayafrica_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger ipayafrica_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://ipayafrica.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/ipayafrica.png<?php echo img_time(); ?>" width="200"><p>https://ipayafrica.com//</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                            <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                             <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_13">
                            <form role="form" id="jazzcash" action="<?php echo site_url('admin/paymentsettings/jazzcash') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$jazzcash_result = check_in_array('jazzcash', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('pp_merchantid'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="jazzcash_pp_MerchantID" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($jazzcash_result->api_secret_key) ? $jazzcash_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger jazzcash_pp_MerchantID_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('pp_password'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="jazzcash_pp_Password" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($jazzcash_result->api_password) ? $jazzcash_result->api_password : ""; ?>" />
                                                        <span class=" text text-danger jazzcash_pp_Password_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('jazzcash_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($jazzcash_result->charge_type) && $jazzcash_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                          <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($jazzcash_result->charge_type) && $jazzcash_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('jazzcash_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($jazzcash_result->charge_type) && $jazzcash_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('jazzcash_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="jazzcash_charge_value" name="jazzcash_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($jazzcash_result->charge_value) ? $jazzcash_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger jazzcash_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.jazzcash.com.pk/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_pakistan'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/jazzcash.jpg<?php echo img_time(); ?>" width="200"><p>https://www.jazzcash.com.pk/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary jazzcash_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_14">
                            <form role="form" id="billplz" action="<?php echo site_url('admin/paymentsettings/billplz') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$billplz_result = check_in_array('billplz', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('api_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="billplz_api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($billplz_result->api_secret_key) ? $billplz_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger billplz_api_key_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                         <?php echo $this->lang->line('customer_service_email'); ?>

                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="billplz_customer_service_email" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($billplz_result->api_email) ? $billplz_result->api_email : ""; ?>" />
                                                        <span class=" text text-danger billplz_customer_service_email_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('billplz_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($billplz_result->charge_type) && $billplz_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($billplz_result->charge_type) && $billplz_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('billplz_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($billplz_result->charge_type) && $billplz_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('billplz_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="billplz_charge_value" name="billplz_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($billplz_result->charge_value) ? $billplz_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger billplz_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.billplz.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_malaysia'); ?></h5>
                                                    <img src="<?php echo base_url(); ?>/backend/images/billplz.jpg<?php echo img_time(); ?>" width="200"><p>https://www.billplz.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary jazzcash_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                         <div class="tab-pane " id="tab_15">
                            <form role="form" id="sslcommerz" action="<?php echo site_url('admin/paymentsettings/sslcommerz') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$sslcommerz_result = check_in_array('sslcommerz', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('store_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="sslcommerz_api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($sslcommerz_result->api_publishable_key) ? $sslcommerz_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger sslcommerz_api_key_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                         <?php echo $this->lang->line('store_password'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="sslcommerz_store_password" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($sslcommerz_result->api_password) ? $sslcommerz_result->api_password : ""; ?>" />
                                                        <span class=" text text-danger sslcommerz_store_password_error"></span>
                                                    </div>  </div>
                                                     <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('sslcommerz_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($sslcommerz_result->charge_type) && $sslcommerz_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($sslcommerz_result->charge_type) && $sslcommerz_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('sslcommerz_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($sslcommerz_result->charge_type) && $sslcommerz_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('sslcommerz_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="sslcommerz_charge_value" name="sslcommerz_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($sslcommerz_result->charge_value) ? $sslcommerz_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger sslcommerz_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.sslcommerz.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_bangladesh'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/sslcommerz.png<?php echo img_time(); ?>" width="200"><br><br><p>https://www.sslcommerz.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary sslcommerz_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                               
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_16">
                            <form role="form" id="walkingm" action="<?php echo site_url('admin/paymentsettings/walkingm') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$walkingm_result = check_in_array('walkingm', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('client_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="walkingm_client_id" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($walkingm_result->api_publishable_key) ? $walkingm_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger walkingm_client_id_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                         <?php echo $this->lang->line('client_secret'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="walkingm_client_secret" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($walkingm_result->api_secret_key) ? $walkingm_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger walkingm_client_secret_error"></span>
                                                    </div>  </div>
                                                     <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('walkingm_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($walkingm_result->charge_type) && $walkingm_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($walkingm_result->charge_type) && $walkingm_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('walkingm_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                          <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($walkingm_result->charge_type) && $walkingm_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('walkingm_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="walkingm_charge_value" name="walkingm_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($walkingm_result->charge_value) ? $walkingm_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger walkingm_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://walkingm.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_liberia'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/walkingm.png<?php echo img_time(); ?>" width="200"><br><br><p>https://walkingm.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary walkingm_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                         <div class="tab-pane " id="tab_17">
                            <form role="form" id="mollie" action="<?php echo site_url('admin/paymentsettings/mollie') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$mollie_result = check_in_array('mollie', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('api_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="mollie_api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($mollie_result->api_publishable_key) ? $mollie_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger mollie_api_key_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('mollie_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($mollie_result->charge_type) && $mollie_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($mollie_result->charge_type) && $mollie_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('mollie_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($mollie_result->charge_type) && $mollie_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('mollie_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="mollie_charge_value" name="mollie_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($mollie_result->charge_value) ? $mollie_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger mollie_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.mollie.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_european_countries'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/mollie.png<?php echo img_time(); ?>" width="200"><br><br><p>https://www.mollie.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary mollie_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_18">
                            <form role="form" id="cashfree" action="<?php echo site_url('admin/paymentsettings/cashfree') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$cashfree_result = check_in_array('cashfree', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('app_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="cashfree_app_id" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($cashfree_result->api_publishable_key) ? $cashfree_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger cashfree_app_id_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('secret_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="cashfree_secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($cashfree_result->api_secret_key) ? $cashfree_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger cashfree_secret_key_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('cashfree_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($cashfree_result->charge_type) && $cashfree_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($cashfree_result->charge_type) && $cashfree_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('cashfree_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($cashfree_result->charge_type) && $cashfree_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('cashfree_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="cashfree_charge_value" name="cashfree_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($cashfree_result->charge_value) ? $cashfree_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger cashfree_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.cashfree.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/cashfree.png<?php echo img_time(); ?>" width="200"><br><br><p>https://www.cashfree.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary cashfree_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_19">
                            <form role="form" id="payfast" action="<?php echo site_url('admin/paymentsettings/payfast') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$payfast_result = check_in_array('payfast', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="payfast_api_publishable_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($payfast_result->api_publishable_key) ? $payfast_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger payfast_api_publishable_key_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="payfast_api_secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($payfast_result->api_secret_key) ? $payfast_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger payfast_api_secret_key_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('security_passphrase'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="payfast_salt" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($payfast_result->salt) ? $payfast_result->salt : ""; ?>" />
                                                        <span class=" text text-danger payfast_salt_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"    name="charge_type" class="finetype" onclick="get_payment_type('payfast_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($payfast_result->charge_type) && $payfast_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($payfast_result->charge_type) && $payfast_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('payfast_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($payfast_result->charge_type) && $payfast_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('payfast_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="payfast_charge_value" name="payfast_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($payfast_result->charge_value) ? $payfast_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger payfast_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.payfast.co.za/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/payfast.png<?php echo img_time(); ?>" width="200"><br><br><p>https://www.payfast.co.za/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary payfast_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                         <div class="tab-pane " id="tab_20">
                            <form role="form" id="toyyibpay" action="<?php echo site_url('admin/paymentsettings/toyyibPay') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$toyyibpay_result = check_in_array('toyyibpay', $paymentlist);
?>
                                                    <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('secret_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="toyyibpay_api_secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($toyyibpay_result->api_secret_key) ? $toyyibpay_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger toyyibpay_api_secret_key_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('category_code'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="toyyibpay_category_code" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($toyyibpay_result->api_signature) ? $toyyibpay_result->api_signature : ""; ?>" />
                                                        <span class=" text text-danger toyyibpay_category_code_error"></span>
                                                    </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                       <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('toyyibpay_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($toyyibpay_result->charge_type) && $toyyibpay_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($toyyibpay_result->charge_type) && $toyyibpay_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('toyyibpay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($toyyibpay_result->charge_type) && $toyyibpay_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('toyyibpay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="toyyibpay_charge_value" name="toyyibpay_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($toyyibpay_result->charge_value) ? $toyyibpay_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger toyyibpay_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://toyyibpay.com/" target="_blank">
                                                     <h5><?php echo $this->lang->line('payment_gateway_for_malaysia'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/toyyibpay.png<?php echo img_time(); ?>" width="200"><br><br><p>https://toyyibpay.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary toyyibpay_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                         <div class="tab-pane " id="tab_21">
                            <form role="form" id="twocheckout" action="<?php echo site_url('admin/paymentsettings/twocheckout') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$twocheckout_result = check_in_array('twocheckout', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_code'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <input  name="twocheckout_api_publishable_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($twocheckout_result->api_publishable_key) ? $twocheckout_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger twocheckout_api_publishable_key_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('secret_key'); ?>
                                                <small class="req"> *</small>
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input  name="twocheckout_api_secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($twocheckout_result->api_secret_key) ? $twocheckout_result->api_secret_key : ""; ?>" />
                                                <span class=" text text-danger twocheckout_api_secret_key_error"></span>
                                                </div>
                                                </div>
                                                <div class="form-group">
                                                <label class="control-label col-md-12 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                <?php echo 'IPN Endpoint for 2Checkout  (' . base_url() . 'gateway_ins/twocheckout)'; ?>
                                                </label>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('twocheckout_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($twocheckout_result->charge_type) && $twocheckout_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($twocheckout_result->charge_type) && $twocheckout_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('twocheckout_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($twocheckout_result->charge_type) && $twocheckout_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('twocheckout_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="twocheckout_charge_value" name="twocheckout_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($twocheckout_result->charge_value) ? $twocheckout_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger twocheckout_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->

                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.2checkout.com/" target="_blank">
                                                     <h5><?php echo $this->lang->line('payment_gateway_for_malaysia'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/twocheckout.png<?php echo img_time(); ?>" width="200"><br><br><p>https://www.2checkout.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary toyyibpay_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_22">
                            <form role="form" id="skrill" action="<?php echo site_url('admin/paymentsettings/skrill') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$skrill_result = check_in_array('skrill', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_account_email'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <input  name="skrill_api_email" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($skrill_result->api_email) ? $skrill_result->api_email : ""; ?>" />
                                                        <span class=" text text-danger skrill_api_email_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('merchant_secret_salt'); ?>
                                                <small class="req"> *</small>
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input  name="skrill_salt" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($skrill_result->salt) ? $skrill_result->salt : ""; ?>" />
                                                <span class=" text text-danger skrill_salt_error"></span>
                                                </div>
                                                </div>
                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('skrill_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($skrill_result->charge_type) &&  $skrill_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($skrill_result->charge_type) && $skrill_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('skrill_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)       
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($skrill_result->charge_type) && $skrill_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('skrill_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="skrill_charge_value" name="skrill_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($skrill_result->charge_value) ? $skrill_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger skrill_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->

                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.skrill.com/" target="_blank">
                                                     <h5><?php echo $this->lang->line('payment_gateway_for_malaysia'); ?></h5>
                                                     <br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/skrill.jpg<?php echo img_time(); ?>" width="200">
                                                    <br>
                                                    <br>
                                                    <p>https://www.skrill.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary toyyibpay_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_23">
                            <form role="form" id="payhere" action="<?php echo site_url('admin/paymentsettings/payhere') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$payhere_result = check_in_array('payhere', $paymentlist);
?>

                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('merchant_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <input  name="payhere_api_publishable_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($payhere_result->api_publishable_key) ? $payhere_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger payhere_api_publishable_key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('merchant_secret'); ?>
                                                <small class="req"> *</small>
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input  name="payhere_api_secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($payhere_result->api_secret_key) ? $payhere_result->api_secret_key : ""; ?>" />
                                                <span class=" text text-danger payhere_api_secret_key_error"></span>
                                                </div>
                                                </div>

                                                 <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                       <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('payhere_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($payhere_result->charge_type) && $payhere_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($payhere_result->charge_type) && $payhere_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('payhere_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)      
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($payhere_result->charge_type) && $payhere_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('payhere_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="payhere_charge_value" name="payhere_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($payhere_result->charge_value) ? $payhere_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger payhere_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->

                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.payhere.lk/" target="_blank">
                                                     <h5><?php echo $this->lang->line('payment_gateway_for_sri_lanka'); ?></h5>
                                                     <br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/payhere.png<?php echo img_time(); ?>" width="200">
                                                    <br>
                                                    <br>
                                                    <p>https://www.payhere.lk/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary toyyibpay_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_24">
                           <form role="form" id="onepay" action="<?php echo site_url('admin/paymentsettings/onepay') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php
$onepay_result = check_in_array('onepay', $paymentlist);
?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('onepay_merchant_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="onepay_merchant_id" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($onepay_result->api_publishable_key) ? $onepay_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger onepay_merchant_id_error"></span>
                                                    </div>  </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('access_code'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="onepay_salt" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($onepay_result->salt) ? $onepay_result->salt : ""; ?>" />
                                                        <span class=" text text-danger onepay_salt_error"></span>
                                                    </div>  </div>
                                                     <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                         <?php echo $this->lang->line('hash_key'); ?>

                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="onepay_api_signature" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($onepay_result->api_signature) ? $onepay_result->api_signature : ""; ?>" />
                                                        <span class=" text text-danger onepay_api_signature_error"></span>
                                                    </div>  </div>

                                                     <!-- processing charge -->
                                                  <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                       <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('onepay_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($onepay_result->charge_type) &&  $onepay_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($onepay_result->charge_type) && $onepay_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('onepay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)      
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($onepay_result->charge_type) && $onepay_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('onepay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="onepay_charge_value" name="onepay_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($onepay_result->charge_value) ? $onepay_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger onepay_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.onepay.vn/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_vietnam'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/onepay.svg" width="200"><br><br><p>https://www.onepay.vn</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary sslcommerz_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                               
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_25">
                           <form role="form" id="dpopay" action="<?php echo site_url('admin/paymentsettings/dpopay') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $dpopay_result = check_in_array('dpopay', $paymentlist); ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('company_token'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="dpopay_company_token" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($dpopay_result->api_secret_key) ? $dpopay_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger dpopay_company_token_error"></span>
                                                    </div>  
												</div>                                         
                                                   

                                                <!-- processing charge -->
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                       <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('dpopay_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($dpopay_result->charge_type) &&  $dpopay_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($dpopay_result->charge_type) && $dpopay_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('dpopay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)      
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($dpopay_result->charge_type) && $dpopay_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('dpopay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="dpopay_charge_value" name="dpopay_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($dpopay_result->charge_value) ? $dpopay_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger dpopay_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://dpogroup.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_zambia'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/dpo_pay_by_network.webp" width="200"><br><br><p>https://dpogroup.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary sslcommerz_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            
                                                <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                          <div class="tab-pane " id="tab_26">
                           <form role="form" id="momopay" action="<?php echo site_url('admin/paymentsettings/momopay') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $momo_result = check_in_array('momopay', $paymentlist); ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('subscription_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="subscriptionKey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($momo_result->api_secret_key) ? $momo_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger subscriptionKey_error"></span>
                                                    </div>  
                                                </div>                                         
                                                   <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('api_key'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="apiKey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($momo_result->api_publishable_key) ? $momo_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger apiKey_error"></span>
                                                    </div>  
                                                </div> 
                                                 <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('user_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="userId" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($momo_result->api_username) ? $momo_result->api_username : ""; ?>" />
                                                        <span class=" text text-danger userId_error"></span>
                                                    </div>  
                                                </div>  

                                                <!-- processing charge -->
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('momopay_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($dpopay_result->charge_type) &&  $dpopay_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                          <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($momo_result->charge_type) && $momo_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('momopay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)      
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($momo_result->charge_type) && $momo_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('momopay_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="momopay_charge_value" name="momopay_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($momo_result->charge_value) ? $momo_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger momopay_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://momodeveloper.mtn.com/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/momopay.png" width="200"><br><br><p>https://momodeveloper.mtn.com/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary sslcommerz_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                             
                                                <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane " id="tab_27" style="display:none">
                           <form role="form" id="kowri" action="<?php echo site_url('admin/paymentsettings/kowri') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                            <div class="col-md-7">
                                                <?php $kowri_result = check_in_array('kowri', $paymentlist); ?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('app_id'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="kowri_app_id" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($kowri_result->api_publishable_key) ? $kowri_result->api_publishable_key : ""; ?>" />
                                                        <span class=" text text-danger kowri_app_id_error"></span>
                                                    </div>  
                                                </div>                                         
                                                   <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('app_reference'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="kowri_app_reference" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($kowri_result->api_username) ? $kowri_result->api_username : ""; ?>" />
                                                        <span class=" text text-danger kowri_app_reference_error"></span>
                                                    </div>  
                                                </div> 
                                                 <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('secret'); ?>
                                                        <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="kowri_secret" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($kowri_result->api_secret_key) ? $kowri_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger kowri_secret_error"></span>
                                                    </div>  
                                                </div>  

                                                <!-- processing charge -->
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('processing_fees_type'); ?>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input   checked="checked"   name="charge_type" class="finetype" onclick="get_payment_type('kowri_charge_value',this.value)" id="charge_type" value="none" type="radio" <?php if(isset($kowri_result->charge_type) &&  $kowri_result->charge_type=='none'){ ?> checked="checked" <?php } ?>><?php echo $this->lang->line('none'); ?>    
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline">
                                                                    <input <?php if(isset($kowri_result->charge_type) && $kowri_result->charge_type=='percentage'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('kowri_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="percentage" type="radio"><?php echo $this->lang->line('percentage'); ?> (%)      
                                                                </label>
                                                            </div>
                                                            </div>
                                                           <div id="input-type" class="row">
                                                            <div class="col-sm-4 col-md-12 col-lg-8">
                                                                <label class="radio-inline" class="control-label col-lg-5 col-md-5 col-sm-5">
                                                                    <input <?php if(isset($kowri_result->charge_type) && $kowri_result->charge_type=='fix'){ ?> checked="checked" <?php } ?> onclick="get_payment_type('kowri_charge_value',this.value)" name="charge_type" class="finetype" id="charge_type" value="fix" type="radio"><?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?>)
                                                                </label>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-5 col-md-5 col-sm-5 col-xs-12" for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('percentage_fix_amount'); ?><small class="req"></small>
                                                    </label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                        <input id="kowri_charge_value" name="kowri_charge_value" placeholder="" type="text" class="form-control"  value="<?php echo isset($kowri_result->charge_value) ? $kowri_result->charge_value : ""; ?>" />
                                                        <span class=" text text-danger kowri_charge_value_error"></span>
                                                    </div>
                                                </div>
                                                <!-- processing charge -->
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.kowri.app/" target="_blank">
                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries'); ?></h5><br>
                                                    <img src="<?php echo base_url(); ?>/backend/images/kowri.jpg" width="200"><br><br><p>https://www.kowri.app/</p></a>
                                            </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary kowri_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
					

                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="<?php echo site_url('admin/paymentsettings/setting') ?>" id="payment_gateway" method="POST">
                        <div class="box-body minheight199">
                            <div class="form-group"> <!-- Radio group !-->
                                <?php
$radio_check = check_selected($paymentlist);
?>

                                <label class="control-label"><?php echo $this->lang->line('select_payment_gateway'); ?></label>

                                <div class="radio">
                                    <label>
                                        <input type="radio" name="payment_setting" value="paypal" <?php
if ($radio_check == 'paypal') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('paypal'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="stripe" <?php
if ($radio_check == 'stripe') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('stripe'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="payu" <?php
if ($radio_check == 'payu') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('payu'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="ccavenue" <?php
if ($radio_check == 'ccavenue') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('ccavenue'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="instamojo" <?php
if ($radio_check == 'instamojo') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('instamojo'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="payment_setting" value="paystack" <?php
if ($radio_check == 'paystack') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('paystack'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="razorpay" <?php
if ($radio_check == 'razorpay') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('razorpay'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="paytm" <?php
if ($radio_check == 'paytm') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('paytm'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="midtrans" <?php
if ($radio_check == 'midtrans') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('midtrans'); ?>
                                    </label>
                                </div>
                                  <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="pesapal" <?php
if ($radio_check == 'pesapal') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('pesapal'); ?>
                                    </label>
                                </div>
                                 <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="flutterwave" <?php
if ($radio_check == 'flutterwave') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('flutter_wave'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="ipayafrica" <?php
if ($radio_check == 'ipayafrica') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('ipay_africa'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="jazzcash" <?php
if ($radio_check == 'jazzcash') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('jazzcash'); ?>
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="billplz" <?php
if ($radio_check == 'billplz') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('billplz'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="sslcommerz" <?php
if ($radio_check == 'sslcommerz') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('sslcommerz'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="walkingm" <?php
if ($radio_check == 'walkingm') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('walkingm'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="mollie" <?php
if ($radio_check == 'mollie') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('mollie'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="cashfree" <?php
if ($radio_check == 'cashfree') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('cashfree'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="payfast" <?php
if ($radio_check == 'payfast') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('payfast'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="toyyibpay" <?php
if ($radio_check == 'toyyibpay') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('toyyibPay'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="twocheckout" <?php
if ($radio_check == 'twocheckout') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('twocheckout'); ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="skrill" <?php
if ($radio_check == 'skrill') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('skrill'); ?>
                                    </label>
                                </div>
                                 <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="payhere" <?php
if ($radio_check == 'payhere') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('payhere'); ?>
                                    </label>
                                </div>
                                
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="onepay" <?php
if ($radio_check == 'onepay') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('onepay'); ?>
                                    </label>
                                </div>
								
								<div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="dpopay" <?php
if ($radio_check == 'dpopay') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('dpopay'); ?>
                                    </label>
                                </div>
								
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="momopay" <?php
if ($radio_check == 'momopay') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('momopay_radio'); ?>
                                    </label>
                                </div>
								
							<div class="radio" style="display:none">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="kowri" <?php
if ($radio_check == 'kowri') {
    echo "checked";
}
?>>
                                        <?php echo $this->lang->line('kowri'); ?>
                                    </label>
                                </div>
                                 <span class="text text-danger payment_setting_error"></span>
                                <div class="radio">
                                    <label>
                                        <input type="radio"  name="payment_setting" value="none" <?php
if ($radio_check == 'none') {
    echo "checked";
}
?>>
                                         <?php echo $this->lang->line('none'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                <button type="submit" class="btn btn-primary pull-right payment_gateway_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                            <?php }?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php

function check_selected($array)
{
    $selected = "none";
    if (!empty($array)) {

        foreach ($array as $a => $element) {
            if ($element->is_active == "yes") {
                $selected = $element->payment_type;
            }
        }
    }
    return $selected;
}

function check_in_array($find, $array)
{
    if (!empty($array)) {
        foreach ($array as $element) {
            if ($find == $element->payment_type) {
                return $element;
            }
        }
    }
    $object            = new stdClass();
    $object->id        = "";
    $object->type      = "";
    $object->api_id    = "";
    $object->username  = "";
    $object->url       = "";
    $object->name      = "";
    $object->contact   = "";
    $object->password  = "";
    $object->authkey   = "";
    $object->senderid  = "";
    $object->is_active = "";
    return $object;
}
?>

<script type="text/javascript">

    function get_payment_type(id,value){
        $("#"+id).val("");
        if(value=="none"){
            $("#"+id).attr("readonly",true);
        }else{
            $("#"+id).attr("readonly",false);
        }
    }

    $("#payment_gateway_config").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payment_gateway_save");
        $this.button('loading');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payment_gateway_config").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
                //if fails
            }, complete: function () {
                $this.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
	
    $("#payment_gateway").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payment_gateway_save");
        $this.button('loading');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payment_gateway").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
                //if fails
            }, complete: function () {
                $this.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#paypal").submit(function (e) {
        $("[class$='_error']").html("");

        var $this = $(".paypal_save");
        $this.button('loading');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#paypal").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
                //if fails
            }, complete: function () {
                $this.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#stripe").submit(function (e) {
        $("[class$='_error']").html("");

        var $this = $(".stripe_save");
        $this.button('loading');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#stripe").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
                //if fails
            }, complete: function () {
                $this.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#payu").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payu_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payu").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#ccavenue").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".ccavenue_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#ccavenue").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#paystack").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".paystack_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#paystack").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#instamojo").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".instamojo_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#instamojo").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#razorpay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".razorpay_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#razorpay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#paytm").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".paytm_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#paytm").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#midtrans").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".midtrans_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#midtrans").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#pesapal").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".pesapal_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#pesapal").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

  $("#ipayafrica").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".ipayafrica_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#ipayafrica").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".ipayafrica_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

   $("#flutterwave").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".flutterwave_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#flutterwave").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".flutterwave_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

     $("#jazzcash").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".jazzcash_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#jazzcash").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

     $("#billplz").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".billplz_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#billplz").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

      $("#sslcommerz").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".sslcommerz_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#sslcommerz").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#walkingm").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".walkingm_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#walkingm").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

      $("#mollie").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".mollie_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#mollie").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#cashfree").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".cashfree_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#cashfree").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

       $("#payfast").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payfast_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payfast").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#toyyibpay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".toyyibpay_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#toyyibpay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#twocheckout").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".twocheckout_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#twocheckout").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#skrill").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".skrill_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#skrill").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            },
            complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#payhere").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".skrill_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payhere").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            },
            complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#onepay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".onepay_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#onepay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });
	
	$("#dpopay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".onepay_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#dpopay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#momopay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".momopay_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#momopay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#kowri").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".kowri_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#kowri").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

</script>