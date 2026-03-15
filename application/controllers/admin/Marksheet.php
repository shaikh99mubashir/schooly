<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Marksheet extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('SaasValidation');

    }

    public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }
    

    public function index()
    {
        if (!$this->rbac->hasPrivilege('design_marksheet', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/marksheet');
        $data['title'] = 'Add Library';

        $this->data['certificateList'] = $this->marksheet_model->get();
        $this->form_validation->set_rules('template', $this->lang->line('template'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('left_logo', $this->lang->line('left_logo'), 'callback_handle_upload[left_logo]');
        $this->form_validation->set_rules('right_logo', $this->lang->line('right_logo'), 'callback_handle_upload[right_logo]');
        $this->form_validation->set_rules('background_img', $this->lang->line('background_image'), 'callback_handle_upload[background_img]');
        $this->form_validation->set_rules('header_image', $this->lang->line('background_image'), 'callback_handle_upload[header_image]');
        $this->form_validation->set_rules('left_sign', $this->lang->line('sign'), 'callback_handle_upload[left_sign]');
        $this->form_validation->set_rules('middle_sign', $this->lang->line('sign'), 'callback_handle_upload[middle_sign]');
        $this->form_validation->set_rules('right_sign', $this->lang->line('sign'), 'callback_handle_upload[right_sign]');

        $storage_array = "left_logo,right_logo,left_sign,middle_sign,right_sign,background_img,header_image"; // use comma for multiple files     
		
		$this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if ($this->form_validation->run() == true) {

        try {

            if (isset($_POST['is_name'])) {
                $is_name = 1;
            } else {
                $is_name = 0;
            }
            if (isset($_POST['is_father_name'])) {
                $is_father_name = 1;
            } else {
                $is_father_name = 0;
            }
            if (isset($_POST['is_mother_name'])) {
                $is_mother_name = 1;
            } else {
                $is_mother_name = 0;
            }
            if (isset($_POST['is_admission_no'])) {
                $is_admission_no = 1;
            } else {
                $is_admission_no = 0;
            }
            if (isset($_POST['exam_session'])) {
                $exam_session = 1;
            } else {
                $exam_session = 0;
            }
            if (isset($_POST['is_roll_no'])) {
                $is_roll_no = 1;
            } else {
                $is_roll_no = 0;
            }
            if (isset($_POST['is_address'])) {
                $is_address = 1;
            } else {
                $is_address = 0;
            }
            if (isset($_POST['is_gender'])) {
                $is_gender = 1;
            } else {
                $is_gender = 0;
            }
            if (isset($_POST['is_photo'])) {
                $is_photo = 1;
            } else {
                $is_photo = 0;
            }
            if (isset($_POST['is_division'])) {
                $is_division = 1;
            } else {
                $is_division = 0;
            }
            if (isset($_POST['is_rank'])) {
                $is_rank = 1;
            } else {
                $is_rank = 0;
            }
            if (isset($_POST['is_class'])) {
                $is_class = 1;
            } else {
                $is_class = 0;
            }
            if (isset($_POST['is_section'])) {
                $is_section = 1;
            } else {
                $is_section = 0;
            }
            if (isset($_POST['is_dob'])) {
                $is_dob = 1;
            } else {
                $is_dob = 0;
            }
            if (isset($_POST['is_teacher_remark'])) {
                $is_teacher_remark = 1;
            } else {
                $is_teacher_remark = 0;
            }

            $insert_data = array(
                'template'          => $this->input->post('template'),
                'heading'           => $this->input->post('heading'),
                'title'             => $this->input->post('title'),
                'exam_name'         => $this->input->post('exam_name'),
                'school_name'       => $this->input->post('school_name'),
                'exam_center'       => $this->input->post('exam_center'),
                'date'              => $this->input->post('date'),
                'is_name'           => $is_name,
                'is_father_name'    => $is_father_name,
                'is_mother_name'    => $is_mother_name,
                'is_admission_no'   => $is_admission_no,
                'is_roll_no'        => $is_roll_no,
                'is_photo'          => $is_photo,
                'is_class'          => $is_class,
                'is_division'       => $is_division,
                'is_rank'           => $is_rank,
                'is_section'        => $is_section,
                'is_dob'            => $is_dob,
                'is_teacher_remark' => $is_teacher_remark,
                'content'           => $this->input->post('content'),
                'content_footer'    => $this->input->post('content_footer'),
                'exam_session'      => $exam_session,
                'header_image'      => "",
                'left_logo'         => "",
                'right_logo'        => "",
                'left_sign'         => "",
                'right_sign'        => "",
                'middle_sign'       => "",
                'background_img'    => "",
            );

            //===============================================================================
            $total_documents_failed_size = 0;
            $storage_array = ['left_logo','right_logo','left_sign','middle_sign','right_sign','background_img','header_image'];
           
            $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially 

             if (isset($_FILES["header_image"]) && !empty($_FILES["header_image"]['name'])) {
                $header_image_name           = $this->media_storage->fileupload("header_image", "./uploads/marksheet/");
                $insert_data['header_image'] = $header_image_name;
                if (IsNullOrEmptyString($header_image_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('header_image');  // get temp size of image because of image not uploaded 
                }
            }

            if(isset($_FILES["left_logo"]) && !empty($_FILES["left_logo"]['name'])) {
                $left_img_name            = $this->media_storage->fileupload("left_logo", "./uploads/marksheet/");
                $insert_data['left_logo'] = $left_img_name;
                if (IsNullOrEmptyString($left_img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('left_logo');  // get temp size of image because of image not uploaded 
                }
            }

            if (isset($_FILES["right_logo"]) && !empty($_FILES["right_logo"]['name'])) {
                $right_img_name            = $this->media_storage->fileupload("right_logo", "./uploads/marksheet/");
                $insert_data['right_logo'] = $right_img_name;
                if (IsNullOrEmptyString($right_img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('right_logo');  // get temp size of image because of image not uploaded 
                }
            }

            if (isset($_FILES["left_sign"]) && !empty($_FILES["left_sign"]['name'])) {
                $left_sign_img_name       = $this->media_storage->fileupload("left_sign", "./uploads/marksheet/");
                $insert_data['left_sign'] = $left_sign_img_name;
                if (IsNullOrEmptyString($left_sign_img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('left_sign');  // get temp size of image because of image not uploaded 
                }
            }

            if (isset($_FILES["middle_sign"]) && !empty($_FILES["middle_sign"]['name'])) {
                $middle_sign_img_name       = $this->media_storage->fileupload("middle_sign", "./uploads/marksheet/");
                $insert_data['middle_sign'] = $middle_sign_img_name;
                if (IsNullOrEmptyString($middle_sign_img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('middle_sign');  // get temp size of image because of image not uploaded 
                }
            }

            if (isset($_FILES["right_sign"]) && !empty($_FILES["right_sign"]['name'])) {
                $right_sign_img_name       = $this->media_storage->fileupload("right_sign", "./uploads/marksheet/");
                $insert_data['right_sign'] = $right_sign_img_name;
                if (IsNullOrEmptyString($right_sign_img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('right_sign');  // get temp size of image because of image not uploaded 
                }
            }

            if (isset($_FILES["background_img"]) && !empty($_FILES["background_img"]['name'])) {
                $background_img_name           = $this->media_storage->fileupload("background_img", "./uploads/marksheet/");
                $insert_data['background_img'] = $background_img_name;
                if (IsNullOrEmptyString($background_img_name)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('background_img');  // get temp size of image because of image not uploaded 
                }
            }

            if ($total_documents_failed_size > 0) {
                 $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
            }          
            //================================================================

            $this->marksheet_model->add($insert_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/marksheet/index');
         } catch (Exception $e) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $e->getMessage() . '</div>');
            redirect('admin/marksheet/index');

        }  
        }

        $this->load->view('layout/header');
        $this->load->view('admin/marksheet/createmarksheet', $this->data);
        $this->load->view('layout/footer');
    }

    public function handle_upload($str, $var)
    {

        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
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

            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed_or_extension_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('design_marksheet', 'can_edit')) {
            access_denied();
        }

        $data['title'] = 'Add Library';
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/marksheet');

        $this->data['certificateList'] = $this->marksheet_model->get();
        $marksheet = $this->marksheet_model->get($id);
        $this->data['marksheet'] = $marksheet;
        $this->form_validation->set_rules('template', $this->lang->line('template'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('left_logo', $this->lang->line('left_logo'), 'callback_handle_upload[left_logo]');
        $this->form_validation->set_rules('right_logo', $this->lang->line('right_logo'), 'callback_handle_upload[right_logo]');
        $this->form_validation->set_rules('background_img', $this->lang->line('background_img'), 'callback_handle_upload[background_img]');
        $this->form_validation->set_rules('left_sign', $this->lang->line('sign'), 'callback_handle_upload[left_sign]');
        $this->form_validation->set_rules('middle_sign', $this->lang->line('sign'), 'callback_handle_upload[middle_sign]');
        $this->form_validation->set_rules('right_sign', $this->lang->line('sign'), 'callback_handle_upload[right_sign]');
        $storage_array = "left_logo,right_logo,left_sign,middle_sign,right_sign,background_img,header_image"; // use comma for multiple files       
		$this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");	

        if ($this->form_validation->run() == true) {


        try {

            if (isset($_POST['is_name'])) {
                $is_name = 1;
            } else {
                $is_name = 0;
            }

            if (isset($_POST['is_father_name'])) {
                $is_father_name = 1;
            } else {
                $is_father_name = 0;
            }

            if (isset($_POST['is_mother_name'])) {
                $is_mother_name = 1;
            } else {
                $is_mother_name = 0;
            }

            if (isset($_POST['is_admission_no'])) {
                $is_admission_no = 1;
            } else {
                $is_admission_no = 0;
            }

            if (isset($_POST['exam_session'])) {
                $exam_session = 1;
            } else {
                $exam_session = 0;
            }

            if (isset($_POST['is_roll_no'])) {
                $is_roll_no = 1;
            } else {
                $is_roll_no = 0;
            }

            if (isset($_POST['is_address'])) {
                $is_address = 1;
            } else {
                $is_address = 0;
            }

            if (isset($_POST['is_gender'])) {
                $is_gender = 1;
            } else {
                $is_gender = 0;
            }

            if (isset($_POST['is_photo'])) {
                $is_photo = 1;
            } else {
                $is_photo = 0;
            }

            if (isset($_POST['is_division'])) {
                $is_division = 1;
            } else {
                $is_division = 0;
            }

            if (isset($_POST['is_rank'])) {
                $is_rank = 1;
            } else {
                $is_rank = 0;
            }

            if (isset($_POST['is_class'])) {
                $is_class = 1;
            } else {
                $is_class = 0;
            }

            if (isset($_POST['is_section'])) {
                $is_section = 1;
            } else {
                $is_section = 0;
            }

            if (isset($_POST['is_dob'])) {
                $is_dob = 1;
            } else {
                $is_dob = 0;
            }

            if (isset($_POST['is_teacher_remark'])) {
                $is_teacher_remark = 1;
            } else {
                $is_teacher_remark = 0;
            }

            $prev_file_size = 0;
            $total_image_upload_size = 0;
 
            $insert_data = array(
                'id'                => $this->input->post('id'),
                'template'          => $this->input->post('template'),
                'heading'           => $this->input->post('heading'),
                'title'             => $this->input->post('title'),
                'exam_name'         => $this->input->post('exam_name'),
                'school_name'       => $this->input->post('school_name'),
                'exam_center'       => $this->input->post('exam_center'),
                'content'           => $this->input->post('content'),
                'content_footer'    => $this->input->post('content_footer'),
                'date'              => $this->input->post('date'),
                'is_dob'            => $is_dob,
                'is_teacher_remark' => $is_teacher_remark,
                'is_name'           => $is_name,
                'is_father_name'    => $is_father_name,
                'is_mother_name'    => $is_mother_name,
                'is_admission_no'   => $is_admission_no,
                'is_roll_no'        => $is_roll_no,
                'is_photo'          => $is_photo,
                'is_class'          => $is_class,
                'is_rank'           => $is_rank,
                'is_section'        => $is_section,
                'is_division'       => $is_division,
                'exam_session'      => $exam_session,
            );

            $removeheader_image   = $this->input->post('removeheader_image');
            $removeleft_logo      = $this->input->post('removeleft_logo');
            $removeright_logo     = $this->input->post('removeright_logo');
            $removeleft_sign      = $this->input->post('removeleft_sign');
            $removemiddle_sign    = $this->input->post('removemiddle_sign');
            $removeright_sign     = $this->input->post('removeright_sign');
            $removebackground_img = $this->input->post('removebackground_img');

            if ($removeheader_image != '') {
                $insert_data['header_image'] = '';
            }

            if ($removeleft_logo != '') {
                $insert_data['left_logo'] = '';
            }

            if ($removeright_logo != '') {
                $insert_data['right_logo'] = '';
            }

            if ($removeleft_sign != '') {
                $insert_data['left_sign'] = '';
            }

            if ($removemiddle_sign != '') {
                $insert_data['middle_sign'] = '';
            }

            if ($removeright_sign != '') {
                $insert_data['right_sign'] = '';
            }

            if ($removebackground_img != '') {
                $insert_data['background_img'] = '';
            }

            
            if (isset($_FILES["left_logo"]) && $_FILES['left_logo']['name'] != '' && (!empty($_FILES['left_logo']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->left_logo,'uploads/marksheet');
                $left_img_name = $this->media_storage->fileupload("left_logo", "./uploads/marksheet/");
                $insert_data['left_logo'] = $left_img_name;

                if (!IsNullOrEmptyString($left_img_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('left_logo');
                }
            }
            if (isset($_FILES["left_logo"]) && $_FILES['left_logo']['name'] != '' && (!empty($_FILES['left_logo']['name']))) {
                $this->media_storage->filedelete($marksheet->left_logo, "uploads/marksheet");
            }

            if (isset($_FILES["right_logo"]) && $_FILES['right_logo']['name'] != '' && (!empty($_FILES['right_logo']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->right_logo,'uploads/marksheet');
                $right_logo_name = $this->media_storage->fileupload("right_logo", "./uploads/marksheet/");
                $insert_data['right_logo'] = $right_logo_name;
                if (!IsNullOrEmptyString($right_logo_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('right_logo');
                }
            }
            if (isset($_FILES["right_logo"]) && $_FILES['right_logo']['name'] != '' && (!empty($_FILES['right_logo']['name']))) {
                $this->media_storage->filedelete($marksheet->right_logo, "uploads/marksheet");
            }

            if (isset($_FILES["left_sign"]) && $_FILES['left_sign']['name'] != '' && (!empty($_FILES['left_sign']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->left_sign,'uploads/marksheet');
                $left_sign_name = $this->media_storage->fileupload("left_sign", "./uploads/marksheet/");
                $insert_data['left_sign'] = $left_sign_name;
                 if (!IsNullOrEmptyString($left_sign_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('left_sign');
                }
            }

            if (isset($_FILES["left_sign"]) && $_FILES['left_sign']['name'] != '' && (!empty($_FILES['left_sign']['name']))) {
                $this->media_storage->filedelete($marksheet->left_sign, "uploads/marksheet");
            }

            if (isset($_FILES["middle_sign"]) && $_FILES['middle_sign']['name'] != '' && (!empty($_FILES['middle_sign']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->middle_sign,'uploads/marksheet');
                $middle_sign_name = $this->media_storage->fileupload("middle_sign", "./uploads/marksheet/");
                $insert_data['middle_sign'] = $middle_sign_name;
                if (!IsNullOrEmptyString($middle_sign_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('middle_sign');
                }
            }
            if (isset($_FILES["middle_sign"]) && $_FILES['middle_sign']['name'] != '' && (!empty($_FILES['middle_sign']['name']))) {
                $this->media_storage->filedelete($marksheet->middle_sign, "uploads/marksheet");
            }

            if (isset($_FILES["right_sign"]) && $_FILES['right_sign']['name'] != '' && (!empty($_FILES['right_sign']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->right_sign,'uploads/marksheet');
                $right_sign_name = $this->media_storage->fileupload("right_sign", "./uploads/marksheet/");
                $insert_data['right_sign'] = $right_sign_name;
                if (!IsNullOrEmptyString($right_sign_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('right_sign');
                }
            }
            if (isset($_FILES["right_sign"]) && $_FILES['right_sign']['name'] != '' && (!empty($_FILES['right_sign']['name']))) {
                $this->media_storage->filedelete($marksheet->right_sign, "uploads/marksheet");
            }

            if (isset($_FILES["background_img"]) && $_FILES['background_img']['name'] != '' && (!empty($_FILES['background_img']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->background_img,'uploads/marksheet');
                $background_img_name           = $this->media_storage->fileupload("background_img", "./uploads/marksheet/");
                $insert_data['background_img'] = $background_img_name;
                 if (!IsNullOrEmptyString($background_img_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('background_img');
                }
            }
            if (isset($_FILES["background_img"]) && $_FILES['background_img']['name'] != '' && (!empty($_FILES['background_img']['name']))) {
                $this->media_storage->filedelete($marksheet->background_img, "uploads/marksheet");
            }

            if (isset($_FILES["header_image"]) && $_FILES['header_image']['name'] != '' && (!empty($_FILES['header_image']['name']))) {
                $prev_file_size += $this->media_storage->getUploadedFileSize($marksheet->header_image,'uploads/marksheet');
                $header_img_name             = $this->media_storage->fileupload("header_image", "./uploads/marksheet/");
                $insert_data['header_image'] = $header_img_name;
                if (!IsNullOrEmptyString($header_img_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('header_image');
                }
            }
            if (isset($_FILES["header_image"]) && $_FILES['header_image']['name'] != '' && (!empty($_FILES['header_image']['name']))) {
                $this->media_storage->filedelete($marksheet->header_image, "uploads/marksheet");
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

            $this->marksheet_model->add($insert_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/marksheet/index');
             } catch (Exception $e) {
                // Handle any errors gracefully
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error: ' . $e->getMessage() . '</div>');
            redirect('admin/marksheet/index');
            }   
        }

        $this->load->view('layout/header');
        $this->load->view('admin/marksheet/editmarksheet', $this->data);
        $this->load->view('layout/footer');
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('design_marksheet', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Certificate List';

        $row = $this->marksheet_model->get($id);

        if ($row->header_image != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->header_image, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->header_image, "uploads/marksheet/");
        }

        if ($row->left_logo != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->left_logo, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->left_logo, "uploads/marksheet/");
        }

        if ($row->right_logo != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->right_logo, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->right_logo, "uploads/marksheet/");
        }

        if ($row->left_sign != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->left_sign, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->left_sign, "uploads/marksheet/");
        }

        if ($row->middle_sign != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->middle_sign, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->middle_sign, "uploads/marksheet/");
        }

        if ($row->right_sign != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->right_sign, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->right_sign, "uploads/marksheet/");
        }

        if ($row->background_img != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->background_img, 'uploads/marksheet');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);     
            $this->media_storage->filedelete($row->background_img, "uploads/marksheet/");
        }


        $this->marksheet_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/marksheet/index');
    }

    public function view()
    {
        $id     = $this->input->post('certificateid');
        $output = '';
        $data   = array();
        $data['marksheet'] = $this->marksheet_model->get($id);
        $page = $this->load->view('admin/marksheet/_view', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

}
