<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ihela extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('gateway_ins_model','paymentsetting_model'));
         $this->api_config = $this->paymentsetting_model->getActiveMethod();
    }

    public function index()
    {
       
       
        $ihela_processing_payment    = $this->gateway_ins_model->get_gateway_ins_by_name('ihela');
      
        if (!empty($ihela_processing_payment)) {
           foreach($ihela_processing_payment as $keyihela_processing_payment=>$valueihela_processing_payment){
            $bulk_fees      = array();
            $ihelaparameter_details=json_decode($valueihela_processing_payment['parameter_details'],true);
            $bill_status_check['bill_code']=$ihelaparameter_details['response_data']['code'];
            $bill_status_check['merchant_reference']='752000';
            $bill_status_check['pin_code']='1234';
            if ($valueihela_processing_payment['module_type'] == 'fees') {
                $parameter_data=$this->gateway_ins_model->get($valueihela_processing_payment['unique_id'],'ihela');
                 
                
               
                if((array_key_exists($ihelaparameter_details['success'], $ihelaparameter_details) && $ihelaparameter_details['success']==1)){
                                     
                    
                   $ihela_responce=$this->bill_status_check(($bill_status_check));
                    
                   if($ihela_responce){
foreach ($parameter_data as $fee_key => $fee_value) {
                
                $amount_detail=json_decode($fee_value['amount_detail'],true);
                $amount_detail['description']="Online fees deposit through ihela TXN ID:".$bill_status_check['bill_code'];
                $insert_fee_data = array(
                    'fee_category'=>$fee_value['fee_category'],
                    'student_transport_fee_id'=>$fee_value['student_transport_fee_id'],
                    'student_fees_master_id' => $fee_value['student_fees_master_id'],
                    'fee_groups_feetype_id'  => $fee_value['fee_groups_feetype_id'],
                    'amount_detail'          => $amount_detail,
                );                 
               $bulk_fees[]=$insert_fee_data;
                //========
            } 
           $insert_id=true;
                //$insert_id = $this->gateway_ins_model->fee_deposit_bulk($bulk_fees);
                if ($insert_id) {
                    //$response = "success";
                   // $this->gateway_ins_model->deleteBygateway_ins_id($valueihela_processing_payment['id']);
                } else {
                    //$response = "quiry_failed";
                }

                   }
                

                }
                    
            }
       // Fees End 
            //OS START
            if ($valueihela_processing_payment['module_type'] == 'online_course') {
                $online_course                   = $this->gateway_ins_model->get_processing_payment($valueihela_processing_payment['id']);
               
                $ihela_responce=$this->bill_status_check(($bill_status_check));
                
                    
                if($ihela_responce){
                $online_course['transaction_id'] = $bill_status_check['bill_code'];
                $online_course['note']           = "Online course fees Ihela Txn ID: " . $bill_status_check['bill_code'];
                unset($online_course['id']);
                unset($online_course['gateway_ins_id']);
                //$this->gateway_ins_model->add_course_payment($online_course,$valueihela_processing_payment['id']);
                $response = "success";
                $this->gateway_ins_model->deleteprocessingpaymentByid($valueihela_processing_payment['id']);
            }
            }
           }
         
           
        }


    }

    public function bill_status_check($postfield){
        return true;
        $api_secret_key = $this->api_config->api_secret_key;
            $api_publishable_key = $this->api_config->api_publishable_key;
           
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.ihela.bi/oAuth2/token/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('grant_type' => 'client_credentials'),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic '.base64_encode($api_publishable_key.':'.$api_secret_key)
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$token_json = json_decode($response, true);

           

$access_token="";
if(isset($token_json['access_token'])){
$access_token=$token_json['access_token']; 

 $curl = curl_init();

curl_setopt_array($curl, array(
   CURLOPT_URL => 'https://api.ihela.bi/api/v2/payments/bill-check/',
 CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

   CURLOPT_CUSTOMREQUEST => 'POST',
   CURLOPT_POSTFIELDS =>json_encode($postfield),
   CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Authorization: Bearer '.$access_token,
   ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
        $json=json_decode($response, true);
            
           

}
       
    }
}
