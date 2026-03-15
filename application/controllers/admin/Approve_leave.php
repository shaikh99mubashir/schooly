<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class approve_leave extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('SaasValidation');
    }

    public function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }
 
    public function index()
    {
        if (!$this->rbac->hasPrivilege('approve_leave', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'Attendance/approve_leave');
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['class_id']    = $class_id    = '';
        $data['section_id']  = $section_id  = '';
        $data['sch_setting'] = $this->setting_model->getSetting();
        $data['results']     = array();
        $listaudit = $this->apply_leave_model->get(null, null, null);

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $data['class_id'] = $class_id = $_POST['class_id'];
            $data['section_id'] = $section_id = $_POST['section_id'];
            $listaudit = $this->apply_leave_model->get(null, $class_id, $section_id);
        }

        $data['results'] = $listaudit;
        $this->load->view('layout/header');
        $this->load->view('admin/approve_leave/index', $data);
        $this->load->view('layout/footer');
    }

    public function get_details()
    {
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $can_edit = 1;

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $myclasssubjects = $this->apply_leave_model->canApproveLeave($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'));
            $can_edit        = $myclasssubjects;
        }

        if ($can_edit == 0) {

            $data = array('status' => 'fail', 'error' => $this->lang->line('not_authoried'));
        } else {
            $data                 = $this->apply_leave_model->get($_POST['id'], null, null);
            
            $data['leave_status'] = $data['status'];
            $data['from_date']    = date($this->customlib->getSchoolDateFormat(), strtotime($data['from_date']));
            $data['to_date']      = date($this->customlib->getSchoolDateFormat(), strtotime($data['to_date']));
            $data['apply_date']   = date($this->customlib->getSchoolDateFormat(), strtotime($data['apply_date']));
        }
        echo json_encode($data);
    }

    public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }

    public function add()
    {
        $student_id = '';
        $this->form_validation->set_rules('class', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('apply_date', $this->lang->line('apply_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('from_date', $this->lang->line('from_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_date', $this->lang->line('to_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_status', $this->lang->line('leave_status'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('userfile', $this->lang->line('file'), 'callback_handle_upload[userfile]');

        $storage_array = "userfile";
        $this->form_validation->set_rules("validate_storage", $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if ($this->form_validation->run() == false) {

            $msg = array(
                'class'        => form_error('class'),
                'section'      => form_error('section'),
                'student'      => form_error('student'),
                'apply_date'   => form_error('apply_date'),
                'from_date'    => form_error('from_date'),
                'to_date'      => form_error('to_date'),
                'userfile'     => form_error('userfile'),
                'leave_status' => form_error('leave_status'),
                'validate_storage' => form_error('validate_storage'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {   
            $data = array(
                'apply_date'         => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('apply_date'))),
                'from_date'          => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('from_date'))),
                'to_date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('to_date'))),
                'student_session_id' => $this->input->post('student'),
                'reason'             => $this->input->post('message'),
                'request_type'       => '1',
                'status'             => $this->input->post('leave_status'),
            );
            
            if ($data['status'] != 0) {
                $data['approve_by'] = $this->customlib->getStaffID();
                $data['approve_date'] = date('Y-m-d');
            } 

            if ($this->input->post('leave_id') == '') {
                try {
                    //============saas============//
                    $total_documents_failed_size = 0;
                    $storage_array = ['userfile'];
                    $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially        
                    $img_name = $this->media_storage->fileupload("userfile", "./uploads/student_leavedocuments/");

                    if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully
                        $total_documents_failed_size += $this->media_storage->getTmpFileSize('userfile');  // get temp size of image because of image not uploaded 
                    }
                    if ($total_documents_failed_size > 0) {
                        $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
                    }
                    //============saas============//
                    $data['docs'] = $img_name;
                    $leave_id     = $this->apply_leave_model->add($data);
                    $data['id']   = $leave_id;
                } catch (Exception $e) {
                    // Print the exception message for debugging or logging purposes
                    $array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
                }  
            } else {
                try {
                    //============saas============//
                    $prev_file_size = 0;
                    $total_image_upload_size = 0;
                    $data['id'] = $this->input->post('leave_id');
                    $leave_list = $this->apply_leave_model->get($this->input->post('leave_id'));
                    
                    if (isset($_FILES["userfile"]) && $_FILES['userfile']['name'] != '' && (!empty($_FILES['userfile']['name']))) {
                        $prev_file_size = $this->media_storage->getUploadedFileSize($leave_list['docs'], 'uploads/student_leavedocuments');
                        $img_name = $this->media_storage->fileupload("userfile", "./uploads/student_leavedocuments/");
                        $img_name = $img_name;
                        if (!IsNullOrEmptyString($img_name)) {
                            $total_image_upload_size += $this->media_storage->getTmpFileSize('userfile');
                        }
                    } else {
                        $img_name = $leave_list['docs'];
                    }
                    $data['docs'] = $img_name;

                    if (isset($_FILES["userfile"]) && $_FILES['userfile']['name'] != '' && (!empty($_FILES['userfile']['name']))) {
                        if ($leave_list['docs'] != '') {
                            $this->media_storage->filedelete($leave_list['docs'], "uploads/student_leavedocuments");
                        }
                    }
           
                    //============
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
                    //============

                    $this->apply_leave_model->add($data);
                }catch (Exception $e) {
                        // Print the exception message for debugging or logging purposes
                        $array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
                } 
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function searchByClassSection($class_id, $student_id)
    {
        $section_id          = $_REQUEST['section_id'];
        $resultlist          = $this->student_model->searchByClassSection($class_id, $section_id);
        $data['resultlist']  = $resultlist;
        $data['select_id']   = $student_id;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('admin/approve_leave/_student_list', $data);
    }

    public function status()
    {
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $can_edit = 1;

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $myclasssubjects = $this->apply_leave_model->canApproveLeave($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'));
            $can_edit        = $myclasssubjects;
        }

        if ($can_edit == 0) {
            $msg   = array('leave' => $this->lang->line('not_authoried'));
            $array = array('status' => 0, 'error' => $this->lang->line('not_authoried'));
        } else {
            if ($_POST['status'] == 1) {
                $data['approve_by'] = $this->customlib->getStaffID();
            } else {
                $data['approve_by'] = 0;
            }

            $data['status'] = $_POST['status'];
            $this->db->where('id', $_POST['id']);
            $this->db->update('student_applyleave', $data);
            $msg   = array('leave' => $this->lang->line('success_message'));
            $array = array('status' => 1, 'success' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function remove_leave()
    {
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $can_edit = 1;

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $myclasssubjects = $this->apply_leave_model->canApproveLeave($userdata["id"], $this->input->post('class_id'), $this->input->post('section_id'));
            $can_edit        = $myclasssubjects;
        }

        if ($can_edit == 0) {
            $array = array('status' => 0, 'error' => $this->lang->line('not_authoried'));
        } else {
            $row = $this->apply_leave_model->get($_POST['id']);
            if ($row['docs'] != '') {
                $delete_file_size = $this->media_storage->getUploadedFileSize($row['docs'], 'uploads/student_leavedocuments');
                $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);        
                $this->media_storage->filedelete($row['docs'], "uploads/student_leavedocuments/");
            }

            $this->apply_leave_model->remove_leave($_POST['id']);
            $array = array('status' => 1, 'success' => $this->lang->line('delete_message'));
        }
        echo json_encode($array);
    }

    public function download($id)
    {
        $approve_leave = $this->apply_leave_model->get($id);
        $this->media_storage->filedownload($approve_leave['docs'], "uploads/student_leavedocuments");
    }

    public function handle_upload($str, $var)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }

                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }

            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;

    }

}
