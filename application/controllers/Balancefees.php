<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Balancefees extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->time               = strtotime(date('d-m-Y H:i:s'));
        $this->payment_mode       = $this->customlib->payment_mode();
        $this->search_type        = $this->customlib->get_searchtype();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
        $this->load->model("module_model");
        $this->load->model("balancefees_model");
    }

    public function index() 
    {
        $userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
    $class_section_array = $this->customlib->get_myClassSection();
    if (empty($class_section_array)) {
        access_denied();
    }
}
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'balancefees/index');

        $data['title']           = 'student fee';
        $data['payment_type']    = $this->customlib->getPaymenttype();
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['student_due_fee'] = array();
            $data['resultarray']     = array();
            $data['feetype']     = "";
            $data['feetype_arr'] = array();
        } else {
            $student_Array = array();
            $search_type   = $this->input->post('search_type');
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            if (isset($class_id)) {
                $studentlist = $this->student_model->searchByClassSectionWithSession($class_id, $section_id);
            } else {
                $studentlist = $this->student_model->getStudents();
            }

            $student_Array = array();
            if (!empty($studentlist)) {
                foreach ($studentlist as $key => $eachstudent) {
                    $obj                = new stdClass();
                    $obj->name          = $this->customlib->getFullName($eachstudent['firstname'], $eachstudent['middlename'], $eachstudent['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
                    $obj->id         = $eachstudent['id'];
                    $obj->class         = $eachstudent['class'];
                    $obj->section       = $eachstudent['section'];
                    $obj->admission_no  = $eachstudent['admission_no'];
                    $obj->roll_no       = $eachstudent['roll_no'];
                    $obj->father_name   = $eachstudent['father_name'];
                    $obj->mobileno      = $eachstudent['mobileno'];
                    $student_session_id = $eachstudent['student_session_id'];
                    $student_total_fees = $this->balancefees_model->getTransStudentFees($student_session_id);

                    if (!empty($student_total_fees)) {
                        $totalfee = 0;
                        $deposit  = 0;
                        $discount = 0;
                        $balance  = 0;
                        $fine     = 0;

                        $total_fees_fine_amount     = 0;
                        $total_amount     = 0;
                        $due_date     = 0;
                        $grand_fine_amount     = 0;
                        $curr_date=date("Y-m-d");
                      
                        foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {
                            if (!empty($student_total_fees_value->fees)) {
                                foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {                  
                                   $totalfee = $totalfee + $each_fee_value->amount;  
                                    if(isJSON($each_fee_value->amount_detail)){                                        
                                        $amount_detail = json_decode($each_fee_value->amount_detail);
    
                                        if (is_object($amount_detail) && !empty($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                $deposit  = (int) $deposit + (int) $amount_detail_value->amount;
                                                $fine     = (int) $fine + (int) $amount_detail_value->amount_fine;//fine amount paid
                                                $discount = (int) $discount + (int) $amount_detail_value->amount_discount;
                                            }
                                        }
                                    }
                                    
                                    //================================================================
                                    if(isset($each_fee_value->due_date)){
                                        $due_date=$each_fee_value->due_date;
                                        
                                        $total_amount += $each_fee_value->amount;                                       

                                        // get cumulative fine amount as delay days 
                                         if (strtotime("$due_date") < strtotime("$curr_date")) {
                                            if($each_fee_value->fine_type=='cumulative'){
                                                $date1=date_create("$each_fee_value->due_date");
                                                $date2=date_create(date('Y-m-d'));
                                                $diff=date_diff($date1,$date2);
                                                $due_days= $diff->format("%a");;
                                                            
                                                if($this->customlib->get_cumulative_fine_amount($each_fee_value->fee_groups_feetype_id,$due_days)){
                                                    $due_fine_amount=$this->customlib->get_cumulative_fine_amount($each_fee_value->fee_groups_feetype_id,$due_days);
                                                }else{
                                                    $due_fine_amount=0;
                                                }
                                                    $grand_fine_amount+=$due_fine_amount;
                                            
                                                }else if($each_fee_value->fine_type=='fix' || $each_fee_value->fine_type=='percentage'){
                                                    $grand_fine_amount+=$each_fee_value->fine_amount;
                                                }
                                            }
                                        // get cumulative fine amount as delay days
                                    }
                                    //================================================================
                                }
                            }
                        }

                        $obj->due_date     = $due_date;
                        $obj->grand_fine_amount     = $grand_fine_amount;
                        $obj->total_amount     = $total_amount;
                        $obj->totalfee     = $totalfee;
                        $obj->payment_mode = "N/A";
                        $obj->deposit      = $deposit;
                        $obj->fine         = $fine;
                        $obj->discount     = $discount;
                        $obj->balance      = $totalfee - ($deposit + $discount);
                    } else {

                        $obj->due_date  = 0;
                        $obj->grand_fine_amount  = 0;
                        $obj->total_amount  = 0;
                        $obj->totalfee     = 0;
                        $obj->payment_mode = 0;
                        $obj->deposit      = 0;
                        $obj->fine         = 0;
                        $obj->balance      = 0;
                        $obj->discount     = 0;
                    }

                    if ($search_type == 'all') {
                        $student_Array[] = $obj;
                    } elseif ($search_type == 'balance') {
                        if ($obj->balance > 0) {
                            $student_Array[] = $obj;
                        }
                    } elseif ($search_type == 'paid') {
                        if ($obj->balance <= 0) {
                            $student_Array[] = $obj;
                        }
                    }
                }
            }
           
            $classlistdata[]         = array('result' => $student_Array);
            $data['student_due_fee'] = $student_Array;
            $data['resultarray']     = $classlistdata;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('balancefees/index', $data); 
        $this->load->view('layout/footer', $data);
    }




}