<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stripe extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('stripe_payment');
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');

    }

    public function index() {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $total = $this->amount;
        $data['amount'] = ($total);
        $data['name'] = $online_data->firstname." ".$online_data->lastname;
        $data['currency_name'] = $this->customlib->get_currencyShortName();
        $data['student_id']=$online_data->id;
        $data['api_publishable_key'] = $this->pay_method->api_publishable_key;
        $this->load->view('onlineadmission/stripe/index', $data);
    }

    public function create_payment_intent()
    {
        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);
        $this->stripe_payment->PaymentIntent($jsonObj );
    }

      public function create_customer()
    {
        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);
        $reference = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $total = $this->amount;
        $jsonObj->fullname = $online_data->firstname;
        $jsonObj->email = $online_data->email;
        $this->stripe_payment->AddCustomer($jsonObj);
    }


     public function insert_payment()
    {

        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);
        $return_response = $this->stripe_payment->InsertTransaction($jsonObj);
        if ($return_response['status']) {
            $payment = $return_response['payment'];
            // If transaction was successful
            if (!empty($payment) && $payment->status == 'succeeded') {
                 
                //=============================

        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date = date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));  
        
      

            $amount = $this->session->userdata('payment_amount');
            $reference  = $this->session->userdata('reference');
            $transactionid                      = $payment->id;
            $gateway_response['online_admission_id']   = $reference;
            $gateway_response['paid_amount']    = $this->amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'Stripe';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['processing_charge_type']   = $this->pay_method->charge_type;
            $gateway_response['processing_charge_value']   = $this->customlib->getGatewayProcessingFees($this->amount);
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_stripe_txn_id') . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
            $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
           // redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));

                echo json_encode(['status'=>1,'msg' => 'Transaction successful.','return_url'=>base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no)]);

                //=====================================

            } else {
                http_response_code(500);
                echo json_encode(['status'=>0,'msg' => 'Transaction has been failed!','return_url'=>base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no)]);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status'=>0,'msg' => $return_response['error']]);
        }
    }

    public function complete() {
        
        $stripeToken         = $this->input->post('stripeToken');
        $stripeTokenType     = $this->input->post('stripeTokenType');
        $stripeEmail         = $this->input->post('stripeEmail');
        $data                = $this->input->post();
        $data['stripeToken'] = $stripeToken;
        $data['total']  = $this->customlib->getGatewayProcessingFees($this->amount)+$this->amount;
        $data['description'] = $this->lang->line('online_admission_form_fees');
        $data['currency']    = $this->customlib->get_currencyShortName();
        $response            = $this->stripe_payment->payment($data);
  
        if ($response->isSuccessful()) {
            $transactionid = $response->getTransactionReference();
            $response      = $response->getData();
            if ($response['status'] == 'succeeded') {
                $amount = $this->session->userdata('payment_amount');
                $reference  = $this->session->userdata('reference');
                $online_data = $this->onlinestudent_model->getAdmissionData($reference);
                $apply_date=date("Y-m-d H:i:s");               
                
                $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
                        
                $currentdate = date('Y-m-d');
                $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
                $this->onlinestudent_model->edit($adddata);
                
                $gateway_response['online_admission_id']   = $reference; 
                $gateway_response['paid_amount']    = $this->customlib->getGatewayProcessingFees($this->amount)+$this->amount;
                $gateway_response['transaction_id'] = $transactionid;
                $gateway_response['payment_mode']   = 'stripe';
                $gateway_response['payment_type']   = 'online';
                $gateway_response['processing_charge_type']   = $this->pay_method->charge_type;
            $gateway_response['processing_charge_value']   = $this->customlib->getGatewayProcessingFees($this->amount);
                $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_stripe_txn_id')   . $transactionid;
                $gateway_response['date']           = date("Y-m-d H:i:s");
                $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
				 
                $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
              
 $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                
                redirect(base_url("onlineadmission/checkout/successinvoice//".$online_data->reference_no));
            }
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            redirect(site_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

}

?>