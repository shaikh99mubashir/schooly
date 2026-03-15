<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Holiday extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("holiday_model");
		$this->sch_setting_detail  = $this->setting_model->getSetting();
    }

    public function index()
    {  	
		if (!$this->rbac->hasPrivilege('annual_calendar', 'can_view')) {
            access_denied();
        }
		
        $data['title']       	        =	$this->lang->line('select_criteria');
        $data["search_holiday_type"]	=	"";

        if (isset($_POST['search_holiday_type']) && $_POST['search_holiday_type'] != '') {
            $search_holiday_type            =   $_POST['search_holiday_type'];
			$data["search_holiday_type"]	=	$_POST['search_holiday_type'];
        }         
        $this->form_validation->set_rules('search_holiday_type', $this->lang->line('type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) { 
            $holidaylist   =   $this->holiday_model->get(null,null);
        } else {
            $holidaylist   =   $this->holiday_model->get(null, $search_holiday_type);
        }

        $data["holidaylist"]  	         = $holidaylist; 
		$data['superadmin_restriction']  = $this->sch_setting_detail->superadmin_restriction;
		$getStaffRole                     = $this->customlib->getStaffRole();
        $data['staffrole']                = json_decode($getStaffRole);
		$data['login_staff_id']           = $this->customlib->getStaffID();
        $data['holiday_type']             = $this->holiday_model->get_holiday_type();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/holiday/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add()
    {  		 
        $holiday_type=$this->input->post('holiday_type');       
		
		$this->form_validation->set_rules('from_date', $this->lang->line('from_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_date', $this->lang->line('to_date'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('holiday_type', $this->lang->line('type'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');     
        if ($this->form_validation->run() == false) {            
            $msg = array(
                'holiday_type'   =>     form_error('holiday_type'),
                'from_date'      =>     form_error('from_date'),
                'to_date'        =>     form_error('to_date'),
                'description'    =>     form_error('description')            
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            // IF THERE IS SINGLE DATE THEN IT WILL BE SAME FOR BOTH COLUMNS - FROM_DATE AND TO_DATE //
            $from_date   =    $this->input->post('from_date');
            $to_date     =    $this->input->post('to_date');              

			if($this->input->post('front_site')){
				$front_site	=	1;
			}else{
				$front_site	=	0;
			}  

            $data = array(
                'id'                =>     $this->input->post('id'),
                'holiday_type'      =>     $this->input->post('holiday_type'),
                'from_date'         =>     date('Y-m-d', $this->customlib->datetostrtotime($from_date)),
                'to_date'           =>     date('Y-m-d 23:59:00', $this->customlib->datetostrtotime($to_date)),
                'description'       =>     $this->input->post('description'),
                'front_site'        =>     $front_site,
                'created_by'        =>     $this->customlib->getStaffID(),
                'holiday_color'     =>     '#008000',              
                'session_id'    	=>     $this->setting_model->getCurrentSession()                
            );

            $edit_id= $this->input->post('id');
            if($edit_id>0){
                $data['updated_at']      =   date('Y-m-d') ;   
            }else{
                $data['created_at']      =   date('Y-m-d H:i:s') ;  
            }

            $this->holiday_model->add($data);      
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
    }     
        echo json_encode($array);
    }


    public function delete_holiday()
    {
		if (!$this->rbac->hasPrivilege('annual_calendar', 'can_delete')) {
            access_denied();
        }
		
        $this->holiday_model->delete_holiday($_POST['id']);
        $array = array('status' => 1, 'success' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }
	
	public function getholiday()
    {
        $id                  = $this->input->post("id");        
        $result              = $this->holiday_model->get($id);
		$result['from_date'] = date($this->customlib->getSchoolDateFormat(),strtotime($result['from_date']));
		$result['to_date']   = date($this->customlib->getSchoolDateFormat(),strtotime($result['to_date']));
        $json_array          = array('status' => '1', 'error' => '', 'result' => $result);
        echo json_encode($json_array);
    }

    public function holidaytype()
    {
        $data["title"]        = $this->lang->line("add_holiday_type");
        $holiday_type         = $this->holiday_model->get_holiday_type();
        $data["holiday_type"] = $holiday_type;
		$data['can_add_edit'] = 'can_add';
        $this->load->view("layout/header");
        $this->load->view("admin/holiday/holidaytype", $data);
        $this->load->view("layout/footer");
    }

    public function add_holiday_type()
    {
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
            'type', $this->lang->line('name'), array('required',array('check_exists', array($this->holiday_model, 'valid_holiday_type')),
            )
        );	
		$data['can_add_edit'] = 'can_add';
        $id = $this->input->post("id");
        if ($this->form_validation->run()) {
            $type = $this->input->post("type");
            if (!empty($id)) {
                $data = array('type' => $type,'id' => $id);
            }else {
                $data = array('type' => $type);
            }
            $insert_id = $this->holiday_model->add_holiday_type($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect("admin/holiday/holidaytype");
        } else {
            $data["title"]        = $this->lang->line("add_holiday_type");
            $holiday_type         = $this->holiday_model->get_holiday_type();
            $data["holiday_type"] = $holiday_type;
            $this->load->view("layout/header");
            $this->load->view("admin/holiday/holidaytype", $data);
            $this->load->view("layout/footer");
        }
    }

    public function editholidaytype($id)
    {
        $data["title"]          = $this->lang->line("edit_holiday_type");
        $result                 = $this->holiday_model->get_holiday_type($id);
        $data["result"]         = $result;
        $holiday_type           = $this->holiday_model->get_holiday_type();
        $data["holiday_type"]   = $holiday_type;
		
		$data['can_add_edit'] = 'can_edit';
		
        $this->load->view("layout/header");
        $this->load->view("admin/holiday/holidaytype", $data);
        $this->load->view("layout/footer");
    }

    public function delete_holiday_type($id)
    {
        $this->holiday_model->delete_holiday_type($id);
        redirect('admin/holiday/holidaytype');
    }
    
}

?>
