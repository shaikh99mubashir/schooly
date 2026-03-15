<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Stuattendence extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->config->load("mailsms");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->load->model(array("classteacher_model",'class_section_time_model','studentAttendaceSetting_model'));
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/index');
        $sch_setting         = $this->setting_model->getSchoolDetail();
        $data['sch_setting'] = $this->sch_setting_detail;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $userdata            = $this->customlib->getUserData();
        $data['class_id']   = "";
        $data['section_id'] = "";
        $data['date']       = "";
		$is_first_time_attendance      = true;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data); 
            $this->load->view('layout/footer', $data);
        } else {

            $class              = $this->input->post('class_id');
            $section            = $this->input->post('section_id');
            $date               = $this->input->post('date');
            $data['class_id']   = $class;
            $data['section_id'] = $section;
            $data['date']       = $date;
            $search             = $this->input->post('search');

            $student_class_section_setting = $this->studentAttendaceSetting_model->getClassWiseAttendanceSettingByClassAndSection($class, $section);
            $data['student_class_section_setting']   = ($student_class_section_setting);

            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist                  = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist']          = $resultlist;

            if (!empty($resultlist)) {
                foreach ($resultlist as $key => $value) {
                    if (!IsNullOrEmptyString($value['attendence_type_id'])) {
                        $is_first_time_attendance = false;
                    }
                }
            }

            if($this->input->post('search') == "saveattendence"){
      
                $session_ary = $this->input->post('student_session');
                $attendance_array=[];
                $absent_student_list=[];
                $present_student_list=[];
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    $attendencetype = $this->input->post('attendencetype' . $value);
					
					if($attendencetype==4 || $attendencetype==5){ // absent or holiday
						$in_time  = null;
						$out_time = null;
					} else {
						$in_time_raw  = $this->input->post("in_time_" . $value);
						$out_time_raw = $this->input->post("out_time_" . $value);
					
						$in_time  = !empty($in_time_raw)  ? date('H:i:s', strtotime($in_time_raw)) : null;
						$out_time = !empty($out_time_raw) ? date('H:i:s', strtotime($out_time_raw)) : null;
					}

                   $absent_config = $this->config_attendance['absent'];
                
                    if ($attendencetype == $absent_config) {
                        $absent_student_list[] = $value;
                    }else if(
                            ($attendencetype == $this->config_attendance["present"]
                            || $attendencetype == $this->config_attendance["late"]
                            || $attendencetype == $this->config_attendance["half_day"]
                            || $attendencetype == $this->config_attendance["half_day_second_shift"] ) && $this->input->post('is_first_time_attendance')
                        ){

                            $present_student_list['student_sessions_id'][$value] = ($value);
                            $present_student_list['in_time'][$value] =$this->input->post("in_time_" . $value);
                    }
             
                    $attendance_array[] = array(                       
                        'student_session_id' => $value,
                        'attendence_type_id' => $attendencetype,
                        'remark'             => $this->input->post("remark" . $value),
                        'in_time'            => $in_time,
                        'out_time'           => $out_time,
                        'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                    );                    
                }
                $this->stuattendence_model->addorUpdate($attendance_array);
                if (!empty($absent_student_list)) {
                    $this->mailsmsconf->mailsms('student_absent_attendence', $absent_student_list, $date);
                }

                if (!empty($present_student_list)) {
                    $this->mailsmsconf->mailsms('student_present_attendence', $present_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>'); 
                redirect('admin/stuattendence/index','refresh'); 
            }

            $data['is_first_time_attendance']          = $is_first_time_attendance;
            $data['resultlist']          = $resultlist; 

            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function attendencereport()
    {
        if (!$this->rbac->hasPrivilege('attendance_by_date', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/attendenceReport');
        $data['title']      = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class              = $this->class_model->get();
        $userdata           = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
                $carray = array();
                $class  = array();
                $class  = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
            }
        }
        $data['classlist']  = $class;
        $data['class_id']   = "";
        $data['section_id'] = "";
        $data['date']       = "";
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class              = $this->input->post('class_id');
            $section            = $this->input->post('section_id');
            $date               = $this->input->post('date');
            $data['class_id']   = $class;
            $data['section_id'] = $section;
            $data['date']       = $date;
            $search             = $this->input->post('search');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        $arr = array(
                            'id'                 => $checkForUpdate,
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        $arr = array(
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    }
                }
            }
            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist                  = $this->stuattendence_model->searchAttendenceClassSectionPrepare($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));

            $data['resultlist']  = $resultlist;
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }    

    public function monthAttendance($st_month, $no_of_months, $student_id)
    {
        $record = array();
        $r     = array();
        $month = date('m', strtotime($st_month));
        $year  = date('Y', strtotime($st_month));
        foreach ($this->config_attendance as $att_key => $att_value) {
            $s = $this->stuattendence_model->count_attendance_obj($month, $year, $student_id, $att_value);
            $attendance_key = $att_key;
            $r[$attendance_key] = $s;
        }

        $record[$student_id] = $r;
        return $record;
    }
    
    public function saveclasstime()
    {
        $this->form_validation->set_rules('row[]', $this->lang->line('section'), 'trim|required|xss_clean');
        $class_sections=$this->input->post('class_section_id');
        $time_valid=true;

       if(!empty($class_sections) && isset($class_sections)){
        foreach ($class_sections as $class_sections_key => $class_sections_value) {
        if($class_sections_value == ""){
             $this->form_validation->set_rules('time', $this->lang->line('time'), 'trim|required|xss_clean');
              $time_valid=false;
                break;
        }
        }
       }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'row' => form_error('row')
            );
            if(!$time_valid){
                $msg['time']= form_error('time');
            }

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {

        $insert_data=array();
        $update_data=array();

         $prev_records=$this->input->post('prev_record_id');
           if(!empty($class_sections) && isset($class_sections)){
            foreach ($class_sections as $class_sections_key => $class_sections_value) {

              if($prev_records[$class_sections_key] > 0){
                 $update_data[]=array(
                        'id'=>$prev_records[$class_sections_key],
                        'class_section_id'=>$class_sections_key,
                        'time'=>$this->customlib->timeFormat($class_sections_value, true),
                    );

              }else{
                 $insert_data[]=array(
                        'class_section_id'=>$class_sections_key,
                        'time'=>$this->customlib->timeFormat($class_sections_value, true),
                    );
              }                  
               
             }
            }

             $this->class_section_time_model->add($insert_data, $update_data);

             $array = array('status' =>1 , 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);

    }

    //**** studattendance ****//
    public function savestudentsetting(){
        $this->form_validation->set_rules('row[]', $this->lang->line('row'), 'trim|required|xss_clean');
        $row = $this->input->post('row');
        $time_valid = true;

        if (!empty($row) && isset($row)) {
            foreach ($row as $row_key => $row_value) {
                $attendance_type = $this->input->post('attendance_type_id_' . $row_value);
                $class_section = $this->input->post('class_section_id_' . $row_value);
                $entry_time_from = $this->input->post('entry_time_from_' . $row_value);
                $entry_time_to = $this->input->post('entry_time_to_' . $row_value);
                $total_institute_hour = $this->input->post('total_institute_hour_' . $row_value);
                if ($class_section == "" || $entry_time_from == "" || $entry_time_to == "" || $total_institute_hour == "" ||  $attendance_type == "") {
                    $this->form_validation->set_rules(
                        'fields',
                        'fields --r',
                        'trim|required|xss_clean',
                        array('required' => $this->lang->line('fields_values_required'))
                    );
                    $time_valid = false;
                    break;
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'row' => form_error('row'),
                'fields' => form_error('fields')
            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $insert_array = array();
            $class_section_array = array();
            foreach ($row as $row_key => $row_value) {
                $class_section_array[] = ($this->input->post('class_section_id_' . $row_value));
                $class_section = $this->input->post('class_section_id_' . $row_value);
                $attendance_type = $this->input->post('attendance_type_id_' . $row_value);
                $entry_time_from = $this->input->post('entry_time_from_' . $row_value);
                $entry_time_to = $this->input->post('entry_time_to_' . $row_value);
                $total_institute_hour = $this->input->post('total_institute_hour_' . $row_value);
                $insert_array[] = array(
                    'attendence_type_id' => $attendance_type,
                    'class_section_id' => $class_section,
                    'entry_time_from' => $entry_time_from,
                    'entry_time_to' =>  $entry_time_to,
                    'total_institute_hour' => ($total_institute_hour)

                );
            }

            $this->studentAttendaceSetting_model->add($insert_array, $class_section_array);
            $array = array('status' => 1, 'message' => $this->lang->line('update_message'));
        }

        echo json_encode($array);
    }

}
