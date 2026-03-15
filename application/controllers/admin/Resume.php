<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Resume extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('media_storage');
        $this->load->model("resume_model");         
        $this->load->model("student_model");
        $this->load->model("class_model");
        $this->load->model("category_model");
        $this->load->model("language_model");
        $this->load->helper('file');
        $this->config->load("mailsms");  
        $this->sch_setting_detail = $this->setting_model->getSetting();              
    }

    public function index(){    

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;       
        $data['sch_setting']     = $this->sch_setting_detail;       
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/resume/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search  = $this->input->post('search');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                } else {
                    $data['searchby']     = "filter";
                    $data['class_id']     = $this->input->post('class_id');
                    $data['section_id']   = $this->input->post('section_id');
                    $resultlist           = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']   = $resultlist;                     
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/resume/index', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    //**** custom details of student resume ****/
    public function student_resume_details($id){    
        $data['id']              = $id;
        $data['sch_setting']     = $this->sch_setting_detail;
        $student                 = $this->student_model->get($id);
        $data['student']         = $student;
        $data['siblings']        = $this->student_model->getMySiblings($student['parent_id'], $student['id']);
        $studentSession          = $this->student_model->getStudentSession($id);
        $data["session"]         = $studentSession["session"];
        $data['get_student_work_experience']   = $this->resume_model->get_student_work_experience($id);
        $data['get_student_education']         = $this->resume_model->get_student_education($id);
        $data['get_student_skills']            = $this->resume_model->get_student_skills($id);
        $data['get_student_reference']         = $this->resume_model->get_student_reference($id);
        $data['category_list']                 = $this->category_model->get();
        $data['custom_fields']                 = $this->onlinestudent_model->getcustomfields();    
        $this->load->view('layout/header', $data);
        $this->load->view('admin/resume/student_resume_details', $data);
        $this->load->view('layout/footer', $data);
    }

    /***** this funtion is used to save multiple work experience details data *****/
    public function add_work() {   
        $total_rows = ($this->input->post('total_work_count'));
        if (isset($total_rows) && !empty($total_rows)) {          
          
          //validation to check each input keeps the value or not
            foreach ($total_rows as $row_key => $row_value) {  
                $this->form_validation->set_rules('institute_'.$row_value,$this->lang->line('institute'), 'trim|required');       
                $this->form_validation->set_rules('designation_'.$row_value,$this->lang->line('designation'), 'trim|required');       
                $this->form_validation->set_rules('year_'.$row_value,$this->lang->line('year'), 'trim|required');       
                $this->form_validation->set_rules('location_'.$row_value,$this->lang->line('location'), 'trim|required');       
                $this->form_validation->set_rules('detail_'.$row_value,$this->lang->line('details'), 'trim|required');    

                if ($this->form_validation->run() == false) {           
                    $msg = array(
                        'name'        => form_error('institute_'.$row_value),
                        'designation' => form_error('designation_'.$row_value),
                        'year'        => form_error('year_'.$row_value),
                        'location'    => form_error('location_'.$row_value),
                        'detail'      => form_error('detail_'.$row_value),
                    );
                    $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');  
                    echo json_encode($json_array);
                    return false;          
                }
            } 
            //validation to check each input keeps the value or not
		}
        // save the multiple data 
		$student_id   = $this->input->post('student_id');
		$this->resume_model->delete_work_experience($student_id);
			
        if(isset($total_rows) && !empty($total_rows) && (count($total_rows)>0)){
            $total_rows   = ($this->input->post('total_work_count'));          

            foreach ($total_rows as $row_key => $row_values) {                 
                $data  = array(
                    'institute'    => $this->input->post('institute_'.$row_values),
                    'designation'  => $this->input->post('designation_'.$row_values),
                    'year'         => $this->input->post('year_'.$row_values),
                    'location'     => $this->input->post('location_'.$row_values),
                    'detail'       => $this->input->post('detail_'.$row_values),
                    'student_id'   => $this->input->post('student_id'),
                );
                $insert_id = $this->resume_model->add_work_experience($data);             
            }
		}
        // save the multiple data 
        $json_array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));      
        echo json_encode($json_array);
    }

    /***** this funtion is used to save multiple education details data *****/
    public function add_education() { 
        $total_rows = ($this->input->post('total_education_count'));
        if (isset($total_rows) && !empty($total_rows)) {          
          
          //validation to check each input keeps the value or not
            foreach ($total_rows as $row_key => $row_value) {  
                $this->form_validation->set_rules('course_'.$row_value,$this->lang->line('course'), 'trim|required');       
                $this->form_validation->set_rules('university_'.$row_value,$this->lang->line('university'), 'trim|required');       
                $this->form_validation->set_rules('education_year_'.$row_value,$this->lang->line('year'), 'trim|required');       
                $this->form_validation->set_rules('education_detail_'.$row_value,$this->lang->line('details'), 'trim|required');       

                if ($this->form_validation->run() == false) {           
                    $msg = array(
                        'course'                => form_error('course_'.$row_value),
                        'university'            => form_error('university_'.$row_value),
                        'education_year'        => form_error('education_year_'.$row_value),
                        'education_detail'      => form_error('education_detail_'.$row_value),
                    );
                    $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');  
                    echo json_encode($json_array);
                    return false;          
                }
            } 
		}
        //validation to check each input keeps the value or not

        // save the multiple data 
		$student_id   = $this->input->post('student_id');
        $this->resume_model->delete_student_educational($student_id);
			
        if(isset($total_rows) && !empty($total_rows) && (count($total_rows)>0)){            
            foreach ($total_rows as $row_key => $row_values) {                 
                $data  = array(
                    'course'            => $this->input->post('course_'.$row_values),
                    'university'        => $this->input->post('university_'.$row_values),
                    'education_year'    => $this->input->post('education_year_'.$row_values),
                    'education_detail'  => $this->input->post('education_detail_'.$row_values),
                    'student_id'        => $this->input->post('student_id'),
                    );
                $insert_id = $this->resume_model->add_education_details($data);              
            }
		}
		
        // save the multiple data 
        $json_array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));      
        echo json_encode($json_array);
    }

    /***** this funtion is used to save multiple skill details data *****/
    public function add_skill() {   
        $total_rows = ($this->input->post('total_skill_count'));
        if (isset($total_rows) && !empty($total_rows)) {          
            //validation to check each input keeps the value or not
            foreach ($total_rows as $row_key => $row_value) {  
                $this->form_validation->set_rules('skill_category_'.$row_value,$this->lang->line('skill_category'), 'trim|required');       
                $this->form_validation->set_rules('skill_detail_'.$row_value,$this->lang->line('details'), 'trim|required');       

                if ($this->form_validation->run() == false) {           
                    $msg = array(
                        'skill_category'      => form_error('skill_category_'.$row_value),
                        'skill_detail'        => form_error('skill_detail_'.$row_value),
                    );
                    $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');  
                    echo json_encode($json_array);
                    return false;          
                }
            } 
            //validation to check each input keeps the value or not
		}
		$student_id   = $this->input->post('student_id');            
        $this->resume_model->delete_student_skills($student_id);
			
        if(isset($total_rows) && !empty($total_rows) && (count($total_rows)>0)){            
            foreach ($total_rows as $row_key => $row_values) {                 
                $data  = array(
                    'skill_category'  => $this->input->post('skill_category_'.$row_values),
                    'skill_detail'    => $this->input->post('skill_detail_'.$row_values),
                    'student_id'      => $this->input->post('student_id'),
                    );
            
                $insert_id = $this->resume_model->add_skills_detail($data);              
            }
		} 
        $json_array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));      
        echo json_encode($json_array);
    }

    /***** this funtion is used to save multiple referensh details data *****/
    public function add_referensh() {
        $total_rows = ($this->input->post('total_reference_count'));
        if (isset($total_rows) && !empty($total_rows)) {          
          //validation to check each input keeps the value or not
            foreach ($total_rows as $row_key => $row_value) {  
                $this->form_validation->set_rules('reference_name_'.$row_value,$this->lang->line('name'),'trim|required');       
                $this->form_validation->set_rules('relation_'.$row_value,$this->lang->line('relation'),'trim|required');       
                $this->form_validation->set_rules('reference_age_'.$row_value,$this->lang->line('age'),'trim|required');       
                $this->form_validation->set_rules('profession_'.$row_value,$this->lang->line('profession'),'trim|required');       
                $this->form_validation->set_rules('contact_'.$row_value,$this->lang->line('contact'),'trim|required');       

                if ($this->form_validation->run() == false) {           
                    $msg = array(
                        'name'                     => form_error('reference_name_'.$row_value),
                        'relation'                 => form_error('relation_'.$row_value),
                        'reference_age'            => form_error('reference_age_'.$row_value),
                        'profession'               => form_error('profession_'.$row_value),
                        'contact'                  => form_error('contact_'.$row_value),
                    );
                    $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');  
                    echo json_encode($json_array);
                    return false;          
                }
            } 
		}
        //validation to check each input keeps the value or not

        // save the multiple data 
		$student_id   = $this->input->post('student_id');
        $this->resume_model->delete_referensh($student_id);
        if(isset($total_rows) && !empty($total_rows) && (count($total_rows)>0)){
            foreach ($total_rows as $row_key => $row_values) {                 
                $data  = array(
                    'name'         => $this->input->post('reference_name_'.$row_values),
                    'relation'     => $this->input->post('relation_'.$row_values),
                    'age'          => $this->input->post('reference_age_'.$row_values),
                    'profession'   => $this->input->post('profession_'.$row_values),
                    'contact'      => $this->input->post('contact_'.$row_values),
                    'student_id'   => $this->input->post('student_id'),
                    );
            
                $insert_id = $this->resume_model->add_reference_detail($data);              
            }
		}
        // save the multiple data 
        $json_array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));      
        echo json_encode($json_array);
    }

    public function resume_setting(){
        $data['sch_setting_detail']    =   $this->sch_setting_detail;
        $data['inserted_fields']       =   $this->resume_model->getformfields();
        $data['additional_fields']     =   $this->resume_model->get_additional_fields();
        $data['fields']                =   get_resume_editable_fields();   
        $data['custom_fields']         =   $this->onlinestudent_model->getcustomfields();   
        $setting                       =   $this->setting_model->getSetting();
        $data['result']                =   $setting;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/resume/resume_setting', $data);
        $this->load->view('layout/footer', $data);
    }

    public function changeresumefieldsetting(){
        $this->form_validation->set_rules('name', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line('status'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $msg = array(
                'status' => form_error('status'),
                'name'   => form_error('name'),
            );
            $array = array('status' => '0', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));
        } else {
            $insert = array(
                'name'   => $this->input->post('name'),
                'status' => $this->input->post('status'),
            );
            $this->resume_model->addformfields($insert); 
            if ($this->input->post('name') == 'if_guardian_is') {
                $status = $this->input->post('status');
                $this->resume_model->editguardianfield($status);
            }
            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function change_additional_fields_status(){
        $this->form_validation->set_rules('name', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line('status'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $msg = array(
                'status' => form_error('status'),
                'name'   => form_error('name'),
            );
            $array = array('status' => '0', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));
        } else {
            $insert = array(
                'name'   => $this->input->post('name'),
                'status' => $this->input->post('status'),
            );
            $this->resume_model->add_additional_fields_setting($insert); 
            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }


    public function printpdfresume(){      
        $student_id                            =    $this->input->post('student_id'); 
        $data['student_data']                  =    $this->resume_model->get($student_id);         
        $data['get_student_work_experience']   =    $this->resume_model->get_student_work_experience($student_id);
        $data['get_student_education']         =    $this->resume_model->get_student_education($student_id);
        $data['get_student_skills']            =    $this->resume_model->get_student_skills($student_id);
        $data['get_student_reference']         =    $this->resume_model->get_student_reference($student_id);
        $data['category_list']                 =    $this->category_model->get();
        $data['sch_setting']                   =    $this->sch_setting_detail;
        $html                                  =    $this->load->view('admin/resume/printpdfresume', $data, true);  
        $this->load->library('m_pdf');
        $mpdf       = $this->m_pdf->load();
        $stylesheet = file_get_contents(base_url() . 'backend/resume_pdf_style.css'); // external css        
        $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf      
        $mpdf->SetWatermarkText("", .2); // add watermark text to be show in marksheet
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showWatermarkText = true;
        $mpdf->autoScriptToLang  = true;
        $mpdf->baseScript        = 1;
        $mpdf->autoLangToFont    = true;
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $response = true;
        $content = $mpdf->Output(random_string() . '.pdf', 'I');
        return $content;    
    }

	public function download(){    
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;       
        $data['sch_setting']     = $this->sch_setting_detail;       
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/resume/download', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search  = $this->input->post('search');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                } else {
                    $data['searchby']     = "filter";
                    $data['class_id']     = $this->input->post('class_id');
                    $data['section_id']   = $this->input->post('section_id');
                    $resultlist           = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']   = $resultlist;                     
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/resume/download', $data);
            $this->load->view('layout/footer', $data);
        }
    }
	
	public function add_other_details(){
        $this->form_validation->set_rules('about', $this->lang->line('about'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student_id', ('student_id'), 'trim|required|xss_clean');        
        
        if ($this->form_validation->run() == false) {
            $msg = array(
                'about'         => form_error('about'),
                'student_id'    => form_error('student_id'),
                'designation'   => form_error('designation'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));
        } else {
            $insert = array(
                'id'            => $this->input->post('student_id'),
                'about'         => $this->input->post('about'),
                'designation'   => $this->input->post('designation'),
            );
            $this->student_model->add($insert); 
             
            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    //**** resume download enable disabled setting for student panel profile ****//
    public function enable_download_setting(){
        $data_record = array(
            'id'                        => $this->input->post('sch_id'),
            'student_resume_download'   => $this->input->post('student_resume_download'),
        );
        $this->setting_model->add($data_record);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }






}