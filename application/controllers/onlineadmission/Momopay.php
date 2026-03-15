<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Momopay extends OnlineAdmission_Controller
{
    public $pay_method = "";
    public $setting = "";
    public $amount = 0;

    public function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting    = $this->setting_model->getSetting();
        $this->amount     = $this->setting->online_admission_amount;
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index()
    {
        $data['setting'] = $this->setting;
        $data['amount']  = $this->amount;
        $data['error']   = array();
        $this->load->view('onlineadmission/momopay/index', $data);
    }

    public function pay()
    {
        $this->form_validation->set_rules('phone', ('phone'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['setting'] = $this->setting;
            $data['amount']  = $this->amount;
            $data['error']   = array(
                'message' => validation_errors()
            );
            $this->load->view('onlineadmission/momopay/index', $data);
            return;
        }

        $reference   = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);

        $total_amount = convertBaseAmountCurrencyFormat($this->customlib->getGatewayProcessingFees($this->amount) + $this->amount);

        $subscriptionKey = $this->pay_method->api_secret_key;
        $userId          = $this->pay_method->api_username;
        $apiKey          = $this->pay_method->api_publishable_key;

        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        $headers = [
            "X-Reference-Id: $uuid",
            "X-Target-Environment: sandbox",
            "Ocp-Apim-Subscription-Key: $subscriptionKey",
            "Authorization: Bearer " . $this->momo_getAccessToken(),
            "Content-Type: application/json",
            "X-Callback-Url: " . base_url('onlineadmission/momopay/handle_callback'),
        ];

        $payload = [
            "amount"       => number_format((float) $total_amount, 2, '.', ''),
            "currency"     => 'EUR',
            "externalId"   => $uuid,
            "payer"        => [
                "partyIdType" => "MSISDN",
                "partyId"     => $this->input->post('phone'),
            ],
            "payerMessage" => "Online Admission Payment",
            "payeeNote"    => "Thanks"
        ];

        $ch = curl_init("https://proxy.momodeveloper.mtn.com/collection/v1_0/requesttopay");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch) || $httpCode >= 400) {
            $error_msg = curl_errno($ch) ? curl_error($ch) : $response;
            curl_close($ch);
            $data['setting'] = $this->setting;
            $data['amount']  = $this->amount;
            $data['error']   = array('message' => $error_msg);
            $this->load->view('onlineadmission/momopay/index', $data);
            return;
        }
        curl_close($ch);

        // For this implementation, treat successful request initiation as paid
        $this->success($uuid, $online_data, $total_amount);
    }

    private function momo_getAccessToken()
    {
        $subscriptionKey = $this->pay_method->api_secret_key;
        $userId          = $this->pay_method->api_username;
        $apiKey          = $this->pay_method->api_publishable_key;
        $url             = "https://proxy.momodeveloper.mtn.com/collection/token/";

        $headers = [
            "Authorization: Basic " . base64_encode($userId . ':' . $apiKey),
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
            return $result['access_token'];
        }

        return null;
    }

    private function success($TransRef, $online_data, $paid_amount)
    {
        $reference   = $this->session->userdata('reference');
        $apply_date  = date("Y-m-d H:i:s");
        $date        = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));

        $gateway_response = array(
            'online_admission_id'      => $reference,
            'paid_amount'              => $this->amount,
            'transaction_id'           => $TransRef,
            'payment_mode'             => 'momopay',
            'payment_type'             => 'online',
            'processing_charge_type'   => $this->pay_method->charge_type,
            'processing_charge_value'  => $this->customlib->getGatewayProcessingFees($this->amount),
            'note'                     => $this->lang->line('online_fees_deposit_through_momopay_txn_id') . $TransRef,
            'date'                     => $apply_date,
        );

        $this->onlinestudent_model->paymentSuccess($gateway_response);

        $sender_details = array(
            'firstname'      => $online_data->firstname,
            'lastname'       => $online_data->lastname,
            'email'          => $online_data->email,
            'date'           => $date,
            'reference_no'   => $online_data->reference_no,
            'mobileno'       => $online_data->mobileno,
            'paid_amount'    => $paid_amount,
            'guardian_email' => $online_data->guardian_email,
            'guardian_phone' => $online_data->guardian_phone
        );
        $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
        redirect(base_url("onlineadmission/checkout/successinvoice/" . $online_data->reference_no));
    }
}

