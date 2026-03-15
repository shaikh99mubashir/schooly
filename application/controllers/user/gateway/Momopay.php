<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Momopay extends Studentgateway_Controller {

    public $api_config = "";

    public function __construct() {
        parent::__construct();

        $api_config = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->get();
        $this->load->library('mailsmsconf');
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
    }
  
    public function index() {
 
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/momopay/index', $data);
    }    
    
    public function pay() {
     
        $this->form_validation->set_rules('phone', ('phone'), 'trim|required|xss_clean');   
        
        $params = $this->session->userdata('params');
        $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $data['api_error'] = $data['api_error'] = array();  
        if ($this->form_validation->run() == false) {
           
            
            $this->load->view('user/gateway/momopay/index', $data);
        } else {

            $apidetails = $this->paymentsetting_model->getActiveMethod();
           
          //  $insta_apikey = $instadetails->api_secret_key;
           // $insta_authtoken = $instadetails->api_publishable_key;
            $data = array();
            $data['name'] = $params['name'];
            $subscriptionKey=$apidetails->api_secret_key;
         $userId=$apidetails->api_username;
          $apiKey=$apidetails->api_publishable_key;
            $amount =number_format((float)($params['fine_amount_balance']+$params['total'] - $params['applied_fee_discount']+ $params['gateway_processing_charge']), 2, '.', '');

            $url = "https://proxy.momodeveloper.mtn.com/collection/v1_0/requesttopay";
    $uuid = sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $headers = [
        "X-Reference-Id: $userId",
        "X-Target-Environment: sandbox",
        "Ocp-Apim-Subscription-Key: $subscriptionKey",
        "Authorization: Bearer ".$this->momo_getAccessToken(),
        "Content-Type: application/json",
        "X-Callback-Url: ".base_url('user/gateway/momopay/handle_callback'),
    ];

    $data = [
        "amount" => $amount,
        "currency" => 'EUR',
        "externalId" =>$uuid,
        "payer" => [
            "partyIdType" => "MSISDN",
            "partyId" => $_POST['phone'],
        ],
        "payerMessage" => "Test Payment",
        "payeeNote" => "Thanks"
        
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);


if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo "HTTP Code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
    echo "Response: \n$response";
}

    curl_close($ch);
 
   // return $uuid; // Used to track payment status
   $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            
            // Parse the response to extract error information
            $response_data = json_decode($response, true);
            if ($response_data) {
                $data['api_error'] = $response_data;
            } else {
                $data['api_error'] = array(
                    'message' => 'Payment request failed',
                    'details' => $response
                );
            }
            
            $this->load->view('user/gateway/momopay/index', $data);
        }

    }

    public function handle_callback(){
        $response = json_decode(file_get_contents('php://input'), true);

        log_message('error', 'MomoPay Callback: ' . print_r($response, true));

        if (isset($response['externalId'])) {
            $status = $this->_check_payment_status($response['externalId']);

            if ($status === 'SUCCESSFUL') {
                $this->success($response['externalId']);
            } else {
                // You might want to create a proper failure view
                log_message('error', 'MomoPay Payment Failed or Pending: ' . $status);
                redirect(base_url('user/gateway/payment/paymentfailed'));
            }
        } else {
            log_message('error', 'MomoPay Callback: Invalid response, missing externalId');
        }
    }

    private function _check_payment_status($reference_id) {
        $url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/" . $reference_id;

        $headers = [
            "Ocp-Apim-Subscription-Key: " . $this->api_config->api_secret_key,
            "Authorization: Bearer " . $this->momo_getAccessToken(),
            "X-Target-Environment: sandbox",
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        
        log_message('error', 'MomoPay Status Check Response: ' . print_r($result, true));

        return $result['status'] ?? null;
    }

    function momo_getAccessToken() {
        $cached_token = $this->session->userdata('momo_access_token');
        if ($cached_token && (time() < $cached_token['expires_at'])) {
            return $cached_token['access_token'];
        }

        $this->api_config = $this->paymentsetting_model->getActiveMethod();
        $subscriptionKey=$this->api_config->api_secret_key;
        $userId=$this->api_config->api_username;
        $apiKey=$this->api_config->api_publishable_key;
        $url = "https://proxy.momodeveloper.mtn.com/collection/token/";

    $headers = [       
        "Authorization: Basic " . base64_encode($userId.':'.$apiKey),
        "Ocp-Apim-Subscription-Key: $subscriptionKey",
        'Cache-Control: no-cache',
        'Content-Length: 0',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
   
    if (isset($result['access_token'])) {
        $token_data = [
            'access_token' => $result['access_token'],
            'expires_at' => time() + $result['expires_in'] - 300 // 5 minutes buffer
        ];
        $this->session->set_userdata('momo_access_token', $token_data);
        return $result['access_token'];
    }

    return null;
}

    public function success($TransRef) {
        
        // if ($_GET['payment_status'] == 'Credit') {
                   
                    $bulk_fees=array();
                    $params     = $this->session->userdata('params');
                 
                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                    
                     $json_array = array(
                        'amount'          =>  $fee_value['amount_balance'],
                        'date'            => date('Y-m-d'),
                        'amount_discount' => $fee_value['applied_fee_discount'],
                        'processing_charge_type'=>$params['processing_charge_type'],
                        'gateway_processing_charge'=>$params['gateway_processing_charge'],
                        'amount_fine'     => $fee_value['fine_balance'],
                        'description'     => $this->lang->line('online_fees_deposit_through_momopay_txn_id') . $TransRef,
                        'received_by'     => '',
                        'payment_mode'    => 'Momopay',
                    );

                    $insert_fee_data = array(
                        'fee_category'=>$fee_value['fee_category'],
                        'student_transport_fee_id'=>$fee_value['student_transport_fee_id'],
                        'student_fees_master_id' => $fee_value['student_fees_master_id'],
                        'fee_groups_feetype_id'  => $fee_value['fee_groups_feetype_id'],
                        'amount_detail'          => $json_array,
                    );                 
                   $bulk_fees[]=$insert_fee_data;
                    //========
                    }
                    $send_to     = $params['guardian_phone'];
                    $response = $this->studentfeemaster_model->fee_deposit_bulk($bulk_fees, $params['fee_discount_group']);
                   //================================
                     //========================
                $student_id            = $this->customlib->getStudentSessionUserID();
                $student_current_class = $this->customlib->getStudentCurrentClsSection();
                $student_session_id    = $student_current_class->student_session_id;
                $fee_group_name        = [];
                $type                  = [];
                $code                  = [];

                $amount          = [];
                $fine_type       = [];
                $due_date        = [];
                $fine_percentage = [];
                $fine_amount     = [];
               
                $invoice     = []; 

                $student = $this->student_model->getStudentByClassSectionID($student_current_class->class_id, $student_current_class->section_id, $student_id);
				
						foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {                     

							if($fee_value['amount_balance'] > 0){
								$invoice_mail     		= $response[$fee_key]['invoice_id'];   
							
								$fee_category           = $fee_value['fee_category'];               
							
								$student_transport_fee_mail 		= $fee_value['student_transport_fee_id'];				 
							
								$fee_groups_feetype_mail   		= $fee_value['fee_groups_feetype_id'];
								$student_fees_master_mail   	= $fee_value['student_fees_master_id'];
								$fee_session_group_mail  		= $fee_value['fee_session_group_id'];				 
										
								$token = encode_receipt_url($invoice_mail, $fee_category, $student_transport_fee_mail, $fee_groups_feetype_mail, $student_fees_master_mail, $fee_session_group_mail, 'student', $student_id);
							
								$fee_receipt_pdf_url[] = base_url() . "download-receipt/" . $token;
							}
												
						}
						
                if ($response && is_array($response)) {
                    foreach ($response as $response_key => $response_value) {
                        $fee_category = $response_value['fee_category'];
                           $invoice[]   = array(
                            'invoice_id'     => $response_value['invoice_id'],
                            'sub_invoice_id' => $response_value['sub_invoice_id'],
                            'fee_category' => $fee_category,
                        );


                        if ($response_value['student_transport_fee_id'] != 0 && $response_value['fee_category'] == "transport") {

                            $data['student_fees_master_id']   = null;
                            $data['fee_groups_feetype_id']    = null;
                            $data['student_transport_fee_id'] = $response_value['student_transport_fee_id'];

                            $mailsms_array     = $this->studenttransportfee_model->getTransportFeeMasterByStudentTransportID($response_value['student_transport_fee_id']);
                            $fee_group_name[]  = $this->lang->line("transport_fees");
                            $type[]            = $mailsms_array->month;
                            $code[]            = "-";
                            $fine_type[]       = $mailsms_array->fine_type;
                            $due_date[]        = $mailsms_array->due_date;
                            $fine_percentage[] = $mailsms_array->fine_percentage;
                            $fine_amount[]     = $mailsms_array->fine_amount;
                            $amount[]          = $mailsms_array->amount;

                        } else {

                            $mailsms_array = $this->feegrouptype_model->getFeeGroupByIDAndStudentSessionID($response_value['fee_groups_feetype_id'], $student_session_id);

                            $fee_group_name[]  = $mailsms_array->fee_group_name;
                            $type[]            = $mailsms_array->type;
                            $code[]            = $mailsms_array->code;
                            $fine_type[]       = $mailsms_array->fine_type;
                            $due_date[]        = $mailsms_array->due_date;
                            $fine_percentage[] = $mailsms_array->fine_percentage;
                            $fine_amount[]     = $mailsms_array->fine_amount;

                            if ($mailsms_array->is_system) {
                                $amount[] = $mailsms_array->balance_fee_master_amount;
                            } else {
                                $amount[] = $mailsms_array->amount;
                            }

                        }

                    }
                    $obj_mail                     = [];
                    $obj_mail['student_id']  = $student_id;
                    $obj_mail['student_session_id'] = $student_session_id;

                    $obj_mail['invoice']         = $invoice;
                    $obj_mail['contact_no']      = $student['guardian_phone'];
                    $obj_mail['email']           = $student['email'];
                    $obj_mail['parent_app_key']  = $student['parent_app_key'];
                    $obj_mail['amount']         = "(".implode(',', $amount).")";
                    $obj_mail['fine_type']       = "(".implode(',', $fine_type).")";
                    $obj_mail['due_date']        = "(".implode(',', $due_date).")";
                    $obj_mail['fine_percentage'] = "(".implode(',', $fine_percentage).")";
                    $obj_mail['fine_amount']     = "(".implode(',', $fine_amount).")";
                    $obj_mail['fee_group_name']  = "(".implode(',', $fee_group_name).")";
                    $obj_mail['type']            = "(".implode(',', $type).")";
                    $obj_mail['code']            = "(".implode(',', $code).")";
                    $obj_mail['fee_category']    = $fee_category;
					$obj_mail['fee_receipt_url']            = "(" . implode(', ', $fee_receipt_pdf_url) . ")";
                    $obj_mail['send_type']    = 'group';

                    $this->mailsmsconf->mailsms('fee_submission', $obj_mail);

                }

                $data = array();
                $data['params'] = $this->session->userdata('params');
                $data['setting'] = $this->setting;
                $data['api_error'] = array();
                $data['student_data'] = $this->student_model->get($data['params']['student_id']);
                $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
                $data['trans_ref'] = $TransRef;
                $this->load->view('user/gateway/momopay/index', $data);
    }

}