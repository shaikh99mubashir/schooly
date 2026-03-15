<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paymentsettings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('payment_methods', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/paymentsettings');
        $data['title']  = 'SMS Config List';     
        $data['statuslist']  = $this->customlib->getStatus();
        $data['paymentlist'] = $this->paymentsetting_model->get();
        $data['api_config'] = $this->paymentsetting_model->getActiveMethod();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/payment_setting/paymentsettingList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function paypal()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('paypal_username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('paypal_password', $this->lang->line('password'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('paypal_signature', $this->lang->line('signature'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('paypal_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'payment_type'  => 'paypal',
                'api_username'  => $this->input->post('paypal_username'),
                'api_password'  => $this->input->post('paypal_password'),
                'api_signature' => $this->input->post('paypal_signature'),
                'paypal_demo'   => 'TRUE',
                'charge_type'   => $this->input->post('charge_type'),
                'charge_value'  => $this->input->post('paypal_charge_value'),
            );
            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'paypal_username'           => form_error('paypal_username'),
                'paypal_password'           => form_error('paypal_password'),
                'paypal_signature'          => form_error('paypal_signature'),
                'paypal_charge_value'       => form_error('paypal_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function stripe()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('api_secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('api_publishable_key', $this->lang->line('publishable_key'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('stripe_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key'      => $this->input->post('api_secret_key'),
                'api_publishable_key' => $this->input->post('api_publishable_key'),
                'payment_type'        => 'stripe',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('stripe_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'api_secret_key'         => form_error('api_secret_key'),
                'api_publishable_key'    => form_error('api_publishable_key'),
                'stripe_charge_value'    => form_error('stripe_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function payu()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('key', $this->lang->line('key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('salt', $this->lang->line('salt'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('payu_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }
        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key' => $this->input->post('key'),
                'salt'           => $this->input->post('salt'),
                'payment_type'   => 'payu',
                'charge_type'   => $this->input->post('charge_type'),
                'charge_value'  => $this->input->post('payu_charge_value'),
            );
            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'key'               => form_error('key'),
                'salt'              => form_error('salt'),
                'payu_charge_value' => form_error('payu_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function twocheckout()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('twocheckout_api_publishable_key', $this->lang->line('merchant_code'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('twocheckout_api_secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');
         if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('twocheckout_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }
        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key'      => $this->input->post('twocheckout_api_secret_key'),
                'api_publishable_key' => $this->input->post('twocheckout_api_publishable_key'),
                'payment_type'        => 'twocheckout',
                'charge_type'    => $this->input->post('charge_type'),
                'charge_value'   => $this->input->post('twocheckout_charge_value'),
            );
            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'twocheckout_api_secret_key'      => form_error('twocheckout_api_secret_key'),
                'twocheckout_api_publishable_key' => form_error('twocheckout_api_publishable_key'),
                'twocheckout_charge_value'        => form_error('twocheckout_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function ccavenue()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('ccavenue_secret', $this->lang->line('ccavenue_merchant_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ccavenue_salt', $this->lang->line('ccavenue_working_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ccavenue_api_publishable_key', $this->lang->line('access_code'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none"){
           $this->form_validation->set_rules('ccavenue_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }
        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key'      => $this->input->post('ccavenue_secret'),
                'salt'                => $this->input->post('ccavenue_salt'),
                'api_publishable_key' => $this->input->post('ccavenue_api_publishable_key'),
                'payment_type'        => 'ccavenue',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('ccavenue_charge_value'),
            );
            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'ccavenue_secret'              => form_error('ccavenue_secret'),
                'ccavenue_salt'                => form_error('ccavenue_salt'),
                'ccavenue_api_publishable_key' => form_error('ccavenue_api_publishable_key'),
                'ccavenue_charge_value'        => form_error('ccavenue_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function paystack()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('paystack_secretkey', $this->lang->line('key'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('paystack_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key' => $this->input->post('paystack_secretkey'),
                'payment_type'   => 'paystack',
                 'charge_type'   => $this->input->post('charge_type'),
                'charge_value'   => $this->input->post('paystack_charge_value'),
            );
            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'paystack_secretkey'    => form_error('paystack_secretkey'),
                'paystack_charge_value' => form_error('paystack_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function instamojo()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('instamojo_apikey', $this->lang->line('private_api_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('instamojo_authtoken', $this->lang->line('private_auth_token'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('instamojo_salt', $this->lang->line('private_salt'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('instamojo_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key'      => $this->input->post('instamojo_apikey'),
                'api_publishable_key' => $this->input->post('instamojo_authtoken'),
                'salt'                => $this->input->post('instamojo_salt'),
                'payment_type'        => 'instamojo',
                 'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('instamojo_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'instamojo_apikey'          => form_error('instamojo_apikey'),
                'instamojo_authtoken'       => form_error('instamojo_authtoken'),
                'instamojo_salt'            => form_error('instamojo_salt'),
                'instamojo_charge_value'    => form_error('instamojo_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function razorpay()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('razorpay_keyid', $this->lang->line('key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('razorpay_secretkey', $this->lang->line('key'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('razorpay_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key'      => $this->input->post('razorpay_secretkey'),
                'api_publishable_key' => $this->input->post('razorpay_keyid'),
                'payment_type'        => 'razorpay',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('razorpay_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'razorpay_keyid'     => form_error('razorpay_keyid'),
                'razorpay_secretkey' => form_error('razorpay_secretkey'),
                'razorpay_charge_value' => form_error('razorpay_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function paytm()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('paytm_merchantid', $this->lang->line('merchant_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('paytm_merchantkey', $this->lang->line('merchant_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('paytm_website', $this->lang->line('website'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('paytm_industrytype', $this->lang->line('indusrty_type'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('paytm_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key'      => $this->input->post('paytm_merchantkey'),
                'api_publishable_key' => $this->input->post('paytm_merchantid'),
                'paytm_website'       => $this->input->post('paytm_website'),
                'paytm_industrytype'  => $this->input->post('paytm_industrytype'),
                'payment_type'        => 'paytm',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('paytm_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'paytm_merchantkey'  => form_error('paytm_merchantkey'),
                'paytm_merchantid'   => form_error('paytm_merchantid'),
                'paytm_website'      => form_error('paytm_website'),
                'paytm_industrytype' => form_error('paytm_industrytype'),
                'paytm_charge_value' => form_error('paytm_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function midtrans()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('midtrans_serverkey', $this->lang->line('key'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('midtrans_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key' => $this->input->post('midtrans_serverkey'),
                'payment_type'   => 'midtrans',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('midtrans_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'midtrans_serverkey' => form_error('midtrans_serverkey'),
                'midtrans_charge_value' => form_error('midtrans_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function pesapal()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('pesapal_consumer_key', $this->lang->line('consumer_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('pesapal_consumer_secret', $this->lang->line('consumer_secret'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('pesapal_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key'      => $this->input->post('pesapal_consumer_secret'),
                'api_publishable_key' => $this->input->post('pesapal_consumer_key'),
                'payment_type'        => 'pesapal',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('pesapal_charge_value'),
            );

            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'pesapal_consumer_key'    => form_error('pesapal_consumer_key'),
                'pesapal_consumer_secret' => form_error('pesapal_consumer_secret'),
                'pesapal_charge_value' => form_error('pesapal_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function ipayafrica()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('ipayafrica_vendorid', $this->lang->line('vendorid'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ipayafrica_hashkey', $this->lang->line('hashkey'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('ipayafrica_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key'      => $this->input->post('ipayafrica_hashkey'),
                'api_publishable_key' => $this->input->post('ipayafrica_vendorid'),
                'payment_type'        => 'ipayafrica',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('ipayafrica_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'ipayafrica_vendorid' => form_error('ipayafrica_vendorid'),
                'ipayafrica_hashkey'  => form_error('ipayafrica_hashkey'),
                'ipayafrica_charge_value'  => form_error('ipayafrica_charge_value'),

            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function flutterwave()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('public_key', $this->lang->line('public_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('flutterwave_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(

                'api_publishable_key' => $this->input->post('public_key'),
                'api_secret_key'      => $this->input->post('secret_key'),
                'payment_type'        => 'flutterwave',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('flutterwave_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'public_key' => form_error('public_key'),
                'secret_key' => form_error('secret_key'),
                'flutterwave_charge_value' => form_error('flutterwave_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function jazzcash()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('jazzcash_pp_MerchantID', $this->lang->line('merchant_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('jazzcash_pp_Password', $this->lang->line('pp_password'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('jazzcash_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key' => $this->input->post('jazzcash_pp_MerchantID'),
                'api_password'   => $this->input->post('jazzcash_pp_Password'),
                'payment_type'   => 'jazzcash',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('jazzcash_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'jazzcash_pp_MerchantID' => form_error('jazzcash_pp_MerchantID'),
                'jazzcash_pp_Password'   => form_error('jazzcash_pp_Password'),
                'jazzcash_charge_value'   => form_error('jazzcash_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function billplz()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('billplz_api_key', $this->lang->line('api_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('billplz_customer_service_email', $this->lang->line('customer_service_email'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('billplz_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_secret_key' => $this->input->post('billplz_api_key'),
                'api_email'      => $this->input->post('billplz_customer_service_email'),
                'payment_type'   => 'billplz',
                'charge_type'    => $this->input->post('charge_type'),
                'charge_value'   => $this->input->post('billplz_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'billplz_api_key'                => form_error('billplz_api_key'),
                'billplz_customer_service_email' => form_error('billplz_customer_service_email'),
                'billplz_charge_value' => form_error('billplz_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function sslcommerz()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('sslcommerz_api_key', $this->lang->line('store_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sslcommerz_store_password', $this->lang->line('sslcommerz_store_password'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('sslcommerz_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_password'        => $this->input->post('sslcommerz_store_password'),
                'api_publishable_key' => $this->input->post('sslcommerz_api_key'),
                'payment_type'        => 'sslcommerz',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('sslcommerz_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'sslcommerz_store_password' => form_error('sslcommerz_store_password'),
                'sslcommerz_api_key'        => form_error('sslcommerz_api_key'),
                'sslcommerz_charge_value'   => form_error('sslcommerz_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function walkingm()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('walkingm_client_id', $this->lang->line('client_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('walkingm_client_secret', $this->lang->line('client_secret'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('walkingm_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_publishable_key' => $this->input->post('walkingm_client_id'),
                'api_secret_key'      => $this->input->post('walkingm_client_secret'),
                'payment_type'        => 'walkingm',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('walkingm_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'walkingm_client_id'     => form_error('walkingm_client_id'),
                'walkingm_client_secret' => form_error('walkingm_client_secret'),
                'walkingm_charge_value' => form_error('walkingm_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function mollie()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('mollie_api_key', $this->lang->line('api_key'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('mollie_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_publishable_key' => $this->input->post('mollie_api_key'),
                'payment_type'        => 'mollie',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('mollie_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'mollie_api_key' => form_error('mollie_api_key'),
                'mollie_charge_value' => form_error('mollie_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function cashfree()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('cashfree_app_id', $this->lang->line('app_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('cashfree_secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');
        
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('cashfree_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_publishable_key' => $this->input->post('cashfree_app_id'),
                'api_secret_key'      => $this->input->post('cashfree_secret_key'),
                'payment_type'        => 'cashfree',
                'charge_type'        => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('cashfree_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'cashfree_app_id'       => form_error('cashfree_app_id'),
                'cashfree_secret_key'   => form_error('cashfree_secret_key'),
                'cashfree_charge_value' => form_error('cashfree_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function payfast()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('payfast_api_publishable_key', $this->lang->line('merchant_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payfast_api_secret_key', $this->lang->line('merchant_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payfast_salt', $this->lang->line('security_passphrase'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('payfast_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_publishable_key' => $this->input->post('payfast_api_publishable_key'),
                'api_secret_key'      => $this->input->post('payfast_api_secret_key'),
                'salt'                => $this->input->post('payfast_salt'),
                'payment_type'        => 'payfast',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('payfast_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'payfast_api_publishable_key' => form_error('payfast_api_publishable_key'),
                'payfast_api_secret_key'      => form_error('payfast_api_secret_key'),
                'payfast_salt'                => form_error('payfast_salt'),
                'payfast_charge_value'        => form_error('payfast_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function toyyibPay()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('toyyibpay_api_secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('toyyibpay_category_code', $this->lang->line('category_code'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('toyyibpay_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key' => $this->input->post('toyyibpay_api_secret_key'),
                'api_signature'  => $this->input->post('toyyibpay_category_code'),
                'payment_type'   => 'toyyibpay',
                'charge_type'    => $this->input->post('charge_type'),
                'charge_value'   => $this->input->post('toyyibpay_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'toyyibpay_api_secret_key' => form_error('toyyibpay_api_secret_key'),
                'toyyibpay_category_code'  => form_error('toyyibpay_category_code'),
                'toyyibpay_charge_value'  => form_error('toyyibpay_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function skrill()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('skrill_api_email', $this->lang->line('merchant_account_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('skrill_salt', $this->lang->line('merchant_secret_salt'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('skrill_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }
        if ($this->form_validation->run()) {
            $data = array(
                'api_email'    => $this->input->post('skrill_api_email'),
                'salt'         => $this->input->post('skrill_salt'),
                'payment_type' => 'skrill',
                'charge_type'  => $this->input->post('charge_type'),
                'charge_value' => $this->input->post('skrill_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'skrill_api_email'          => form_error('skrill_api_email'),
                'skrill_salt'               => form_error('skrill_salt'),
                'skrill_charge_value'       => form_error('skrill_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function payhere()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('payhere_api_publishable_key', $this->lang->line('merchant_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payhere_api_secret_key', $this->lang->line('merchant_secret'), 'trim|required|xss_clean');
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('payhere_charge_value',$this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }
        if ($this->form_validation->run()) {
            $data = array(
                'api_publishable_key' => $this->input->post('payhere_api_publishable_key'),
                'api_secret_key'      => $this->input->post('payhere_api_secret_key'),
                'payment_type'        => 'payhere',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('payhere_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'payhere_api_publishable_key' => form_error('payhere_api_publishable_key'),
                'payhere_api_secret_key'      => form_error('payhere_api_secret_key'),
                'payhere_charge_value'        => form_error('payhere_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }
    }

    public function onepay() 
    {     
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('onepay_merchant_id', $this->lang->line('onepay_merchant_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('onepay_salt', $this->lang->line('access_code'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('onepay_api_signature', $this->lang->line('hash_key'), 'trim|required|xss_clean');

        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('onepay_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {

            $data = array(
                'api_publishable_key'   => $this->input->post('onepay_merchant_id'),
                'salt'                  => $this->input->post('onepay_salt'),
                'api_signature'         => $this->input->post('onepay_api_signature'),
                'payment_type'          => 'onepay',
                'charge_type'           => $this->input->post('charge_type'),
                'charge_value'          => $this->input->post('onepay_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'onepay_merchant_id' => form_error('onepay_merchant_id'),
                'onepay_salt' => form_error('onepay_salt'),
                'onepay_api_signature' => form_error('onepay_api_signature'),
                'onepay_charge_value' => form_error('onepay_charge_value'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }
	
	public function dpopay()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('dpopay_company_token', $this->lang->line('company_token'), 'trim|required|xss_clean');
        
		
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('dpopay_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key'      => $this->input->post('dpopay_company_token'), 
                'payment_type'        => 'dpopay',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('dpopay_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'dpopay_company_token'         => form_error('dpopay_company_token'),                
                'dpopay_charge_value'    => form_error('dpopay_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }
    
    public function momopay(){
    
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('subscriptionKey', $this->lang->line('subscription_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('apiKey', $this->lang->line('api_key'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('userId', $this->lang->line('user_id'), 'trim|required|xss_clean');
        
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('momopay_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_secret_key'      => $this->input->post('subscriptionKey'), 
                'api_publishable_key'      => $this->input->post('apiKey'), 
                'api_username'      => $this->input->post('userId'), 
                'payment_type'        => 'momopay',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('momopay_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'subscriptionKey'         => form_error('subscriptionKey'), 
                'apiKey'         => form_error('apiKey'),                 
                'momopay_charge_value'    => form_error('momopay_charge_value'),
                'userId' => form_error('userId'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function kowri()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('kowri_app_id', $this->lang->line('app_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('kowri_app_reference', $this->lang->line('app_reference'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('kowri_secret', $this->lang->line('secret'), 'trim|required|xss_clean');
        
        if($this->input->post('charge_type')!="none" && $this->input->post('charge_type')!=""){
           $this->form_validation->set_rules('kowri_charge_value', $this->lang->line('percentage_fix_amount'), 'trim|required|xss_clean|numeric'); 
        }

        if ($this->form_validation->run()) {
            $data = array(
                'api_publishable_key' => $this->input->post('kowri_app_id'),
                'api_secret_key'      => $this->input->post('kowri_secret'),
                'api_username'        => $this->input->post('kowri_app_reference'),
                'payment_type'        => 'kowri',
                'charge_type'         => $this->input->post('charge_type'),
                'charge_value'        => $this->input->post('kowri_charge_value'),
            );

            $this->paymentsetting_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'kowri_app_id'         => form_error('kowri_app_id'),
                'kowri_app_reference'  => form_error('kowri_app_reference'),
                'kowri_secret'         => form_error('kowri_secret'),
                'kowri_charge_value'   => form_error('kowri_charge_value'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }


    public function payment_gateway_config()
    {
        $account_type = $this->input->post('account_type');
        $this->form_validation->set_error_delimiters('', '');
  
        $this->form_validation->set_rules('payment_setting', $this->lang->line('payment_setting'), array('required',
            array('paymentsetting', array($this->paymentsetting_model, 'valid_paymentsetting')),
        )
        );
          $this->form_validation->set_rules('account_type', ('account_type --r'), 'trim|required|xss_clean');
          if ($account_type != "none") {
           $this->form_validation->set_rules('fine_amount', $this->lang->line('fine_amount'), 'trim|required|xss_clean');
       }
        if ($this->form_validation->run()) {
            $paymentsetting = $this->input->post('payment_setting');
            
            $other          = false;
            if ($account_type == "none") {
                $other = true;
                $data  = array(
                    'charge_type' => NULL,
                    'charge_value'    => NULL,
                );
            } else {
                $data = array(
                    'charge_type' => $this->input->post('account_type'),
                    'charge_value'    => $this->input->post('fine_amount'),
                    'payment_type'=>$this->input->post('payment_setting')
                );
            }
            $this->paymentsetting_model->payment_gateway_config($data, $other);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'payment_setting' => form_error('payment_setting'),
                'account_type' => form_error('account_type')
            );
            if ($account_type != "none") {
				$data['fine_amount']  = form_error('fine_amount');
            }

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function setting()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('payment_setting', $this->lang->line('payment_setting'), array('required',
            array('paymentsetting', array($this->paymentsetting_model, 'valid_paymentsetting')),
        )
        );
        if ($this->form_validation->run()) {
            $paymentsetting = $this->input->post('payment_setting');
            $other          = false;
            if ($paymentsetting == "none") {
                $other = true;
                $data  = array(
                    'is_active' => 'no',
                );
            } else {
                $data = array(
                    'payment_type' => $this->input->post('payment_setting'),
                    'is_active'    => 'yes',
                );
            }
            $this->paymentsetting_model->active($data, $other);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'payment_setting' => form_error('payment_setting'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

}
