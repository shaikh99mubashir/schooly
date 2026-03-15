<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'third_party/omnipay/vendor/autoload.php';

class Payment extends Studentgateway_Controller
{

    public $payment_method;
    public $school_name;
    public $school_setting;
    public $setting;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('Paypal_payment');
        $this->load->library('Stripe_payment');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->payment_method     = $this->paymentsetting_model->get();
        $this->school_name        = $this->customlib->getAppName();
        $this->school_setting     = $this->setting_model->get();
        $this->setting            = $this->setting_model->get();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function grouppay()
    {
        $this->session->unset_userdata("params");
        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $row_counter = $this->input->post('row_counter');
            if (!empty($row_counter) && is_array($row_counter)) {
                $fees_master_array    = array();
                $total_amount_balance = 0;

                $total_fine_amount_balance = 0;
                $partialPaymentSetting = '';
                if (isset($this->sch_setting_detail->student_partial_payment)) {
                    $partialPaymentSetting = strtolower(trim((string) $this->sch_setting_detail->student_partial_payment));
                }
                $partialPaymentAllowed = in_array($partialPaymentSetting, array('enabled', '1', 'true', 'yes'), true);
                foreach ($row_counter as $row_key => $row_value) {
                    $fine_amount_balance = 0;
                    $amount_paid         = 0;
                    $amount_fine_paid    = 0;
                    $amount_discount     = 0;
                    $fee_record          = array();

                    $fee_record['fee_category']             = $this->input->post("fee_category_" . $row_value);
                    $fee_record['student_transport_fee_id'] = $this->input->post("trans_fee_id_" . $row_value);

                    $fee_record['fee_groups_feetype_id']  = $this->input->post("fee_groups_feetype_id_" . $row_value);
                    $fee_record['student_fees_master_id'] = $this->input->post("student_fees_master_id_" . $row_value);
                    $fee_record['fee_session_group_id'] = $this->input->post("fee_session_group_id_" . $row_value);

                    if ($fee_record['fee_category'] == "transport") {
                        $result = $this->studentfeemaster_model->studentTRansportDeposit($fee_record['student_transport_fee_id']);

                        $fee_record['fee_group_name'] = $this->lang->line("transport_fees");
                        $fee_record['fee_type_code']  = $result->month;
                        $fee_record['is_system']             = 0;
                        //===========================

                        $amount_detail = json_decode($result->amount_detail);

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount_paid      = $amount_paid + $amount_detail_value->amount;
                                $amount_discount  = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine_paid = $amount_fine_paid + $amount_detail_value->amount_fine;
                            }
                        }

                        $fees_balance = $result->fees - ($amount_paid + $amount_discount);

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $fees_balance > 0) {
                            $fine_amount_balance = is_null($result->fine_percentage) ? $result->fine_amount : percentageAmount($result->fees, $result->fine_percentage);
                        }
                    } elseif ($fee_record['fee_category'] == "fees") {

                        $result                       = $this->studentfeemaster_model->studentDeposit($fee_record);
                        $fee_record['fee_group_name'] = $result->fee_group_name;
                        $fee_record['fee_type_code']  = $result->fee_type_code;
                        //===========================
                        $fee_record['is_system']             = $result->is_system;
                        $amount_detail = json_decode($result->amount_detail);

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount_paid      = $amount_paid + $amount_detail_value->amount;
                                $amount_discount  = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine_paid = $amount_fine_paid + $amount_detail_value->amount_fine;
                            }
                        }

                        $fees_balance = $result->amount - ($amount_paid + $amount_discount);

                        if ($result->is_system) {
                            $fees_balance = $result->student_fees_master_amount - ($amount_paid + $amount_discount);
                        }

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $fees_balance > 0) {
                           
						   // get cumulative fine amount as delay days 
                            if ($result->fine_type == 'cumulative') {
                                $date1 = date_create("$result->due_date");
                                $date2 = date_create(date('Y-m-d'));
                                $diff = date_diff($date1, $date2);
                                $due_days = $diff->format("%a");;

                                $fee_groups_feetype_id = $this->input->post("fee_groups_feetype_id_" . $row_value);

                                if ($this->customlib->get_cumulative_fine_amount($fee_groups_feetype_id, $due_days)) {
                                    $due_fine_amount = $this->customlib->get_cumulative_fine_amount($fee_groups_feetype_id, $due_days);
                                } else {
                                    $due_fine_amount = 0;
                                }
                                $fine_amount_balance  =  $due_fine_amount;
                            } else if ($result->fine_type == 'fix' || $result->fine_type == 'percentage') {
                                $fine_amount_balance   = $result->fine_amount;
                            }
                            // get cumulative fine amount as delay days
                        }
                    }

                    // Use posted fee_amount if available (from editable amount field), otherwise use calculated balance
                    $posted_fee_amount = $this->input->post("fee_amount_" . $row_value);
                    $posted_fine_amount = $this->input->post("fee_groups_feetype_fine_amount_" . $row_value);
                    
                    // Parse posted amounts (remove any currency formatting)
                    $fee_amount_to_use = $fees_balance;
                    if ($partialPaymentAllowed && $posted_fee_amount !== null && $posted_fee_amount !== '') {
                        $posted_fee_amount_clean = preg_replace('/[^0-9.]/', '', $posted_fee_amount);
                        if ($posted_fee_amount_clean === '') {
                            $fee_amount_to_use = 0;
                        } else {
                            $fee_amount_to_use = floatval($posted_fee_amount_clean);
                            // Ensure it doesn't exceed the balance
                            if ($fee_amount_to_use > $fees_balance) {
                                $fee_amount_to_use = $fees_balance;
                            }
                        }
                    } elseif (!$partialPaymentAllowed) {
                        $fee_amount_to_use = $fees_balance;
                    }
                    $fee_record['amount_balance'] = $fee_amount_to_use;
                    
                    $fine_balance_calc = ($fine_amount_balance - $amount_fine_paid);
                    $fine_amount_to_use = $fine_balance_calc;
                    if ($partialPaymentAllowed && $posted_fine_amount !== null && $posted_fine_amount !== '') {
                        $posted_fine_amount_clean = preg_replace('/[^0-9.]/', '', $posted_fine_amount);
                        if ($posted_fine_amount_clean === '') {
                            $fine_amount_to_use = 0;
                        } else {
                            $fine_amount_to_use = floatval($posted_fine_amount_clean);
                            // Ensure it doesn't exceed the fine balance
                            if ($fine_amount_to_use > $fine_balance_calc) {
                                $fine_amount_to_use = $fine_balance_calc;
                            }
                        }
                    } elseif (!$partialPaymentAllowed) {
                        $fine_amount_to_use = $fine_balance_calc;
                    }
                    $fee_record['fine_balance'] = $fine_amount_to_use;
                    
                    // Skip sending this fee if nothing is being paid for it
                    if ($fee_record['amount_balance'] <= 0 && $fee_record['fine_balance'] <= 0) {
                        continue;
                    }
                    
                    $fees_master_array[]          = $fee_record;
                    $total_fine_amount_balance += $fee_record['fine_balance'];
                    $total_amount_balance += $fee_record['amount_balance'];

                    //===========================

                }

                $student_id     = $this->customlib->getStudentSessionUserID();
                $pay_method     = $this->paymentsetting_model->getActiveMethod();

                $student_record = $this->student_model->get($student_id);

                //=================
                // Get posted total_paying amount if user has edited it
                $posted_total_paying = $this->input->post('total_paying');
                $calculated_total = $total_amount_balance + $total_fine_amount_balance;
                
                if ($partialPaymentAllowed) {
                    // Use posted total_paying if available, otherwise use calculated total
                    if (!empty($posted_total_paying) && is_numeric($posted_total_paying)) {
                        $posted_total_clean = preg_replace('/[^0-9.]/', '', $posted_total_paying);
                        $final_total = floatval($posted_total_clean);
                        // Ensure it doesn't exceed calculated total
                        if ($final_total > $calculated_total) {
                            $final_total = $calculated_total;
                        }
                    } else {
                        $final_total = $calculated_total;
                    }
                } else {
                    $final_total = $calculated_total;
                }
                
                $page                = new stdClass();
                $page->symbol        = $this->setting[0]['currency_symbol'];
                $page->currency_name = $this->session->userdata('student')['currency_name'];
                $gateway_processing_charge=0;
                    if($pay_method->charge_type=='percentage'){
                    $gateway_processing_charge=(($final_total * $pay_method->charge_value)/100);
                    }elseif($pay_method->charge_type=='fix'){
                        $gateway_processing_charge=$pay_method->charge_value;
                    }else{
                     $gateway_processing_charge=0;   
                    }   
                $params = array( //payment session
                    'key'                       => $pay_method->api_secret_key,
                    'api_publishable_key'       => $pay_method->api_publishable_key,
                    'invoice'                   => $page,
                    'total'                     => $total_amount_balance,
                    'applied_fee_discount'      => 0,
                    'student_fees_discount_id'      => null,
                    'fine_amount_balance'       => ($total_fine_amount_balance),
                    'student_session_id'        => $student_record['student_session_id'],
                    'name'                      => $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                    'email'                     => $student_record['email'],
                    'guardian_phone'            => $student_record['guardian_phone'],
                    'mobileno'                  => $student_record['mobileno'],
                    'guardian_email'            => $student_record['guardian_email'],
                    'address'                   => $student_record['permanent_address'],
                    'student_fees_master_array' => $fees_master_array,
                    'student_id'                => $student_id,
                     'fee_discount_group'        => array(),//new line added
                    'processing_charge_type'=>$pay_method->charge_type,
                    'gateway_processing_charge'=>$gateway_processing_charge
                );
                //=================

                if ($pay_method->payment_type == "payu") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payu_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/payu"));
                    }
                } else if ($pay_method->payment_type == "instamojo") {
                    //==========Start Instamojo==========
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->salt == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('instamojo_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/instamojo"));
                    }
                    //==========End Instamojo==========
                } else if ($pay_method->payment_type == "paypal") {
                    //==========Start Paypal==========
                    if ($pay_method->api_username == "" || $pay_method->api_password == "" || $pay_method->api_signature == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paypal_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/paypal"));
                    }
                    //==========End Paypal==========
                } else if ($pay_method->payment_type == "stripe") {
                    ///=====================
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('stripe_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/stripe"));
                    }
                    //=======================
                } else if ($pay_method->payment_type == "paystack") {
                    ///=====================
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paystack_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/paystack"));
                    }
                    //=======================
                } else if ($pay_method->payment_type == "razorpay") {
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('razorpay_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/razorpay"));
                    }
                } else if ($pay_method->payment_type == "paytm") {
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->paytm_website == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paytm_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/paytm"));
                    }
                } else if ($pay_method->payment_type == "midtrans") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('midtrans_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/midtrans"));
                    }
                } else if ($pay_method->payment_type == "pesapal") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('pesapal_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/pesapal"));
                    }
                } else if ($pay_method->payment_type == "flutterwave") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('flutterwave_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/flutterwave"));
                    }
                } else if ($pay_method->payment_type == "ipayafrica") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ipay_africa_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/ipayafrica"));
                    }
                } else if ($pay_method->payment_type == "jazzcash") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('jazzcash_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/jazzcash"));
                    }
                } else if ($pay_method->payment_type == "billplz") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('billplz_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/billplz"));
                    }
                } else if ($pay_method->payment_type == "ccavenue") {
                    if ($pay_method->api_secret_key == "" || $pay_method->salt == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ccavenue_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/ccavenue"));
                    }
                } else if ($pay_method->payment_type == "sslcommerz") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_password == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('sslcommerz_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/sslcommerz"));
                    }
                } else if ($pay_method->payment_type == "walkingm") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('walkingm_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/walkingm"));
                    }
                } else if ($pay_method->payment_type == "mollie") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('mollie_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/mollie"));
                    }
                } else if ($pay_method->payment_type == "cashfree") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('cashfree_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/cashfree"));
                    }
                } else if ($pay_method->payment_type == "payfast") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payfast_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/payfast"));
                    }
                } else if ($pay_method->payment_type == "toyyibpay") {
                    if ($pay_method->api_signature == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('toyyibpay_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/toyyibpay"));
                    }
                } else if ($pay_method->payment_type == "twocheckout") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('twocheckout_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/twocheckout"));
                    }
                } else if ($pay_method->payment_type == "skrill") {
                    if ($pay_method->api_email == "" || $pay_method->salt == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('skrill_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/skrill"));
                    }
                } else if ($pay_method->payment_type == "payhere") {
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payhere_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/payhere"));
                    }
                } else if ($pay_method->payment_type == "onepay") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('onepay_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/onepay"));
                    }
                } else if ($pay_method->payment_type == "dpopay") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger"> instamojo_settings_not_available </div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/dpopay"));
                    }
                } else if ($pay_method->payment_type == "momopay") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger"> momopay_settings_not_available </div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/momopay"));
                    }
                } else if ($pay_method->payment_type == "icicipay") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger"> momopay_settings_not_available </div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/icici"));
                    }
                } else if ($pay_method->payment_type == "kowri") {
                    //==========Start Kowri==========
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "" || $pay_method->api_username == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('kowri_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/kowri"));
                    }
                    //==========End Kowri==========
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('something_went_wrong'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                // No row_counter or empty array
                $this->session->set_flashdata('error', $this->lang->line('please_select_record'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function pay()
    {
       
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $this->session->unset_userdata("params");
            ///=======================get balance fees
            $applied_fee_discount = $this->input->post('fee_discount');
            $student_fees_discount_id = $this->input->post('student_fees_discount_id');
            $fee_category = $this->input->post('fee_category');
            $student_transport_fee_id = $this->input->post('student_transport_fee_id');
            $student_fees_master_id   = $this->input->post('student_fees_master_id');
            $fee_groups_feetype_id    = $this->input->post('fee_groups_feetype_id');
            $fee_session_group_id=$this->studentfeemaster_model->getFeeSessionGroupId($student_fees_master_id);
            $student_id               = $this->input->post('student_id');
            if ($this->input->post('submit_mode') == "offline_payment") {

                $fee_record = array();
                $fee_record['fee_session_group_id']=$fee_session_group_id;
                if ($fee_category == "transport") {

                    $result = $this->studentfeemaster_model->studentTRansportDeposit($student_transport_fee_id);

                    $fee_record['fee_category']             = $fee_category;
                    $fee_record['student_transport_fee_id'] = $student_transport_fee_id;

                    $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $fee_record['student_fees_master_id'] = $student_fees_master_id;
                    $fee_record['fee_group_name']         = $this->lang->line("transport_fees");
                    $fee_record['fee_type_code']          = $result->month;
                } elseif ($fee_category == "fees") {
                    $data = array();

                    $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $data['student_fees_master_id'] = $student_fees_master_id;

                    $result = $this->studentfeemaster_model->studentDeposit($data);

                    $fee_record['fee_category']             = $fee_category;
                    $fee_record['student_transport_fee_id'] = $student_transport_fee_id;

                    $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $fee_record['student_fees_master_id'] = $student_fees_master_id;
                    $fee_record['fee_group_name']         = $result->fee_group_name;
                    $fee_record['fee_type_code']          = $result->fee_type_code;
                }
                $this->session->set_userdata("params", $fee_record);

                redirect("user/offlinepayment");
            } elseif ($this->input->post('submit_mode') == "online_payment") {

                if (!empty($this->payment_method)) {

                    $amount_balance      = 0;
                    $amount              = 0;
                    $amount_fine         = 0;
                    $amount_discount     = 0;
                    $fine_amount_balance = 0;
                    $data = array();

                    $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $data['student_fees_master_id'] = $student_fees_master_id;
                   
                    $fee_discount_group =  $this->input->post('fee_discount_group');; //addedd discount added array
                  
                    if($fee_category == "transport") {
                        $result = $this->studentfeemaster_model->studentTRansportDeposit($student_transport_fee_id);
                        $fee_record = array();
                        $fee_record['fee_session_group_id']=$fee_session_group_id;
                        $fee_record['is_system']             = 0;
                        $fee_record['fee_category']             = $fee_category;
                        $fee_record['student_transport_fee_id'] = $student_transport_fee_id;
                        $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                        $fee_record['student_fees_master_id'] = $student_fees_master_id;
                        $fee_record['fee_group_name']         = $this->lang->line("transport_fees");
                        $fee_record['fee_type_code']          = $result->month;
                        $fees_master_array = array();

                        $amount_detail = json_decode($result->amount_detail);

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount          = $amount + $amount_detail_value->amount;
                                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
                            }
                        }

                        $amount_balance = $result->fees - ($amount + $amount_discount);

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $amount_balance > 0) {
                            $fine_amount_balance = is_null($result->fine_percentage) ? $result->fine_amount : percentageAmount($result->fees, $result->fine_percentage);
                        }
                    } elseif ($fee_category == "fees") {
                        $result = $this->studentfeemaster_model->studentDeposit($data);
                        $fee_record = array();
                        $fee_record['fee_session_group_id']=$fee_session_group_id;
                        $fee_record['is_system']             = $result->is_system;
                        $fee_record['fee_category']             = $fee_category;
                        $fee_record['student_transport_fee_id'] = $student_transport_fee_id;
                        $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                        $fee_record['student_fees_master_id'] = $student_fees_master_id;
                        $fee_record['fee_group_name']         = $result->fee_group_name;
                        $fee_record['fee_type_code']          = $result->fee_type_code;

                        $fees_master_array = array();
                        $amount_detail = json_decode($result->amount_detail);

                        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {                           

                            // get cumulative fine amount as delay days 
                            if ($result->fine_type == 'cumulative') {
                                $date1 = date_create("$result->due_date");
                                $date2 = date_create(date('Y-m-d'));
                                $diff = date_diff($date1, $date2);
                                $due_days = $diff->format("%a");;

                                if ($this->customlib->get_cumulative_fine_amount($fee_groups_feetype_id, $due_days)) {
                                    $due_fine_amount = $this->customlib->get_cumulative_fine_amount($fee_groups_feetype_id, $due_days);
                                } else {
                                    $due_fine_amount = 0;
                                }
                                $fine_amount_balance  =  $due_fine_amount;
                            } else if ($result->fine_type == 'fix' || $result->fine_type == 'percentage') {
                                $fine_amount_balance   = $result->fine_amount;
                            }
                            // get cumulative fine amount as delay days
                        }

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d')))) {                         

                            // get cumulative fine amount as delay days 
                            if ($result->fine_type == 'cumulative') {
                                $date1 = date_create("$result->due_date");
                                $date2 = date_create(date('Y-m-d'));
                                $diff = date_diff($date1, $date2);
                                $due_days = $diff->format("%a");;

                                if ($this->customlib->get_cumulative_fine_amount($fee_groups_feetype_id, $due_days)) {
                                    $due_fine_amount = $this->customlib->get_cumulative_fine_amount($fee_groups_feetype_id, $due_days);
                                } else {
                                    $due_fine_amount = 0;
                                }
                                $fine_amount_balance  =  $due_fine_amount;
                            } else if ($result->fine_type == 'fix' || $result->fine_type == 'percentage') {
                                $fine_amount_balance   = $result->fine_amount;
                            }
                            // get cumulative fine amount as delay days
                        }

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount          = $amount + $amount_detail_value->amount;
                                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
                            }
                        }

                        $amount_balance = $result->amount - ($amount + $amount_discount);
                        if ($result->is_system) {

                            $amount_balance = $result->student_fees_master_amount - ($amount + $amount_discount);
                        }
                    }

                    $fine_amount_balance = $fine_amount_balance - $amount_fine;
                    
                    // Keep gross balance before any discount or user edits
                    $gross_balance = $amount_balance;
                    $original_fine_balance = $fine_amount_balance; // Store original fine balance before allocation
                    
                    $student_record               = $this->student_model->get($student_id);
                    $pay_method                   = $this->paymentsetting_model->getActiveMethod();

                    $fee_record['fine_balance']   = $fine_amount_balance;
                  
                    
                    // multiple discount applied section
                    // Use posted discount amount (already calculated in modal on paying amount)
                    $posted_discount = $this->input->post('fee_discount');
                    $final_discount_amount = 0;
                    
                    if (!empty($posted_discount) && is_numeric($posted_discount)) {
                        // Use the discount amount that was already calculated and displayed in modal
                        $final_discount_amount = floatval($posted_discount);
                    }

                    // Get posted values from textboxes (fee_amount_single and fine_amount_single)
                    // fee_amount_single is the paying amount for fees (before discount)
                    $posted_fee_amount = $this->input->post('fee_amount_single'); // Fees amount (before discount)
                    $posted_fine_amount = $this->input->post('fine_amount_single'); // Fine amount
                    
                    // Extract allocated amounts from posted values
                    $fee_allocated = 0;
                    $fine_allocated = 0;
                    
                    if (!empty($posted_fee_amount) && is_numeric($posted_fee_amount)) {
                        // fee_amount_single is the fees amount before discount
                        // After discount: fee_allocated = fee_amount_single - discount
                        $fee_allocated = floatval($posted_fee_amount);
                        if ($fee_allocated < 0) {
                            $fee_allocated = 0;
                        }
                    }
                    
                    if (!empty($posted_fine_amount) && is_numeric($posted_fine_amount)) {
                        // fine_amount_single is the allocated fine amount
                        $fine_allocated = floatval($posted_fine_amount);
                    }
                    
                    // Use allocated amounts
                    $amount_balance = $fee_allocated;
                    $fine_amount_balance = $fine_allocated;
                    
                    // Final amount = fees (after discount) + fine
                    $final_amount = $amount_balance + $fine_amount_balance;
                    
                    // Check if discount is 100% (discount amount equals or exceeds fees amount)
                    // Since discount is only applied on fees, check if discount >= fees amount
                    $is_100_percent_discount = false;
                    if ($final_discount_amount > 0 && $gross_balance > 0) {
                        // If discount amount is equal to or greater than fees amount, it's 100% discount on fees
                        if ($final_discount_amount >= $gross_balance) {
                            $is_100_percent_discount = true;
                            // If discount covers all fees, set amount_balance to gross_balance so it's marked as fully paid
                            // This ensures: fees - (amount + discount) = fees - (fees) = 0 (fully paid, not partial)
                            $amount_balance = $gross_balance;
                            // Final amount is only fine (if any)
                            $final_amount = $fine_amount_balance;
                        }
                    }

                    $gateway_processing_charge=0;
                   if($pay_method->charge_type=='percentage'){
                        
                    $gateway_processing_charge=((($final_amount-$final_discount_amount) * $pay_method->charge_value)/100);
                    }elseif($pay_method->charge_type=='fix'){
                        if($final_amount>0){
                            $gateway_processing_charge=$pay_method->charge_value;
                        }else{
                            $gateway_processing_charge=0;   
                        }
                        
                    }else{
                     $gateway_processing_charge=0;   
                    }   
 
                    // set fee record breakdown: display gross, collect allocated amounts
                    $fee_record['applied_fee_discount'] = $final_discount_amount;
                    $fee_record['display_amount_balance'] = $gross_balance; // original gross fee for display
                    $fee_record['amount_balance'] = $amount_balance; // allocated fee amount (from textbox)
                    $fee_record['payable_amount_balance'] = $amount_balance; // same as amount_balance
                    $fee_record['fine_balance'] = $fine_amount_balance; // allocated fine amount (from textbox)
                    // multiple discount applied section


                    $fees_master_array[]          = $fee_record;
                    //======================================

                    $page                = new stdClass();
                    $page->symbol        = $this->setting[0]['currency_symbol'];
                    $page->currency_name = $this->session->userdata('student')['currency_name'];

                    $params              = array(
                        'key'                       => $pay_method->api_secret_key,
                        'api_publishable_key'       => $pay_method->api_publishable_key,
                        'invoice'                   => $page,
                        'total'                     => $amount_balance, // allocated fee amount (for processing)
                        'fine_amount_balance'       => ($fine_amount_balance), // allocated fine amount
                        'applied_fee_discount'      => ($final_discount_amount), // discount amount (for display)
                        'student_fees_discount_id'  => ($student_fees_discount_id),
                        'student_session_id'        => $student_record['student_session_id'],
                        'guardian_phone'            => $student_record['guardian_phone'],
                        'name'                      => $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                        'email'                     => $student_record['email'],
                        'guardian_phone'            => $student_record['guardian_phone'],
                        'guardian_email'            => $student_record['guardian_email'],
                        'mobileno'                  => $student_record['mobileno'],
                        'address'                   => $student_record['permanent_address'],
                        'student_id'                => $student_id,
                        'student_fees_master_array' => $fees_master_array,
                        'fee_discount_group'        => $fee_discount_group,//new line added
                        'processing_charge_type'=>$pay_method->charge_type,
                        'gateway_processing_charge'=>$gateway_processing_charge

                    );

                  
                    // If discount is 100% (covers all fees) or final amount is 0 or less, process without payment gateway
                    if ($is_100_percent_discount) {
                        $bulk_fees=array();                        
                         
                        foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                        
                         $json_array = array(
                            // allocated fee amount (user's paying amount allocated to fee)
                            'amount'          =>  $fee_value['amount_balance']-$fee_value['applied_fee_discount'],
                            'date'            => date('Y-m-d'),
                            // discount amount (separate, for record keeping)
                            'amount_discount' => $fee_value['applied_fee_discount'],
                            'processing_charge_type'=>$params['processing_charge_type'],
                            'gateway_processing_charge'=>0,
                            // allocated fine amount (user's paying amount allocated to fine)
                            'amount_fine'     => $fee_value['fine_balance'],
                            'description'     => '',
                            'received_by'     => '',
                            'payment_mode'    => '',
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
                        $obj_mail['send_type']    = 'group';
                        $this->mailsmsconf->mailsms('fee_submission', $obj_mail);
                    }

                        if ($response) {
                              redirect(base_url("user/gateway/payment/successinvoice"));                     
                        } else {
                          redirect(base_url('user/gateway/payment/paymentfailed'));
                        }

                    }
                    //=====================================
                    if ($pay_method->payment_type == "paypal") {
                        //==========Start Paypal==========
                        if ($pay_method->api_username == "" || $pay_method->api_password == "" || $pay_method->api_signature == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paypal_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/paypal"));
                        }
                        //==========End Paypal==========
                    } else if ($pay_method->payment_type == "paystack") {
                        ///=====================

                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paystack_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/paystack"));
                        }

                        //=======================
                    } else if ($pay_method->payment_type == "stripe") {
                        //=====================

                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('stripe_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/stripe"));
                        }

                        //=======================
                    } else if ($pay_method->payment_type == "payu") {

                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payu_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/payu"));
                        }
                    } else if ($pay_method->payment_type == "ccavenue") {
                        if ($pay_method->api_secret_key == "" || $pay_method->salt == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ccavenue_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/ccavenue"));
                        }
                    } else if ($pay_method->payment_type == "instamojo") {

                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->salt == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('instamojo_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/instamojo"));
                        }
                    } else if ($pay_method->payment_type == "razorpay") {

                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('razorpay_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/razorpay"));
                        }
                    } else if ($pay_method->payment_type == "paytm") {
                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->paytm_website == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paytm_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/paytm"));
                        }
                    } else if ($pay_method->payment_type == "midtrans") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('midtrans_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/midtrans"));
                        }
                    } else if ($pay_method->payment_type == "pesapal") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('pesapal_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/pesapal"));
                        }
                    } else if ($pay_method->payment_type == "flutterwave") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('flutterwave_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/flutterwave"));
                        }
                    } else if ($pay_method->payment_type == "ipayafrica") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ipay_africa_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/ipayafrica"));
                        }
                    } else if ($pay_method->payment_type == "jazzcash") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('jazzcash_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/jazzcash"));
                        }
                    } else if ($pay_method->payment_type == "billplz") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('billplz_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/billplz"));
                        }
                    } else if ($pay_method->payment_type == "sslcommerz") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_password == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('sslcommerz_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/sslcommerz"));
                        }
                    } else if ($pay_method->payment_type == "walkingm") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('walkingm_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/walkingm"));
                        }
                    } else if ($pay_method->payment_type == "mollie") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('mollie_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/mollie"));
                        }
                    } else if ($pay_method->payment_type == "cashfree") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('cashfree_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/cashfree"));
                        }
                    } else if ($pay_method->payment_type == "payfast") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payfast_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/payfast"));
                        }
                    } else if ($pay_method->payment_type == "toyyibpay") {
                        if ($pay_method->api_signature == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('toyyibpay_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/toyyibpay"));
                        }
                    } else if ($pay_method->payment_type == "twocheckout") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('twocheckout_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/twocheckout"));
                        }
                    } else if ($pay_method->payment_type == "skrill") {
                        if ($pay_method->api_email == "" || $pay_method->salt == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('skrill_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/skrill"));
                        }
                    } else if ($pay_method->payment_type == "payhere") {
                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payhere_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/payhere"));
                        }
                    } else if ($pay_method->payment_type == "onepay") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('onepay_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/onepay"));
                        }
                    } else if ($pay_method->payment_type == "dpopay") {

                        if ($pay_method->api_secret_key == "" ) {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger"> instamojo_settings_not_available </div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/dpopay"));
                        }
                    } else if ($pay_method->payment_type == "momopay") {

                        if ($pay_method->api_secret_key == "" ) {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger"> momopay_settings_not_available </div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/momopay"));
                        }
                    } else if ($pay_method->payment_type == "icicipay") {

                        if ($pay_method->api_secret_key == "" ) {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger"> momopay_settings_not_available </div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/icici"));
                        }
                    } else if ($pay_method->payment_type == "kowri") {
                    //==========Start Kowri==========
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "" || $pay_method->api_username == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('kowri_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/kowri"));
                    }
                    //==========End Kowri==========
                }else {
                        $this->session->set_flashdata('error', $this->lang->line('something_went_wrong'));
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payment_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }

    public function pay1($student_fees_master_id, $fee_groups_feetype_id, $student_id)
    {
        $this->session->unset_userdata("params");
        ///=======================get balance fees

        if (!empty($this->payment_method)) {
            $data                           = array();
            $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
            $data['student_fees_master_id'] = $student_fees_master_id;
            $result                         = $this->studentfeemaster_model->studentDeposit($data);

            $fee_record                           = array();
            $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
            $fee_record['student_fees_master_id'] = $student_fees_master_id;
            $fee_record['fee_group_name']         = $result->fee_group_name;
            $fee_record['fee_type_code']          = $result->fee_type_code;

            $fees_master_array = array();

            $amount_balance      = 0;
            $amount              = 0;
            $amount_fine         = 0;
            $amount_discount     = 0;
            $fine_amount_balance = 0;
            $amount_detail       = json_decode($result->amount_detail);

            if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
                $fine_amount_balance = $result->fine_amount;
            }

            if (is_object($amount_detail)) {
                foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                    $amount          = $amount + $amount_detail_value->amount;
                    $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                    $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
                }
            }

            $amount_balance = $result->amount - ($amount + $amount_discount);
            if ($result->is_system) {
                $amount_balance = $result->student_fees_master_amount - ($amount + $amount_discount);
            }
            $fine_amount_balance = $fine_amount_balance - $amount_fine;

            $student_record               = $this->student_model->get($student_id);
            $pay_method                   = $this->paymentsetting_model->getActiveMethod();
            $fee_record['fine_balance']   = $fine_amount_balance;
            $fee_record['amount_balance'] = $amount_balance;
            $fees_master_array[]          = $fee_record;
            //======================================

            $page                = new stdClass();
            $page->symbol        = $this->setting[0]['currency_symbol'];
            $page->currency_name = $this->setting[0]['currency'];
            $params              = array(
                'key'                       => $pay_method->api_secret_key,
                'api_publishable_key'       => $pay_method->api_publishable_key,
                'invoice'                   => $page,
                'total'                     => ($amount_balance),
                'fine_amount_balance'       => convertBaseAmountCurrencyFormat($fine_amount_balance),
                'student_session_id'        => $student_record['student_session_id'],
                'guardian_phone'            => $student_record['guardian_phone'],
                'name'                      => $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),

                'email'                     => $student_record['email'],
                'mobileno'                  => $student_record['mobileno'],
                'guardian_email'            => $student_record['guardian_email'],
                'address'                   => $student_record['permanent_address'],
                'student_id'                => $student_id,
                'student_fees_master_array' => $fees_master_array,
            );
            //=====================================
            if ($pay_method->payment_type == "paypal") {
                //==========Start Paypal==========
                if ($pay_method->api_username == "" || $pay_method->api_password == "" || $pay_method->api_signature == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paypal_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/paypal"));
                }
                //==========End Paypal==========
            } else if ($pay_method->payment_type == "paystack") {
                ///=====================
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paystack_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/paystack"));
                }
                //=======================
            } else if ($pay_method->payment_type == "stripe") {
                ///=====================
                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('stripe_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/stripe"));
                }
                //=======================
            } else if ($pay_method->payment_type == "payu") {

                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payu_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/payu"));
                }
            } else if ($pay_method->payment_type == "ccavenue") {
                if ($pay_method->api_secret_key == "" || $pay_method->salt == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ccavenue_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/ccavenue"));
                }
            } else if ($pay_method->payment_type == "instamojo") {

                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->salt == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('instamojo_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/instamojo"));
                }
            } else if ($pay_method->payment_type == "razorpay") {

                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('razorpay_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/razorpay"));
                }
            } else if ($pay_method->payment_type == "paytm") {
                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->paytm_website == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paytm_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/paytm"));
                }
            } else if ($pay_method->payment_type == "midtrans") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('midtrans_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/midtrans"));
                }
            } else if ($pay_method->payment_type == "pesapal") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('pesapal_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/pesapal"));
                }
            } else if ($pay_method->payment_type == "flutterwave") {
                if ($pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('flutterwave_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/flutterwave"));
                }
            } else if ($pay_method->payment_type == "ipayafrica") {
                if ($pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ipay_africa_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/ipayafrica"));
                }
            } else if ($pay_method->payment_type == "jazzcash") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('jazzcash_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/jazzcash"));
                }
            } else if ($pay_method->payment_type == "billplz") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('billplz_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/billplz"));
                }
            } else if ($pay_method->payment_type == "sslcommerz") {
                if ($pay_method->api_publishable_key == "" || $pay_method->api_password == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('sslcommerz_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/sslcommerz"));
                }
            } else if ($pay_method->payment_type == "walkingm") {
                if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('walkingm_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/walkingm"));
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line('something_went_wrong'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payment_settings_not_available') . '</div>');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function paymentfailed()
    {
        $this->session->set_userdata('top_menu', 'Fees');
        $data['title']       = 'Invoice';
        $data['message']     = "dfsdfds";
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/paymentfailed', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function paymentprocessing()
    {
        $params         = $this->session->userdata('params');
        $student_record = $this->student_model->getByStudentSession($params['student_session_id']);
        $this->session->set_userdata('top_menu', 'Fees');
        $data['title']                 = 'Invoice';
        $data['message']               = "dfsdfds";
        $setting_result                = $this->setting_model->get();
        $data['settinglist']           = $setting_result;
        $mailsms_array                 = (object) array();
        $mailsms_array->transaction_id = $params['transaction_id'];
        $mailsms_array->guardian_phone = $params['guardian_phone'];
        $mailsms_array->email          = $params['email'];
        $mailsms_array->class          = $student_record['class'];
        $mailsms_array->section        = $student_record['section'];
        $applied_discount = isset($params['applied_fee_discount']) ? $params['applied_fee_discount'] : 0;
        $mailsms_array->fee_amount     = ($params['total'] + $params['fine_amount_balance']) - $applied_discount;
        $mailsms_array->guardian_email = $params['guardian_email'];
        $mailsms_array->mobileno       = $params['mobileno'];
        $mailsms_array->parent_app_key = $student_record['parent_app_key'];
        $mailsms_array->app_key = $student_record['app_key'];
        $mailsms_array->student_name   = $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        $this->mailsmsconf->mailsms('fee_processing', $mailsms_array);
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/paymentprocessing', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function successinvoice()
    {
        $this->session->set_userdata('top_menu', 'fees');
        $this->session->set_userdata('sub_menu', 'student/getFees');
        $data = array();
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/invoice', $data);
        $this->load->view('layout/student/footer', $data);
    }
}
