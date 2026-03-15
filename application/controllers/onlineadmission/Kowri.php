<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kowri extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('kowri_payment');
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
        $data['email'] = $online_data->email;
        $data['phone'] = $online_data->mobileno;
        $data['currency_name'] = $this->customlib->get_currencyShortName();
        // Payment options are now hardcoded to only show PayNow Card and PayNow MOMO
        $data['payment_options'] = array();
        $this->load->view('onlineadmission/kowri/index', $data);
    }

    public function pay() {
        $this->form_validation->set_rules('phone', ('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', ('email'), 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('payment_method', ('payment_method'), 'trim|required|xss_clean');
        
        $reference = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        
        if ($this->form_validation->run() == false) {
            $data['setting'] = $this->setting;
            $data['amount'] = $this->amount;
            $data['name'] = $online_data->firstname." ".$online_data->lastname;
            $data['email'] = $online_data->email;
            $data['phone'] = $online_data->mobileno;
            $data['currency_name'] = $this->customlib->get_currencyShortName();
            $data['api_error'] = array();
            
            // Payment options are hardcoded
            $data['payment_options'] = array();
            
            // Preserve form data for form validation errors
            $data['form_data'] = array(
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'payment_method' => $this->input->post('payment_method'),
                'card_number' => $this->input->post('card_number'),
                'card_expiry' => $this->input->post('card_expiry'),
                'card_cvv' => $this->input->post('card_cvv'),
                'momo_phone' => $this->input->post('momo_phone'),
                'mobile_money_phone' => $this->input->post('mobile_money_phone')
            );
            
            $this->load->view('onlineadmission/kowri/index', $data);
        } else {
            $total_amount = $this->customlib->getGatewayProcessingFees($this->amount) + $this->amount;
            
            $selected_payment_method = $this->input->post('payment_method');
            $selected_payment_provider = $this->input->post('selected_payment_provider');
            
            // Collect additional data based on payment method
            $additional_data = array();
            if ($selected_payment_method === 'CARD') {
                $additional_data = array(
                    'card_number' => $this->input->post('card_number'),
                    'card_expiry' => $this->input->post('card_expiry'),
                    'card_cvv' => $this->input->post('card_cvv')
                );
            } elseif ($selected_payment_method === 'MOMO') {
                $additional_data = array(
                    'momo_phone' => $this->input->post('momo_phone')
                );
            } elseif (in_array($selected_payment_provider, ['AIRTELTIGO_MONEY', 'VODAFONE_CASH', 'MTN_MONEY'])) {
                $additional_data = array(
                    'mobile_money_phone' => $this->input->post('mobile_money_phone')
                );
            }
            
            $payment_data = array(
                'amount' => $total_amount,
                'currency' => $this->customlib->get_currencyShortName(),
                'description' => 'Online Admission Form Fees - ' . $online_data->firstname . ' ' . $online_data->lastname,
                'customer_email' => $this->input->post('email'),
                'customer_name' => $online_data->firstname . ' ' . $online_data->lastname,
                'customer_phone' => $this->input->post('phone'),
                'payment_method' => $selected_payment_method,
                'payment_provider' => $selected_payment_provider,
                'additional_data' => $additional_data,
                'return_url' => base_url('onlineadmission/kowri/success'),
                'cancel_url' => base_url('onlineadmission/kowri/cancel'),
                'webhook_url' => base_url('onlineadmission/kowri/webhook')
            );

            // Check payment method and route accordingly
            if ($selected_payment_method === 'CARD' || $selected_payment_provider === 'CARD') {
                $response = $this->kowri_payment->processPayNowPayment($payment_data);
            } elseif ($selected_payment_method === 'MOMO' || $selected_payment_provider === 'MOMO') {
                $response = $this->kowri_payment->processPayNowMOMO($payment_data);
            } else {
                $response = $this->kowri_payment->processOnlineAdmissionPayment($payment_data);
            }
            
            if ($response['status']) {
                // Store payment data in session
                $this->session->set_userdata('kowri_payment_data', array(
                    'transaction_id' => $response['transaction_id'],
                    'amount' => $total_amount,
                    'reference' => $reference
                ));
                
                // Redirect to Kowri payment page
                redirect($response['payment_url']);
            } else {
                $data['setting'] = $this->setting;
                $data['amount'] = $this->amount;
                $data['name'] = $online_data->firstname." ".$online_data->lastname;
                $data['email'] = $online_data->email;
                $data['phone'] = $online_data->mobileno;
                $data['currency_name'] = $this->customlib->get_currencyShortName();
                $data['api_error'] = array('error' => $response['error']);
                
                // Payment options are hardcoded
                $data['payment_options'] = array();
                
                $this->load->view('onlineadmission/kowri/index', $data);
            }
        }
    }

    public function success() {
        $payment_data = $this->session->userdata('kowri_payment_data');
        
        if (!$payment_data) {
            redirect(base_url('onlineadmission/checkout/paymentfailed'));
        }

        $transaction_id = $this->input->get('transaction_id');
        
        // Verify payment with Kowri
        $verification = $this->kowri_payment->verifyPayment($transaction_id);
        
        if ($verification['status'] && $verification['data']['status'] == 'success') {
            $reference = $this->session->userdata('reference');
            $online_data = $this->onlinestudent_model->getAdmissionData($reference);
            
            // Process the payment
            $this->processPaymentSuccess($verification['data'], $online_data);
            
            // Clear session data
            $this->session->unset_userdata('kowri_payment_data');
            
            redirect(base_url("onlineadmission/checkout/successinvoice//".$online_data->reference_no));
        } else {
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

    public function cancel() {
        $reference = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
    }

    public function webhook() {
        $input = file_get_contents('php://input');
        $callback_data = json_decode($input, true);
        
        if ($callback_data) {
            $result = $this->kowri_payment->processCallback($callback_data);
            
            if ($result['status']) {
                // Process successful payment
                $this->processWebhookPayment($result);
                http_response_code(200);
                echo 'OK';
            } else {
                http_response_code(400);
                echo 'Error: ' . $result['error'];
            }
        } else {
            http_response_code(400);
            echo 'Invalid data';
        }
    }

    private function processPaymentSuccess($payment_data, $online_data) {
        $gateway_response = array();
        $gateway_response['transactionid'] = $payment_data['transaction_id'];
        $gateway_response['amount'] = $this->amount;
        $gateway_response['processing_charge_type'] = $this->pay_method->charge_type;
        $gateway_response['processing_charge_value'] = $this->customlib->getGatewayProcessingFees($this->amount);
        $gateway_response['note'] = $this->lang->line('online_fees_deposit_through_kowri_txn_id') . ' ' . $payment_data['transaction_id'];
        $gateway_response['date'] = date("Y-m-d H:i:s");
        
        $return_detail = $this->onlinestudent_model->paymentSuccess($gateway_response);
        
        $sender_details = array(
            'firstname' => $online_data->firstname, 
            'lastname' => $online_data->lastname, 
            'email' => $online_data->email,
            'date' => date("Y-m-d H:i:s"),
            'reference_no' => $online_data->reference_no,
            'mobileno' => $online_data->mobileno,
            'paid_amount' => $this->amount,
            'guardian_email' => $online_data->guardian_email,
            'guardian_phone' => $online_data->guardian_phone
        );
        
        $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
    }

    private function processWebhookPayment($payment_data) {
        // Process webhook payment data
        // This method can be used for additional processing if needed
    }
}
