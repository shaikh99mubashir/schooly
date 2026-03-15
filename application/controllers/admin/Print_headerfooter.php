<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Print_headerfooter extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('SaasValidation');
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('print_header_footer', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/print_headerfooter');
        $data['title']  = 'SMS Config List';
        $data['result'] = $this->setting_model->get_printheader();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/print_headerfooter/print_headerfooter', $data);
        $this->load->view('layout/footer', $data);
    }

    public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }

    public function edit()
    {
        $message = "";
        if (isset($_POST['type'])) {
            $is_required = $this->setting_model->check_haederimage($_POST['type']);
            $this->form_validation->set_rules('header_image', $this->lang->line('header_image'), 'trim|xss_clean|callback_handle_upload[' . $is_required . ']');
				 

            if($_POST['type'] == 'staff_payslip'){
                $message = 'message';
            }else if($_POST['type'] == 'online_admission_receipt') {
                $message = "admission_message";
            }else if($_POST['type'] == 'online_exam') {
                $message = 'online_exam_message';
            }else if($_POST['type'] == 'general_purpose') {
                $message = 'general_purpose_message';
            }else if($_POST['type'] == 'email') {
                $message = 'email_message';
            }else{
                $message = 'message1';
            }	
		
        }

        $storage_array = "header_image";

        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if ($this->form_validation->run() == false) {
             
            $data['result'] = $this->setting_model->get_printheader();
            $this->load->view('layout/header', $data);
            $this->load->view('admin/print_headerfooter/print_headerfooter', $data);
            $this->load->view('layout/footer', $data);

        } else {
        
        try {
            $prev_file_size = 0;
            $total_image_upload_size = 0;

            if (isset($_FILES["header_image"]) && !empty($_FILES['header_image']['name'])) {

                if ($_POST['type'] == 'student_receipt') {
                    
                    $row_student_receipt = $this->setting_model->unlink_receiptheader();
                    $prev_file_size = $this->media_storage->getUploadedFileSize($row_student_receipt, 'uploads/print_headerfooter/student_receipt');
                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/student_receipt/");
                    //========
                    if (!IsNullOrEmptyString($img_name)) { 
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('header_image');
                    }
                    //========
                    if (!empty($row_student_receipt)) {
                        $this->media_storage->filedelete($row_student_receipt, "uploads/print_headerfooter/student_receipt/");
                    }
                } else if ($_POST['type'] == 'online_admission_receipt') {
                    
                    $row_online_admission_receipt = $this->setting_model->unlink_onlinereceiptheader();;
                    $prev_file_size = $this->media_storage->getUploadedFileSize($row_online_admission_receipt, 'uploads/print_headerfooter/online_admission_receipt');
                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/online_admission_receipt/");
                    //========
                    if (!IsNullOrEmptyString($img_name)) { 
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('online_admission_receipt');
                    }
                    //========
                    if (!empty($row_online_admission_receipt)) {
                        $this->media_storage->filedelete($row_online_admission_receipt, "uploads/print_headerfooter/online_admission_receipt/");
                    }
                } else if ($_POST['type'] == 'online_exam') {
                    
                    $row_online_exam = $this->setting_model->get_onlineexamheader();
                    $prev_file_size += $this->media_storage->getUploadedFileSize($row_online_exam, 'uploads/print_headerfooter/online_exam');
                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/online_exam/");
                    //========
                    if (!IsNullOrEmptyString($img_name)) { 
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('online_admission_receipt');
                    }
                    //========
                    if (!empty($row_online_exam)) {
                        $this->media_storage->filedelete($row_online_exam, "uploads/print_headerfooter/online_exam/");
                    }
               
                }else if ($_POST['type'] == 'general_purpose') {
                    
                    $row_general_purpose = $this->setting_model->get_general_purpose_header();
                    $prev_file_size += $this->media_storage->getUploadedFileSize($row_general_purpose, 'uploads/print_headerfooter/general_purpose');
                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/general_purpose/");
                    //========
                    if (!IsNullOrEmptyString($img_name)) { 
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('online_admission_receipt');
                    }
                    //========
                    if (!empty($row_general_purpose)) {
                        $this->media_storage->filedelete($row_general_purpose, "uploads/print_headerfooter/general_purpose/");
                    }
                }else if ($_POST['type'] == 'email') {

                    $row_email = $this->setting_model->get_email_header();
                    $prev_file_size += $this->media_storage->getUploadedFileSize($row_email, 'uploads/print_headerfooter/email');//added
                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/email/");
                    //========
                    if (!IsNullOrEmptyString($img_name)) { 
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('online_admission_receipt');
                    }
                    //========
                    if (!empty($row_email)) {
                        $this->media_storage->filedelete($row_email, "uploads/print_headerfooter/email/");
                    }
                }else {
                    $row = $this->setting_model->unlink_payslipheader();
                    $prev_file_size += $this->media_storage->getUploadedFileSize($row, 'uploads/print_headerfooter/staff_payslip');//added
                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/staff_payslip/");
                    //========
                    if (!IsNullOrEmptyString($img_name)) { 
                        $total_image_upload_size = $this->media_storage->getTmpFileSize('online_admission_receipt');
                    }
                    //========
                    if ($row != '') {
                        $this->media_storage->filedelete($row, "uploads/print_headerfooter/staff_payslip/");
                    }
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

                $data = array('print_type' => $_POST['type'], 'header_image' => $img_name, 'footer_content' => $_POST[$message], 'created_by' => $this->customlib->getStaffID());
                $this->setting_model->add_printheader($data);
            }
            } catch (Exception $e) {
                // Handle any errors gracefully
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error: ' . $e->getMessage() . '</div>');
                redirect('admin/print_headerfooter'); 
            }

            $data = array('print_type' => $_POST['type'], 'footer_content' => $_POST[$message], 'created_by' => $this->customlib->getStaffID());
            $this->setting_model->add_printheader($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/print_headerfooter'); 
        }
    }

    public function handle_upload($str, $is_required)
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["header_image"]) && !empty($_FILES['header_image']['name']) && $_FILES["header_image"]["size"] > 0) {

            $file_type = $_FILES["header_image"]['type'];
            $file_size = $_FILES["header_image"]["size"];
            $file_name = $_FILES["header_image"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['header_image']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->image_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            if ($is_required == 0) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('please_choose_a_file_to_upload'));
                return false;
            } else {
                return true;
            }
        }
    }

}
