<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dpopay extends OnlineAdmission_Controller
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
        $this->load->view('onlineadmission/dpopay/index', $data);
    }

    public function pay()
    {
        $this->form_validation->set_rules('creditcardnumber', ('Credit Card Number'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('creditcardexpiry', ('Credit Card Expiry'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('creditcardcvv', ('Credit Card CVV'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('cardholdername', ('Card Holder Name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['setting'] = $this->setting;
            $data['amount']  = $this->amount;
            $data['error']   = array('ResultExplanation' => validation_errors());
            $this->load->view('onlineadmission/dpopay/index', $data);
            return;
        }

        $reference   = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);

        $total_amount = number_format((float) ($this->customlib->getGatewayProcessingFees($this->amount) + $this->amount), 2, '.', '');
        $instadetails = $this->pay_method;

        $CompanyToken = $instadetails->api_secret_key;
        $PaymentCurrency = isset($this->setting->currency) ? $this->setting->currency : 'USD';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://secure.3gdirectpay.com/API/v6/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "<?xml version='1.0' encoding='utf-8'?><API3G><CompanyToken>" . $CompanyToken . "</CompanyToken><Request>createToken</Request><Transaction><PaymentAmount>" . $total_amount . "</PaymentAmount><PaymentCurrency>" . $PaymentCurrency . "</PaymentCurrency><CompanyRef>online_admission</CompanyRef><RedirectURL>" . base_url('onlineadmission/checkout/successinvoice/' . $online_data->reference_no) . "</RedirectURL><BackURL>" . base_url('onlineadmission/checkout/paymentfailed/' . $online_data->reference_no) . "</BackURL><CompanyRefUnique>0</CompanyRefUnique><PTL>5</PTL></Transaction><Services><Service><ServiceType>86280</ServiceType><ServiceDescription>Online Admission</ServiceDescription><ServiceDate>" . date('Y-m-d') . "</ServiceDate></Service></Services></API3G>");
        $headers = array();
        $headers[] = 'Content-Type: application/xml';
        $headers[] = 'Accept: application/xml';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $data['setting'] = $this->setting;
            $data['amount']  = $this->amount;
            $data['error']   = array('ResultExplanation' => curl_error($ch));
            curl_close($ch);
            $this->load->view('onlineadmission/dpopay/index', $data);
            return;
        }
        // Safely parse XML; if not XML, surface raw response to avoid warnings
        $xml = @simplexml_load_string($result);
        if ($xml === false) {
            $data['setting'] = $this->setting;
            $data['amount']  = $this->amount;
            $data['error']   = array('ResultExplanation' => 'Invalid gateway response', 'message' => substr($result, 0, 500));
            curl_close($ch);
            $this->load->view('onlineadmission/dpopay/index', $data);
            return;
        }
        $array = json_decode(json_encode($xml), true);
        curl_close($ch);

        if (isset($array['Result']) && $array['Result'] == '000') {
            $TransToken = $array['TransToken'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://secure.3gdirectpay.com/API/v6/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '<?xml version="1.0" encoding="utf-8"?><API3G><CompanyToken>' . $CompanyToken . '</CompanyToken><Request>chargeTokenCreditCard</Request><TransactionToken>' . $TransToken . '</TransactionToken><CreditCardNumber>' . $this->input->post('creditcardnumber') . '</CreditCardNumber><CreditCardExpiry>' . $this->input->post('creditcardexpiry') . '</CreditCardExpiry><CreditCardCVV>' . $this->input->post('creditcardcvv') . '</CreditCardCVV><CardHolderName>' . $this->input->post('cardholdername') . '</CardHolderName><ChargeType></ChargeType></API3G>');
            $headers = array();
            $headers[] = 'Content-Type: application/xml';
            $headers[] = 'Accept: application/xml';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result1 = curl_exec($ch);
            if (curl_errno($ch)) {
                $data['setting'] = $this->setting;
                $data['amount']  = $this->amount;
                $data['error']   = array('ResultExplanation' => curl_error($ch));
                curl_close($ch);
                $this->load->view('onlineadmission/dpopay/index', $data);
                return;
            }
            $xml  = @simplexml_load_string($result1);
            if ($xml === false) {
                $data['setting'] = $this->setting;
                $data['amount']  = $this->amount;
                $data['error']   = array('ResultExplanation' => 'Invalid gateway response', 'message' => substr($result1, 0, 500));
                curl_close($ch);
                $this->load->view('onlineadmission/dpopay/index', $data);
                return;
            }
            $json = json_decode(json_encode($xml), true);

            curl_close($ch);
            if (isset($json['ResultExplanation']) && $json['ResultExplanation'] == 'Transaction Charged') {
                $this->success($array['TransRef'], $online_data, $total_amount);
                return;
            } else {
                $data['setting'] = $this->setting;
                $data['amount']  = $this->amount;
                $data['error']   = $json;
                $this->load->view('onlineadmission/dpopay/index', $data);
                return;
            }
        } else {
            $data['setting'] = $this->setting;
            $data['amount']  = $this->amount;
            $data['error']   = $array;
            $this->load->view('onlineadmission/dpopay/index', $data);
            return;
        }
    }

    private function success($TransRef, $online_data, $paid_amount)
    {
        $reference   = $this->session->userdata('reference');
        $apply_date  = date("Y-m-d H:i:s");
        $date        = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));
		
		$total_amount   = $this->customlib->getGatewayProcessingFees($this->amount)+$this->amount;
		
        $gateway_response = array(
            'online_admission_id'      => $reference,
            'paid_amount'              => $this->amount,
            'transaction_id'           => $TransRef,
            'payment_mode'             => 'dpo',
            'payment_type'             => 'online',
            'processing_charge_type'   => $this->pay_method->charge_type,
            'processing_charge_value'  => $this->customlib->getGatewayProcessingFees($this->amount),
            'note'                     => "Online admission fees deposit through dpo txn id " . $TransRef,
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
            'paid_amount'    => $total_amount,
            'guardian_email' => $online_data->guardian_email,
            'guardian_phone' => $online_data->guardian_phone
        );
        $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
        redirect(base_url("onlineadmission/checkout/successinvoice/" . $online_data->reference_no));
    }
}

