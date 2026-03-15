<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feemaster extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
         $this->load->model('feesessiongroup_model');
    }
  
	public function index()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['title']        = $this->lang->line('fees_master_list');
        $feegroup             = $this->feegroup_model->get();
        $data['feegroupList'] = $feegroup;
        $feetype              = $this->feetype_model->get();
        $data['feetypeList']  = $feetype;
        $feegroup_result       = $this->feesessiongroup_model->getFeesByGroup(null,0);
        $data['feemasterList'] = $feegroup_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/feemasterList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_data(){

        $this->form_validation->set_rules('feetype_id', $this->lang->line('fee_type'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');
        $this->form_validation->set_rules(
            'fee_groups_id', $this->lang->line('fee_group'), array(
                'required',
                array('check_exists', array($this->feesessiongroup_model, 'valid_check_exists')),
            )
        );

        if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
            $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        }

        if($_POST['account_type'] == 'cumulative'){
            $this->form_validation->set_rules("overdue_day[]", $this->lang->line('total_overdue'), 'required|numeric');
            $this->form_validation->set_rules("overdue_fine[]", $this->lang->line('fine_amount'), 'required|numeric');
        }

        if ($this->form_validation->run() == false) {
             $data = array(
                'fee_groups_id'     => form_error('fee_groups_id'),
                'feetype_id'        => form_error('feetype_id'),
                'amount'            => form_error('amount'),
                'fine_amount'       => form_error('fine_amount'),
				  'fine_percentage'       => form_error('fine_percentage'),	 
                'due_date'          => form_error('due_date'),
                'overdue_day'       => form_error('overdue_day[]'),
                'overdue_fine'      => form_error('overdue_fine[]'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);

        } else {
            
            if($this->input->post('fine_amount')){
                $fine_amount    =   convertCurrencyFormatToBaseAmount($this->input->post('fine_amount'));
            }else{
                $fine_amount    = '';
            }

            if($this->input->post('fine_per_day')){
                $fine_per_day =   1;
            }else{
                $fine_per_day =   0;
            }  
            
            $insert_array = array(
                'fee_groups_id'   => $this->input->post('fee_groups_id'),
                'feetype_id'      => $this->input->post('feetype_id'),
                'amount'          => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                'session_id'      => $this->setting_model->getCurrentSession(),
                'fine_type'       => $this->input->post('account_type'),
                'fine_percentage' => $this->input->post('fine_percentage'),
                'fine_amount'     => $fine_amount,
                'fine_per_day'    => $fine_per_day,
            );

            $feegroup_result = $this->feesessiongroup_model->add($insert_array); 

            if($_POST['account_type'] == 'cumulative'){

            $overdue_day    =   $this->input->post('overdue_day[]');
            $overdue_fine   =   $this->input->post('overdue_fine[]');

            if(count($overdue_day)>0){
            for($i=0;$i<count($overdue_day);$i++){
                $insert_fine_array = array(
                'overdue_day'               => $this->input->post("overdue_day[$i]"),
                'fine_amount'               => $this->input->post("overdue_fine[$i]"),
                'fee_groups_feetype_id'     => $feegroup_result,
                'fee_session_group_id'      => $this->get_fee_session_group_id($this->input->post('fee_groups_id')),
            );
            $this->feesessiongroup_model->add_fine($insert_fine_array);
            }
            }
            }          

            echo json_encode(array('status' => 1, 'msg' => "successfully_saved", 'error' => ''));                    
        }

    }

    public function get_fee_session_group_id($fee_groups_id){
        return $this->feesessiongroup_model->group_exists($fee_groups_id);
    }

	 public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('fees_master', 'can_delete')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('fees_master_list');
        $this->feegrouptype_model->remove($id);
        $this->feegrouptype_model->remove_comulative_by_fee_groups_feetype_id($id);
        redirect('admin/feemaster/index');
    }

    public function deletegrp($id)
    {
        $data['title'] = $this->lang->line('fees_master_list');
        $this->feesessiongroup_model->remove($id);
        $this->feesessiongroup_model->remove_comulative_by_fee_groups_id($id);
        redirect('admin/feemaster');
    }	
	
	public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('fees_master', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']                 =   $id;
        $cumulative_fine            =   $this->feesessiongroup_model->get_cumulative_fine($id);
        $data['cumulative_fine']    =   $cumulative_fine;
        $feegroup_type              =   $this->feegrouptype_model->get($id);
        $data['feegroup_type']      =   $feegroup_type;
        $feegroup                   =   $this->feegroup_model->get();
        $data['feegroupList']       =   $feegroup;
        $feetype                    =   $this->feetype_model->get();
        $data['feetypeList']        =   $feetype;
        $feegroup_result            =   $this->feesessiongroup_model->getFeesByGroup(null,0);
        $data['feemasterList']      =   $feegroup_result;
      
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/feemasterEdit', $data);
        $this->load->view('layout/footer', $data);       
    }

    public function edit_data(){
        $this->form_validation->set_rules('feetype_id', $this->lang->line('fee_type'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric');
        $this->form_validation->set_rules(
            'fee_groups_id', $this->lang->line('fee_group'), array(
                'required',
                array('check_exists', array($this->feesessiongroup_model, 'valid_check_exists')),
            )
        );

        if (isset($_POST['account_type']) && $_POST['account_type'] == 'fix') {
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        } elseif (isset($_POST['account_type']) && ($_POST['account_type'] == 'percentage')) {
            $this->form_validation->set_rules('fine_percentage', $this->lang->line('percentage'), 'required|numeric');
            $this->form_validation->set_rules('fine_amount', $this->lang->line('fix_amount'), 'required|numeric');
            $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');
        }

        if($_POST['account_type'] == 'cumulative'){
            $this->form_validation->set_rules("overdue_day[]", $this->lang->line('total_overdue'), 'required|numeric');
            $this->form_validation->set_rules("overdue_fine[]", $this->lang->line('fine_amount'), 'required|numeric'); 
        }

        if ($this->form_validation->run() == false) {
             $data = array(
                'fee_groups_id'     => form_error('fee_groups_id'),
                'feetype_id'        => form_error('feetype_id'),
                'amount'            => form_error('amount'),
                'fine_amount'       => form_error('fine_amount'),
				 'fine_percentage'   => form_error('fine_percentage'),	 
                'due_date'          => form_error('due_date'),
                'overdue_day'       => form_error('overdue_day[]'),
                'overdue_fine'      => form_error('overdue_fine[]'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);

        } else {            
            if($this->input->post('fine_amount')){
                $fine_amount    =   convertCurrencyFormatToBaseAmount($this->input->post('fine_amount'));
            }else{
                $fine_amount    = '';
            }

            if($this->input->post('fine_per_day')){
                $fine_per_day =   1;
            }else{
                $fine_per_day =   0;
            }            

            $insert_array = array(
                'id'              => $this->input->post('id'),
                'feetype_id'      => $this->input->post('feetype_id'),
                'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                'amount'          => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'fine_type'       => $this->input->post('account_type'),
                'fine_percentage' => $this->input->post('fine_percentage'),
                'fine_amount'     => $fine_amount,
                'fine_per_day'    => $fine_per_day,
            );

            $feegroup_result = $this->feegrouptype_model->add($insert_array);

            if($_POST['account_type'] == 'cumulative'){

            $cumulative_id      =   $this->input->post('cumulative_id[]');
            $overdue_day        =   $this->input->post('overdue_day[]');
            $overdue_fine       =   $this->input->post('overdue_fine[]');

            if(count($overdue_day)>0){
            for($i=0;$i<count($overdue_day);$i++){
                $insert_fine_array = array(
                'id'                        => $this->input->post("cumulative_id[$i]"),
                'overdue_day'               => $this->input->post("overdue_day[$i]"),
                'fine_amount'               => $this->input->post("overdue_fine[$i]"),
                'fee_groups_feetype_id'     => $feegroup_result,
                'fee_session_group_id'      => $this->get_fee_session_group_id($this->input->post('fee_groups_id')),
            );
            $this->feesessiongroup_model->add_fine($insert_fine_array);

            }
            }
            }else{
                //if on edit fine type is not cumulative then we will remove all the cumulative fine recored from the table 
                $fee_groups_feetype_id=$this->input->post('id');
                $this->feesessiongroup_model->remove_cumulativeby_grouptypid($fee_groups_feetype_id);
            }          
            echo json_encode(array('status' => 1, 'msg' => $this->lang->line('success_message'), 'error' => ''));  
        }
    }

    public function remove_row(){
        $cumulative_id=$_POST['cumulative_id'];
        $this->feesessiongroup_model->remove_cumulative($cumulative_id);
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('success_message'), 'error' => ''));
    }

    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_group_assign', 'can_view')) {
            access_denied();
        }
        $userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
    $class_section_array = $this->customlib->get_myClassSection();
    if (empty($class_section_array)) {
        access_denied();
    }
}
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feemaster');
        $data['id']              = $id;
        $data['title']           = $this->lang->line('student_fees');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $feegroup_result         = $this->feesessiongroup_model->getFeesByGroup($id);
        $data['feegroupList']    = $feegroup_result;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $genderList            = $this->customlib->getGender();
        $data['genderList']    = $genderList;
        $RTEstatusList         = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;

        $category             = $this->category_model->get();
        $data['categorylist'] = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender']      = $this->input->post('gender');
            $data['rte_status']  = $this->input->post('rte');
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');

            $resultlist         = $this->studentfeemaster_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feemaster/assign', $data);
        $this->load->view('layout/footer', $data);
    }    

}
