<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kowri extends Studentgateway_Controller {

    public $api_config = "";

    public function __construct() {
        parent::__construct();

        $api_config = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->get();
        $this->load->library('mailsmsconf');
        $this->load->library('kowri_payment');
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
    }
  
    public function index() {
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array'] = $data['params']['student_fees_master_array'];
        
        // Payment options are now hardcoded to only show PayNow Card and PayNow MOMO
        $data['payment_options'] = array();
        
        $this->load->view('user/gateway/kowri/index', $data);
    }    
    
    public function pay() {
        $this->form_validation->set_rules('phone', ('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', ('email'), 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('payment_method', ('payment_method'), 'trim|required|xss_clean');
        
        $params = $this->session->userdata('params');
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array'] = $data['params']['student_fees_master_array'];
        
        if ($this->form_validation->run() == false) {
            // Reload payment options for form validation errors
            $payment_options_response = $this->kowri_payment->getallpaymentoptions();
            if ($payment_options_response['status']) {
                $data['payment_options'] = $payment_options_response['data'];
            } else {
                $data['payment_options'] = array();
                $data['api_error'] = array('error' => $payment_options_response['error']);
            }
            
            // Preserve form data for form validation errors
            $data['form_data'] = array(
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'payment_method' => $this->input->post('payment_method'),
                'momo_phone' => $this->input->post('momo_phone'),
                'mobile_money_phone' => $this->input->post('mobile_money_phone')
            );
            
            $this->load->view('user/gateway/kowri/index', $data);
        } else {
            $apidetails = $this->paymentsetting_model->getActiveMethod();
            
            $amount = number_format((float)($params['fine_amount_balance'] + $params['total'] - $params['applied_fee_discount'] + $params['gateway_processing_charge']), 2, '.', '');
            
            $selected_payment_method = $this->input->post('payment_method');
            $selected_payment_provider = $this->input->post('selected_payment_provider');
            
            $payment_data = array(
                'amount' => $amount,
                'currency' => $this->customlib->get_currencyShortName(),
                'description' => 'Student Fee Payment - ' . $params['name'],
                'customer_email' => $this->input->post('email'),
                'customer_name' => $params['name'],
                'customer_phone' => $this->input->post('phone'),
                'payment_method' => $selected_payment_method,
                'payment_provider' => $selected_payment_provider,
                'return_url' => base_url('user/gateway/kowri/success'),
                'cancel_url' => base_url('user/gateway/kowri/cancel'),
                'webhook_url' => base_url('user/gateway/kowri/webhook')
            );

            // Check payment method and route accordingly
            if ($selected_payment_method === 'CARD' || $selected_payment_provider === 'CARD') {
                $response = $this->kowri_payment->processPayNowPayment($payment_data);
            } elseif ($selected_payment_method === 'MOMO' || $selected_payment_provider === 'MOMO') {
                $response = $this->kowri_payment->processPayNowMOMO($payment_data);
            } else {
                $response = $this->kowri_payment->createPayment($payment_data);
            }
            
            if ($response['status']) {
                // Store payment data in session for verification
                $this->session->set_userdata('kowri_payment_data', array(
                    'transaction_id' => $response['transaction_id'],
                    'reference' => $response['reference'],
                    'amount' => $amount,
                    'student_id' => $params['student_id'],
                    'invoice_num' => isset($response['invoice_num']) ? $response['invoice_num'] : null,
                    'request_id' => isset($response['request_id']) ? $response['request_id'] : null
                ));
                
                // Redirect to Kowri payment page
                redirect($response['payment_url']);
            } else {
                // Log the full error response for debugging
                log_message('error', 'Kowri PayNow API Error: ' . json_encode($response));
                
                $data['api_error'] = array(
                    'error' => $response['error'],
                    'http_code' => isset($response['http_code']) ? $response['http_code'] : 'Unknown',
                    'response' => isset($response['response']) ? $response['response'] : null
                );
                
                // Payment options are hardcoded
                $data['payment_options'] = array();
                
                $this->load->view('user/gateway/kowri/index', $data);
            }
        }
    }

    public function success() {
        $payment_data = $this->session->userdata('kowri_payment_data');
        
        if (!$payment_data) {
            redirect(base_url('user/user/feegrouppayment'));
        }

        // Get parameters from URL (PayNow callback)
        $transaction_id = $this->input->get('transaction_id');
        $status = $this->input->get('status');
        $reference = $this->input->get('reference');
        
        // For PayNow, we'll process the payment based on the callback parameters
        if ($status == 'success' || $status == 'completed') {
            $params = $this->session->userdata('params');
            
            // Create payment data for processing
            $payment_result = array(
                'transaction_id' => $transaction_id ?: $payment_data['transaction_id'],
                'reference' => $reference ?: $payment_data['reference'],
                'amount' => $payment_data['amount'],
                'status' => 'success'
            );
            
            // Process the payment
            $this->processPaymentSuccess($payment_result, $params);
            
            // Clear session data
            $this->session->unset_userdata('kowri_payment_data');
            $this->session->unset_userdata('params');
            
            $this->session->set_flashdata('success', 'Payment completed successfully!');
            redirect(base_url('user/user/feegrouppayment'));
        } else {
            $this->session->set_flashdata('error', 'Payment was not successful');
            redirect(base_url('user/user/feegrouppayment'));
        }
    }

    public function cancel() {
        $this->session->set_flashdata('error', 'Payment was cancelled');
        redirect(base_url('user/user/feegrouppayment'));
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

    private function processPaymentSuccess($payment_data, $params) {
        $this->load->model('feegrouppayment_model');
        
        foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
            $json_array = array(
                'student_fees_master_id' => $fee_value['student_fees_master_id'],
                'fee_groups_feetype_id' => $fee_value['fee_groups_feetype_id'],
                'student_id' => $params['student_id'],
                'amount' => $fee_value['amount'],
                'amount_discount' => $fee_value['amount_discount'],
                'amount_fine' => $fee_value['amount_fine'],
                'amount_paid' => $fee_value['amount_paid'],
                'payment_mode' => 'kowri_paynow',
                'description' => 'Fee payment via Kowri PayNow - Invoice: ' . (isset($payment_data['invoice_num']) ? $payment_data['invoice_num'] : 'N/A'),
                'date' => date('Y-m-d'),
                'transaction_id' => $payment_data['transaction_id'],
                'reference' => isset($payment_data['reference']) ? $payment_data['reference'] : $payment_data['transaction_id'],
                'status' => 'success'
            );

            $this->feegrouppayment_model->add($json_array);
        }

        // Send notification
        $sender_details = array(
            'firstname' => $params['name'],
            'email' => $params['email'],
            'amount' => $payment_data['amount'],
            'transaction_id' => $payment_data['transaction_id']
        );
        
        $this->mailsmsconf->mailsms('fees_submission', $sender_details);
    }

    private function processWebhookPayment($payment_data) {
        // Process webhook payment data
        // This method can be used for additional processing if needed
    }
}
