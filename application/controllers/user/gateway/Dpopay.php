<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dpopay extends Studentgateway_Controller {

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
        $this->load->view('user/gateway/dpopay/index', $data);
    }	 
	
    public function dpo_pay() {
        $this->form_validation->set_rules('creditcardnumber', ('Credit Card Number'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('creditcardexpiry', ('Credit Card Expiry'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('creditcardcvv', ('Credit Card CVV'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('cardholdername', ('Card Holder Name'), 'trim|required|xss_clean');	
		
        $params = $this->session->userdata('params');
        if ($this->form_validation->run() == false) {
            $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $data['api_error'] = $data['api_error'] = array();	
			
            $this->load->view('user/gateway/dpopay/index', $data);
        } else {
            $amount =number_format((float)($params['fine_amount_balance']+$params['total'] - $params['applied_fee_discount']+ $params['gateway_processing_charge']), 2, '.', '');
            $instadetails = $this->paymentsetting_model->getActiveMethod();
            
            $CompanyToken = $instadetails->api_secret_key;
            $insta_authtoken = $instadetails->api_publishable_key;
            $data = array();
            $data['name'] = $params['name'];
            $PaymentCurrency=$params['invoice']->currency_name;
            //$amount =number_format((float)($params['fine_amount_balance']+$params['total'] - $params['applied_fee_discount']+ $params['gateway_processing_charge']), 2, '.', '');
            $amount=1;
            $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://secure.3gdirectpay.com/API/v6/');   
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "<?xml version='1.0' encoding='utf-8'?><API3G><CompanyToken>".$CompanyToken."</CompanyToken><Request>createToken</Request><Transaction><PaymentAmount>".$amount."</PaymentAmount><PaymentCurrency>".$PaymentCurrency."</PaymentCurrency><CompanyRef>gff</CompanyRef><RedirectURL>".base_url('user/gateway/success')."</RedirectURL><BackURL>".base_url('user/gateway/dpo_pay')."</BackURL><CompanyRefUnique>0</CompanyRefUnique><PTL>5</PTL></Transaction><Services><Service><ServiceType>86280</ServiceType><ServiceDescription>Flight from Nairobi to Diani</ServiceDescription><ServiceDate>".date('Y-m-d')."</ServiceDate></Service></Services></API3G>");
			$headers = array();
			$headers[] = 'Content-Type: application/xml';
			$headers[] = 'Accept: application/xml';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			$xml = simplexml_load_string($result);
			$array = json_decode(json_encode($xml), true);
			$TransToken="";
            curl_close($ch);
          
            if(isset($array['Result']) && $array['Result']=='000'){
              $TransToken=$array['TransToken'];  
              $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://secure.3gdirectpay.com/API/v6/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '<?xml version="1.0" encoding="utf-8"?><API3G><CompanyToken>'.$CompanyToken.'</CompanyToken><Request>chargeTokenCreditCard</Request><TransactionToken>'.$TransToken.'</TransactionToken><CreditCardNumber>'.$_POST['creditcardnumber'].'</CreditCardNumber><CreditCardExpiry>'.$_POST['creditcardexpiry'].'</CreditCardExpiry><CreditCardCVV>'.$_POST['creditcardcvv'].'</CreditCardCVV><CardHolderName>'.$_POST['cardholdername'].'</CardHolderName><ChargeType></ChargeType></API3G>');
            $headers = array();
            $headers[] = 'Content-Type: application/xml';
            $headers[] = 'Accept: application/xml';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
             
            $result1 = curl_exec($ch);       
            
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            $xml = simplexml_load_string($result1);         
             
            $json = json_decode(json_encode($xml), true);
            
            curl_close($ch);
            if (isset($json['ResultExplanation']) && $json['ResultExplanation'] == 'Transaction Charged') {
                $this->success($array['TransRef']);
            }else{
              $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $data['api_error'] = $data['api_error'] = $json;  
            
            $this->load->view('user/gateway/dpopay/index', $data);  
            }
          }else{
            
           
              $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $data['api_error'] = $data['api_error'] = $array;  
            
            $this->load->view('user/gateway/dpopay/index', $data); 
           
          }
			
			
			
			
        }
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
                        'description'     => "Online fees deposit through dpo txn id " . $TransRef,
                        'received_by'     => '',
                        'payment_mode'    => 'DPO',
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

                    if ($response) {
                          redirect(base_url("user/gateway/payment/successinvoice"));                     
                    } else {
                      redirect(base_url('user/gateway/payment/paymentfailed'));
                    }

                // } else {
                    // redirect(base_url('user/gateway/payment/paymentfailed'));
                // }
    }

}