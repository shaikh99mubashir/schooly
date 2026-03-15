<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transfercertificate extends Admin_Controller
{
    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->library('media_storage');
        $this->load->model("transfercertificate_model");
        $this->config->load("app-config");
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
        $this->load->library('SaasValidation');

    }

    public function index()
    {
		if (!$this->rbac->hasPrivilege('tc_settings', 'can_view')) {
            access_denied();
        }	
       
        $data                           = array();
        $data['fields']                 = $this->transfercertificate_model->getallfields();
        $data['inserted_fields']        = $this->transfercertificate_model->getallfields();
        $data['sch_setting_detail']     = $this->sch_setting_detail;
        $data['custom_fields_array']    = $this->transfercertificate_model->getcustomfields();
        $data['header_result']          = $this->setting_model->get_printheader();
        $data['get_settings'] = $this->transfercertificate_model->get_settings();
        $data['print_next_tc_no'] = $this->transfercertificate_model->get_transfer_certificate_no();

        $this->load->view("layout/header");
        $this->load->view("admin/transfercertificate/index", $data);
        $this->load->view("layout/footer");
    }

    public function sortQueue()
    {
        $position  = $this->input->post("position");
        $queueData = array();
        $data      = array();
        $i         = 1;
        foreach ($position as $position_key => $position_value) {
            $data = array(
                "id"       => $position_value,
                "position" => $i,
            );
            array_push($queueData, $data);
            $i++;
        }

        if ($this->transfercertificate_model->updateQueue($queueData)) {
            echo json_encode(array("status" => "success", "message" => $this->lang->line("success_message")));
        } else {
            echo json_encode(array("status" => "error", "message" => $this->lang->line("no_changes_were_made")));
        }
    }

    public function changeformfieldsetting()
    {
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
            $iscustomfield=$this->input->post('iscustomfield');
            $this->transfercertificate_model->addformfields($insert,$iscustomfield);

            if ($this->input->post('name') == 'if_guardian_is') {
                $status = $this->input->post('status');
                $this->transfercertificate_model->editguardianfield($status);
            }
            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function download(){    
		
		if (!$this->rbac->hasPrivilege('download_tc', 'can_view')) {
            access_denied();
        }
		
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;       
        $data['sch_setting']     = $this->sch_setting_detail;       
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfercertificate/download', $data);
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
            $this->load->view('admin/transfercertificate/download', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function print_transfer_certificate(){ 

        $student_id                    =    $this->input->post('student_id'); 
        $student_session_id            =    $this->input->post('student_session_id'); 
        $is_regenerte                  =    $this->input->post('is_regenerte'); 
        $data['student_data']          =    $this->transfercertificate_model->get($student_id);      
        $data['getallfields']          =    $this->transfercertificate_model->getallfields();
        $data['get_settings']          =    $this->transfercertificate_model->get_settings();
        $data['print_next_tc_no']      =    $this->transfercertificate_model->get_transfer_certificate_no();
        $data['is_regenerte']          =    $is_regenerte;
		$data['sch_setting_detail']    = $this->sch_setting_detail;
        //save generated transfer certificate record
        $data_record = array(
            "tc_no"                 => $data['print_next_tc_no'],
            "student_session_id"    => $student_session_id,
            "is_regenerte"          => $is_regenerte
        );
        $this->transfercertificate_model->save_tc_details($data_record);
        //save generated transfer certificate record

        $html                                  =    $this->load->view('admin/transfercertificate/print_transfer_certificate', $data, true);  
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
        $content = $mpdf->Output(random_string() . '.pdf', 'I');
        if (!empty($content)) {
            //if pdf is successfully created only then transfer certificate record will save
        }
        return $content;    
    }

    public function edit_custom_field($id){  
        $data['id']              = $id;
        $data['sch_setting']     = $this->sch_setting_detail;
        $student                 = $this->student_model->get($id);
        $data['student']         = $student;
        $studentSession          = $this->student_model->getStudentSession($id);
        $data["session"]         = $studentSession["session"];
        $data['category_list']   = $this->category_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transfercertificate/edit_custom_field', $data);
        $this->load->view('layout/footer', $data);
    }

	public function save_custom_fields()
    { 
		$id = $this->input->post('student_id');
		$data['id']              = $id;
        $data['sch_setting']     = $this->sch_setting_detail;
        $student                 = $this->student_model->get($id);
        $data['student']         = $student;
        $studentSession          = $this->student_model->getStudentSession($id);
        $data["session"]         = $studentSession["session"];
        $data['category_list']   = $this->category_model->get();		
		$custom_fields           = $this->customfield_model->getByBelong('transfer_certificate');      
		$has_validation = false;

		foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
			if ($custom_fields_value['validation']) {
				$custom_fields_id   = $custom_fields_value['id'];
				$custom_fields_name = $custom_fields_value['name'];
				$this->form_validation->set_rules("custom_fields[transfer_certificate][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
				$has_validation = true;
			}
		}
		
		if ($has_validation && $this->form_validation->run() == false) {
			 
			$this->load->view('layout/header', $data);
			$this->load->view('admin/transfercertificate/edit_custom_field', $data);
			$this->load->view('layout/footer', $data);
		} else {
	
			$custom_field_post  = $this->input->post("custom_fields[transfer_certificate]");
			
			if (!empty($custom_field_post)) {
				foreach ($custom_field_post as $key => $value) {
					$check_field_type = $this->input->post("custom_fields[transfer_certificate][" . $key . "]");
					$field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
					$array_custom     = array(
						'belong_table_id' => $id,
						'custom_field_id' => $key,
						'field_value'     => $field_value,
					);
					$custom_value_array[] = $array_custom;
				}
			}
			$this->customfield_model->updateRecord($custom_value_array, $id, 'transfer_certificate');
			redirect("admin/transfercertificate/edit_custom_field/$id");
		}
    }

    public function update_signature()
    {

        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $storage_array = "file";
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
                'validate_storage' => form_error('validate_storage'),
            );
            $array = array('success' => false, 'error' => $data);
            echo json_encode($array);
        } else {
        try {

            $id = $this->input->post('id');
            $field_name = $this->input->post('field_name');;
            $setting = $this->transfercertificate_model->get_settings();
            $imagename=$setting[0]["$field_name"];

            if(empty($imagename)){
                $total_documents_failed_size = 0;
                $storage_array = ['file'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially
                $img_name = $this->media_storage->fileupload("file", "./uploads/transfer_certificate/");
                
                if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size = $this->media_storage->getTmpFileSize('file');  // get temp size of image because of image not uploaded 
                }

                if ($total_documents_failed_size > 0) {
                    $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
                }

            }else{

                $prev_file_size = 0;
                $total_image_upload_size = 0;

                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                    $prev_file_size = $this->media_storage->getUploadedFileSize("$imagename", 'uploads/transfer_certificate');;
                    $img_name = $this->media_storage->fileupload("file", "./uploads/transfer_certificate/");
                    if (!IsNullOrEmptyString($img_name)) {
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('file');
                    }
                } else {
                    $img_name = $imagename;
                }
                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                    $this->media_storage->filedelete("$imagename", "uploads/transfer_certificate");
                }
                if ($prev_file_size > $total_image_upload_size) {
                    // Previous file was larger 3
                    $size_difference = $prev_file_size - $total_image_upload_size;
                    $this->saasvalidation->deleteResouceQuota('storage', $size_difference);
                } elseif ($prev_file_size < $total_image_upload_size) {
                    // New file is larger 
                    $size_difference = $total_image_upload_size - $prev_file_size;
                    $this->saasvalidation->updateResouceQuota('storage', $size_difference);
                } else {
                    // File size unchanged → no quota adjustment needed 
                }
            }

            $data_record = array('id' => $id, "$field_name" => $img_name);
            $this->transfercertificate_model->update_setting($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        } catch (Exception $e) {
            $array = array('success' => false, 'error' =>$e->getMessage());
            echo json_encode($array);
        }
        }
    }

    public function handle_upload()
    {   
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp);            
            
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["file"]["type"] != 'image/gif' &&
                $_FILES["file"]["type"] != 'image/jpeg' &&
                $_FILES["file"]["type"] != 'image/png') {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["file"]["size"] > 1024000) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . " 1MB");
                return false;
            }
            return true;
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('logo_file_is_required'));
            return false;
        }
    }

    public function remove_signature(){
        $id = $this->input->post('id');;
        $field_name = $this->input->post('field_name');;
        $setting = $this->transfercertificate_model->get_settings();

        if ($setting[0]["$field_name"] != '') {
            $imagename=$setting[0]["$field_name"];
            $delete_file_size = $this->media_storage->getUploadedFileSize("$imagename", 'uploads/transfer_certificate');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);
            $this->media_storage->filedelete("$imagename", "uploads/transfer_certificate/");
        }

        $data_record = array('id' => $id, "$field_name" => "");
        $this->transfercertificate_model->update_setting($data_record);
        $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function save_generation_id(){
        $id = $this->input->post('id');
        $tc_no_start = $this->input->post('tc_no_start');
        $affiliation_no = $this->input->post('affiliation_no');
		
        $print_next_tc_no=$this->transfercertificate_model->get_transfer_certificate_no();
        if(empty($this->transfercertificate_model->check_is_tc_exist($tc_no_start)) && $tc_no_start>=$print_next_tc_no){
            // check is the start tc id already generated
            // also check start tc id should be greater than till now all records
            $data_record = array('id' => $id, "tc_no_start" => $tc_no_start, "affiliation_no" => $affiliation_no);
            $this->transfercertificate_model->update_setting($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }else{
            $array = array('success' => false, 'error' => $this->lang->line('please_enter_valid_serial_number'), 'message' =>'' );
            echo json_encode($array);
        }        
    }

    public function verify_tc(){  
		
		if (!$this->rbac->hasPrivilege('verify_tc', 'can_view')) {
            access_denied();
        }
		
        $data['resultlist']     = [];
        $data['sch_setting']    = $this->sch_setting_detail;       
        $data['student_tc_no']  = $student_tc_no =  $this->input->post('student_tc_no');
        $this->form_validation->set_rules('student_tc_no', $this->lang->line('tc_no'), 'trim|required|xss_clean');
        if($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfercertificate/verify_tc', $data);
            $this->load->view('layout/footer', $data);
        }else{

            $check_is_tc_exist = $this->transfercertificate_model->check_is_tc_exist($student_tc_no);

            $data['check_is_tc_exist'] = $check_is_tc_exist;

            if (!empty($check_is_tc_exist) 
                && is_array($check_is_tc_exist) 
                && isset($check_is_tc_exist[0]['student_session_id'])) {

                $student_session_id = $check_is_tc_exist[0]['student_session_id'];

            } else {
                $student_session_id = null;  
            }

            $resultlist                 = $this->transfercertificate_model->getByStudentSession($student_session_id);
			$data['resultlist']         = $resultlist;
			
            if(!empty($check_is_tc_exist) && !empty($resultlist)){ //when tc exist in the table                
              
                $data['student_data']      =  $this->transfercertificate_model->get($resultlist['id']);   
                $data['sch_setting']       =  $this->sch_setting_detail;
                $data['getallfields']      =  $this->transfercertificate_model->getallfields();
                $data['get_settings']      =  $this->transfercertificate_model->get_settings();
                $data['print_next_tc_no']  =  $check_is_tc_exist[0]["tc_no"];
                $data['is_regenerte']      =  $check_is_tc_exist[0]["is_regenerte"];
                $data['html']              =  $this->load->view('admin/transfercertificate/preview_tc', $data, true);
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfercertificate/verify_tc', $data);
            $this->load->view('layout/footer', $data);               
        }            
    }

    public function prepare_tc(){    
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;       
        $data['sch_setting']     = $this->sch_setting_detail;       
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transfercertificate/prepare_tc', $data);
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
            $this->load->view('admin/transfercertificate/prepare_tc', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }

    public function edit_header()
    {
        // $this->form_validation->set_rules('header_image', $this->lang->line('header_image'), 'trim|xss_clean|callback_handle_header_upload');
		
        $storage_array = "header_image";
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if ($this->form_validation->run() == false) {
             
            $data                           = array();
            $data['fields']                 = $this->transfercertificate_model->getallfields();
            $data['inserted_fields']        = $this->transfercertificate_model->getallfields();
            $data['sch_setting_detail']     = $this->sch_setting_detail;
            $data['custom_fields_array']    = $this->transfercertificate_model->getcustomfields();
            $data['header_result']          = $this->setting_model->get_printheader();
            $data['get_settings'] = $this->transfercertificate_model->get_settings();
            $data['print_next_tc_no'] = $this->transfercertificate_model->get_transfer_certificate_no();
            $this->load->view("layout/header");
            $this->load->view("admin/transfercertificate/index", $data);
            $this->load->view("layout/footer");

        } else {
        
			try {
				$prev_file_size = 0;
				$total_image_upload_size = 0;
				$row_transfer_certificate = $this->transfercertificate_model->get_settings();

					foreach ($row_transfer_certificate as $key => $value) {

						if (isset($_FILES["header_image"]) && !empty($_FILES['header_image']['name'])) {
							$prev_file_size = $this->media_storage->getUploadedFileSize($value["header_image"], 'uploads/transfer_certificate');//added
							$img_name = $this->media_storage->fileupload("header_image", "./uploads/transfer_certificate/");
							//added
							if (!IsNullOrEmptyString($img_name)) { 
								$total_image_upload_size += $this->media_storage->getTmpFileSize('header_image');
							}
							//added
							if (isset($_FILES["header_image"]) && $_FILES['header_image']['name'] != '' && (!empty($_FILES['header_image']['name']))) {
								$this->media_storage->filedelete($value["header_image"],"uploads/transfer_certificate");
							}
						} else {
							$img_name = $value["header_image"];
						}
						$id=$value["id"];

					}

					if ($prev_file_size > $total_image_upload_size) {
							// Previous file was larger 
							$size_difference = $prev_file_size - $total_image_upload_size;
							$this->saasvalidation->deleteResouceQuota('storage', $size_difference);
					} elseif ($prev_file_size < $total_image_upload_size) {
							// New file is larger 
							$size_difference = $total_image_upload_size - $prev_file_size;
							$this->saasvalidation->updateResouceQuota('storage', $size_difference);
					} else {
							// File size unchanged → no quota adjustment needed 
					}

					$data_record = array(
						'id' => $id,
						"header_image" => $img_name,
						"footer_content"=> $this->input->post('transfer_certificate')
					);
					$this->transfercertificate_model->update_setting($data_record);
            } catch (Exception $e) {
                // Handle any errors gracefully
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error: ' . $e->getMessage() . '</div>');
                redirect('admin/transfercertificate/index#tab_1/');
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/transfercertificate/index#tab_1/');
        }
    }


    public function handle_header_upload()
    {   
        if (isset($_FILES["header_image"]) && !empty($_FILES['header_image']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp        = explode(".", $_FILES["header_image"]["name"]);
            $extension   = end($temp);            
            
            if ($_FILES["header_image"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["header_image"]["type"] != 'image/gif' &&
                $_FILES["header_image"]["type"] != 'image/jpeg' &&
                $_FILES["header_image"]["type"] != 'image/png') {
                $this->form_validation->set_message('handle_header_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('handle_header_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["header_image"]["size"] > 1024000) {
                $this->form_validation->set_message('handle_header_upload', $this->lang->line('file_size_shoud_be_less_than') . " 1MB");
                return false;
            }
            return true;
        } else {
            $this->form_validation->set_message('handle_header_upload', $this->lang->line('logo_file_is_required'));
            return false;
        }
    }

   















    
    

}
