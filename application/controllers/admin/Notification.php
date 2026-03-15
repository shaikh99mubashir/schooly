<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notification extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('mailer');
        $this->load->library('SaasValidation');

    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('notice_board', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'notification/index');
        $data['title']            = 'Notifications';
        $data['notificationlist'] = $this->notification_model->get();

        $userdata           = $this->customlib->getUserData();     
        $data['user_id']    = $userdata["id"];
        $this->load->view('layout/header', $data);
        $this->load->view('admin/notification/notificationList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $userdata         = $this->customlib->getUserData();
        $user_id          = $userdata["id"];
        $usernotification = $this->notification_model->get($id);
        if ((!$this->rbac->hasPrivilege('notice_board', 'can_edit'))) {
            if ($usernotification['created_id'] != $user_id) {
                access_denied();
            }
        }

        if($usernotification['attachment'] != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($usernotification['attachment'], 'uploads/notice_board_images');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);  
            $this->media_storage->filedelete($usernotification['attachment'], "uploads/notice_board_images/");
        }

        $this->notification_model->remove($id);
        // unlink("./uploads/notice_board_images/" . $usernotification['attachment']);
        redirect('admin/notification');
    }

    public function setting()
    {
        if (!$this->rbac->hasPrivilege('notification_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'notification/setting');
        $data                     = array();
        $data['title']            = 'Email Config List';
        $notificationlist         = $this->notificationsetting_model->get();
        $data['notificationlist'] = $notificationlist;
        $this->form_validation->set_rules('email_type', $this->lang->line('email_type'), 'required');
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $ids          = $this->input->post('ids');
            $update_array = array();
            foreach ($ids as $id_key => $id_value) {
                $array = array(
                    'id'                    => $id_value,
                    'is_mail'               => 0,
                    'is_sms'                => 0,
					'is_whatsapp'           => 0,
                    'is_student_recipient'  => 0,
                    'is_guardian_recipient' => 0,
                    'is_staff_recipient'    => 0,
                );
                $mail               = $this->input->post('mail_' . $id_value);
                $sms                = $this->input->post('sms_' . $id_value);
				$whatsapp           = $this->input->post('whatsapp_' . $id_value);
                $notification       = $this->input->post('notification_' . $id_value);
                $student_recipient  = $this->input->post('student_recipient_' . $id_value);
                $guardian_recipient = $this->input->post('guardian_recipient_' . $id_value);
                $staff_recipient    = $this->input->post('staff_recipient_' . $id_value);
                if (isset($mail)) {
                    $array['is_mail'] = $mail;
                }
                if (isset($sms)) {
                    $array['is_sms'] = $sms;
                }
				if (isset($whatsapp)) {
                    $array['is_whatsapp'] = $whatsapp;
                }
                if (isset($notification)) {
                    $array['is_notification'] = $notification;
                } else {
                    $array['is_notification'] = 0;
                }
                if (isset($student_recipient)) {
                    $array['is_student_recipient'] = $student_recipient;
                }
                if (isset($guardian_recipient)) {
                    $array['is_guardian_recipient'] = $guardian_recipient;
                }
                if (isset($staff_recipient)) {
                    $array['is_staff_recipient'] = $staff_recipient;
                }

                $update_array[] = $array;
            }

            $this->notificationsetting_model->updatebatch($update_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/notification/setting');
        }

        $data['title'] = 'Email Config List';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/notification/setting', $data);
        $this->load->view('layout/footer', $data);
    }

    public function read()
    {
        $array           = array('status' => "fail", 'msg' => $this->lang->line('something_went_wrong'));
        $notification_id = $this->input->post('notice');
        if ($notification_id != "") {
            $staffid = $this->customlib->getStaffID();
            $data    = $this->notification_model->updateStatusforStaff($notification_id, $staffid);
            $array   = array('status' => "success", 'data' => $data, 'msg' => $this->lang->line('update_message'));
        }

        echo json_encode($array);
    }

    public function gettemplate()
    {
        $id             = $this->input->post('id');
        $data['record'] = $this->notificationsetting_model->get($id);

        $template = $this->load->view('admin/notification/gettemplate', $data, true);
        $response = array('status' => 1, 'template' => $template);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function savetemplate()
    {
        $response = array();
        $this->form_validation->set_rules('temp_id', $this->lang->line('template_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('template_message', $this->lang->line('template_message'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('template_subject', $this->lang->line('subject'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'temp_id'          => form_error('temp_id'),
                'template_message' => form_error('template_message'),
                'template_subject' => form_error('template_subject'),
            );
            $response = array('status' => 0, 'error' => $data);
        } else {

            $data_update = array(
                'id'          => $this->input->post('temp_id'),
                'template_id' => $this->input->post('template_id'),
                'whatsapp_template_id' => $this->input->post('whatsapp_template_id'),
                'template'    =>  htmlspecialchars_decode($this->input->post('template_message', false)), //$this->input->post('template_message'),
                'subject'     => $this->input->post('template_subject'),
            );

            $this->notificationsetting_model->update($data_update);
            $response = array('status' => 1, 'message' => $this->lang->line('update_message'));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['file']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function download($id)
    {       
        $notification = $this->notification_model->notification($id);
        $this->media_storage->filedownload($notification['attachment'], "uploads/notice_board_images");       
    }

    public function notification()
    {
        $message_id       = $this->input->post('message_id');
        $notificationlist = $this->notification_model->get($message_id);
        $data['notificationlist'] = $notificationlist;
       
        $userdata        = $this->customlib->getUserData();
        $role_id         = $userdata["role_id"];
        $user_id         = $userdata["id"];
       
        $created_by_name = $this->staff_model->get($notificationlist["created_id"]);
        $roles           = $notificationlist["roles"];

        $staff_id = '';
        if ($created_by_name["employee_id"] != '') {
            $staff_id = ' (' . $created_by_name["employee_id"] . ')';
        }

        $arr = explode(",", $roles); 
       
        $data['notificationlist']["role_name"]      = $this->notification_model->getRole($arr);           
                
        if(!empty($created_by_name["name"])){
            $data['notificationlist']["createdby_name"] =  "<li><i class='fa fa-user pr-1'></i>".$this->lang->line('created_by').':'.$created_by_name["name"] . " " . $created_by_name["surname"] . $staff_id."</li>";
        }else{
            $data['notificationlist']["createdby_name"] = ''; 
        }          

        $page = $this->load->view('admin/notification/_notification', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }


    //*** send notice to start ***//
    public function multihandle_upload($str, $var){

        if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            $image_validate               = $this->config->item('file_validate');
            $result                       = $this->filetype_model->get();
            $file_size_shoud_be_less_than = array();
            $file_type_not_allowed        = array();
            foreach ($_FILES['file']['name'] as $key => $files_value) {
                $file_type         = $_FILES['file']['type'][$key];
                $file_size         = $_FILES['file']["size"][$key];
                $file_name         = $_FILES['file']['name'][$key];
                $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
                $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
                $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $message           = "";
                if ($files = filesize($_FILES['file']['tmp_name'][$key])) {

                    if (!in_array($file_type, $allowed_mime_type)) {
                        $file_type_not_allowed[] = 0;
                    }
                    if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                        $file_type_not_allowed[] = 0;
                    }
                    if ($file_size > $result->file_size) {
                        $file_size_shoud_be_less_than[] = 0;
                    }
                } else {

                }
            }

            if (in_array(0, $file_type_not_allowed)) {
                $this->form_validation->set_message('multihandle_upload', $this->lang->line('file_type_not_allowed'));
                $file_type_not_allowed[] = 0;
                return false;
            }

            if (in_array(0, $file_size_shoud_be_less_than)) {
                $this->form_validation->set_message('multihandle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }
        }
        return true;
    }


 public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }
    

    public function add()
    {
        if (!$this->rbac->hasPrivilege('notice_board', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'notification/index');
        $data['title']              =   'Add Notification';
        $data['title_list']         =   'Notification List';
        $data['roles']              =    $this->role_model->get();
        $send_attachments           =    [];
        $template_id                =    $this->input->post('template_id');
        $data['mail']               =    $mail           =    $this->input->post('mail');
        $data['sms']                =    $sms            =    $this->input->post('sms');
        $data['mobile_notification']       =    $mobile_notification   =    $this->input->post('mobile_notification');
        $userlisting                =    $this->input->post('visible[]');
        $message                    =    $this->input->post('message');
        $message_title              =    $this->input->post('title');

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('notice_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('publish_date', $this->lang->line('publish_on'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('visible[]', $this->lang->line('message_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_multihandle_upload[file]');


        $storage_array = "file"; // use comma for multiple files       
        $this->form_validation->set_rules($storage_array, $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");      



        if($this->input->post('sms')==1){
            // $this->form_validation->set_rules('template_id', $this->lang->line('template_id'), 'trim|required|xss_clean');
            $data['sms_validation']      = 1;
        }else{
              $data['sms_validation']    = 0;
        }
        if ($this->form_validation->run() == false) {

        } else {
            
        //============
        try {
            
            $storage_array = ['file'];
            if (isset($_FILES['file']['name'][0]) && !empty($_FILES['file']['name'][0])) {
                    $total_documents_failed_size = 0;
                    $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially 
                    $img_name = $this->media_storage->fileupload("file", "./uploads/notice_board_images/");
                    
                    if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully
                        $total_documents_failed_size += $this->media_storage->getTmpFileSize('file');  // get temp size of image because of image not uploaded 
                    }

                    if ($total_documents_failed_size > 0) {
                        $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
                    }

                    $file_name = $_FILES['file']['name'][0];
                    $send_attachments[] = array('directory' => 'uploads/notice_board_images/', 'attachment' => $img_name, 'attachment_name' => $file_name);
            }
            //===================

            foreach($userlisting as $users_key => $users_value) {
                if ($users_value == "student" || $users_value == "parent") {
                    $userdata_array = $this->student_model->getStudents();
                }
                if ($users_value == "student") {
                    if (!empty($userdata_array)) {
                        foreach ($userdata_array as $student_key => $student_value) {
                            $array = array(
                                'user_id'  => $student_value['id'],
                                'email'    => $student_value['email'],
                                'mobileno' => $student_value['mobileno'],
                                'app_key'  => $student_value['app_key'], //app key for mobile app notification
                                'role'     => 'student'
                            );
                            $user_array[] = $array;
                        }
                    }
                }else if ($users_value == "parent"){
                    if (!empty($userdata_array)) {
                        foreach ($userdata_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'   => $parent_value['id'],
                                'email'     => $parent_value['guardian_email'],
                                'mobileno'  => $parent_value['guardian_phone'],
                                'app_key'   => $parent_value['parent_app_key'], //app key for mobile app notification
                                'role'      => 'parent'
                            );
                            $user_array[] = $array;
                        }
                    }
                }else if (is_numeric($users_value)){
                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                                'role'     => 'staff'
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }
            //send notification in mail
            if(isset($mail) && $mail==1){
            if (!empty($user_array)) {
                if (!empty($this->mail_config)) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['email'] != "") {
                            $this->mailer->compose_mail($user_mail_value['email'], $message_title, $message, $send_attachments);
                        }
                    }
                }
            }
            }
            //send notification in sms 
            if(isset($sms) && $sms==1){
            if (!empty($user_array)) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], $message, $template_id, "");
                        }
                    }
            }
            }
            //mobile app notification will only send to the students
            if(isset($mobile_notification) && $mobile_notification==1){
            if (!empty($user_array)) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        $push_array = array(
                            'title' => $message_title,
                            'body'  => $message,
                        );
                        if($user_mail_value['role']=='student' || $user_mail_value['role']=='parent'){                         
                            if($user_mail_value['app_key'] != "") {
                                $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                            }
                        }
                    }
            }
            }
            //code end

            //***old code as it is for send notification on web application notice board***//
            $userdata    = $this->customlib->getUserData();
            $student     = "No";
            $staff       = "No";
            $parent      = "No";
            $staff_roles = array();
            $visible     = $this->input->post('visible');
            
            if (!in_array(7, $visible)){
                $staff_roles[] = array('role_id' => 7, 'send_notification_id' => '');
                $staff         = "Yes";
            }
           
            foreach ($visible as $key => $value) {
                if ($value == "student") {
                    $student = "Yes";
                } else if ($value == "parent") {
                    $parent = "Yes";
                } else if (is_numeric($value)) {
                    $staff_roles[] = array('role_id' => $value, 'send_notification_id' => '');
                    $staff         = "Yes";
                }
            }
 
            $data = array(
                'message'         => $this->input->post('message'),
                'title'           => $this->input->post('title'),
                'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'created_by'      => $userdata["user_type"],
                'created_id'      => $this->customlib->getStaffID(),
                'visible_student' => $student,
                'visible_staff'   => $staff,
                'visible_parent'  => $parent,
                'publish_date'    => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('publish_date'))),
                'attachment'    => $img_name,
            );

            $id = $this->notification_model->insertBatch($data, $staff_roles);        
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/notification/index');

        }catch (Exception $e) {
            // Print the exception message for debugging or logging purposes
            echo 'Error: ' . $e->getMessage();

             $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $e->getMessage() . '</div>');
        }

        }
        $exam_result                    = $this->exam_model->get();
        $data['examlist']               = $exam_result;
        $data['superadmin_restriction'] = $this->customlib->superadmin_visible();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/notification/notificationAdd', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit($id)
    {
        $userdata         = $this->customlib->getUserData();
        $user_id          = $userdata["id"];
        $usernotification = $this->notification_model->get($id);
        if ((!$this->rbac->hasPrivilege('notice_board', 'can_edit'))) {
            if ($usernotification['created_id'] != $user_id) {
                access_denied();
            }
        }
        $data['id']   = $id;
        $notification = $this->notification_model->get($id);

        $data['notification']       =   $notification;
        $data['roles']              =   $this->role_model->get();
        $data['title']              =   'Edit Notification';
        $data['title_list']         =   'Notification List';
        $send_attachments           =    [];
        $template_id                =    $this->input->post('template_id');
        $data['mail']               =    $mail           =    $this->input->post('mail');
        $data['sms']                =    $sms            =    $this->input->post('sms');
        $data['mobile_notification']=    $mobile_notification   =    $this->input->post('notification');
        $userlisting                =    $this->input->post('visible[]');
        $message                    =    $this->input->post('message');
        $message_title              =    $this->input->post('title');

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('notice_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('publish_date', $this->lang->line('publish_on'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('visible[]', $this->lang->line('message_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_multihandle_upload[file]');

        $storage_array = "file";
        $this->form_validation->set_rules($storage_array , $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if($this->input->post('sms')==1){
            // $this->form_validation->set_rules('template_id', $this->lang->line('template_id'), 'trim|required|xss_clean');
            $data['sms_validation']      = 1;
        }else{
              $data['sms_validation']    = 0;
        }

        if ($this->form_validation->run() == false) {

        } else {
           
        try {
            
            $prev_file_size = 0;
            $total_image_upload_size = 0;

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
              if (isset($_FILES['file']['name'][0]) && !empty($_FILES['file']['name'][0])) {

                    $prev_file_size = $this->media_storage->getUploadedFileSize($notification['attachment'], 'uploads/notice_board_images');
                    
                    $img_name = $this->media_storage->fileupload("file", "./uploads/notice_board_images/");

                    if (!IsNullOrEmptyString($img_name)) {
                        $total_image_upload_size += $this->media_storage->getTmpFileSize('file');
                    }

                    $file_name = $_FILES['file']['name'][$key];
            }
            } else {
                $file_name = $notification['attachment_name'];
                $img_name = $notification['attachment'];
            }

            $send_attachments[] = array('directory' => 'uploads/notice_board_images/', 'attachment' => $img_name, 'attachment_name' => $file_name);


            if(isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                $this->media_storage->filedelete($notification['attachment'], "uploads/notice_board_images/");
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

            //===================================
            foreach($userlisting as $users_key => $users_value) {
                if ($users_value == "student" || $users_value == "parent") {
                    $userdata_array = $this->student_model->getStudents();
                }
                if ($users_value == "student") {
                    if (!empty($userdata_array)) {
                        foreach ($userdata_array as $student_key => $student_value) {
                            $array = array(
                                'user_id'  => $student_value['id'],
                                'email'    => $student_value['email'],
                                'mobileno' => $student_value['mobileno'],
                                'app_key'  => $student_value['app_key'], //app key for mobile app notification
                                'role'     => 'student'
                            );
                            $user_array[] = $array;
                        }
                    }
                }else if ($users_value == "parent"){
                    if (!empty($userdata_array)) {
                        foreach ($userdata_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'   => $parent_value['id'],
                                'email'     => $parent_value['guardian_email'],
                                'mobileno'  => $parent_value['guardian_phone'],
                                'app_key'   => $parent_value['parent_app_key'], //app key for mobile app notification
                                'role'      => 'parent'
                            );
                            $user_array[] = $array;
                        }
                    }
                }else if (is_numeric($users_value)){
                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                                'role'     => 'staff'
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }

            //send notification in mail
            if(isset($mail) && $mail==1){
                if (!empty($user_array)) {
                    if (!empty($this->mail_config)) {
                        foreach ($user_array as $user_mail_key => $user_mail_value) {
                            if ($user_mail_value['email'] != "") {
                                $this->mailer->compose_mail($user_mail_value['email'], $message_title, $message, $send_attachments);
                            }
                        }
                    }
                }
            }
            //send notification in sms 
            if(isset($sms) && $sms==1){
                if (!empty($user_array)) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], $message, $template_id, "");
                        }
                    }
                }
            }
            //mobile app notification will only send to the students
            if(isset($mobile_notification) && $mobile_notification==1){
                if (!empty($user_array)) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        $push_array = array(
                            'title' => $message_title,
                            'body'  => $message,
                        );
                        if($user_mail_value['role']=='student' || $user_mail_value['role']=='parent'){                         
                            if($user_mail_value['app_key'] != "") {
                                $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                            }
                        }
                    }
                }
            }
            //code end

            //****old code as it is for sending notification on web application no  changes perform on below code****//
            $userdata    = $this->customlib->getUserData();
            $student     = "No";
            $staff       = "No";
            $parent      = "No";
            $prev_roles  = $this->input->post('prev_roles');
            $visible     = $this->input->post('visible');
            $staff_roles = array();
            $inst_staff  = array();
            
            if (!in_array(7, $visible))
            {
                $staff_roles[] = array('role_id' => 7, 'send_notification_id' => '');
                $staff         = "Yes";
            }
            
            foreach ($visible as $key => $value) {

                if ($value == "student") {
                    $student = "Yes";
                } else if ($value == "parent") {
                    $parent = "Yes";
                } else if (is_numeric($value)) {
                    $inst_staff[]  = $value;
                    $staff_roles[] = array('role_id' => $value, 'send_notification_id' => '');
                    $staff         = "Yes";
                }
            }

            $to_be_del    = array_diff($prev_roles, $inst_staff);
            $to_be_insert = array_diff($inst_staff, $prev_roles);
            $insert       = array();

            if (!empty($to_be_insert)) {

                foreach ($to_be_insert as $to_insert_key => $to_insert_value) {
                    $insert[] = array('role_id' => $to_insert_value, 'send_notification_id' => '');
                }
            }      
            
            $data = array(
                'id'              => $id,
                'message'         => $this->input->post('message'),
                'title'           => $this->input->post('title'),
                'date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'created_by'      => $userdata["user_type"],
                'created_id'      => $this->customlib->getStaffID(),
                'visible_student' => $student,
                'visible_staff'   => $staff,
                'visible_parent'  => $parent,
                'publish_date'    => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('publish_date'))),    
                'attachment'      => $img_name  
            );          
         
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                if ($notification['attachment'] != '') {
                    $this->media_storage->filedelete($notification['attachment'], "uploads/school_income");
                }
            }
            
            $this->notification_model->insertBatch($data, $insert, $to_be_del);            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/notification/index');

            } catch (Exception $e) {
                // Handle any errors gracefully
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error: ' . $e->getMessage() . '</div>');
                redirect('admin/notification/index');
            }  
        }
        $exam_result      = $this->exam_model->get();
        $data['examlist'] = $exam_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/notification/notificationEdit', $data);
        $this->load->view('layout/footer', $data);
    }

	public function view_template()
    {
        $id             = $this->input->post('id');
        $record = $this->notificationsetting_model->get($id);		
		
		$data['subject'] = $record->subject;
		$data['body'] = $record->template;	
		$data['email_header'] = $this->setting_model->get_email_header();
        $data['email_footer'] = $this->setting_model->get_email_footer();		
		
        $template = $this->load->view('admin/notification/_emailtemplatedesign', $data, true);
        $response = array('status' => 1, 'template' => $template);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function delete_notice_board_log() 
    { 
        $notificationOldLogs=$this->notification_model->getPastNoticeLogs();
        foreach($notificationOldLogs as $key=>$value){
        if($value['attachment'] != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($value['attachment'], 'uploads/notice_board_images');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);  
            $this->media_storage->filedelete($value['attachment'], "uploads/notice_board_images/");
        }
        $this->notification_model->remove($value['id']);
        }
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('record_deleted_successfully'));  
        $this->output->set_content_type('application/json')->set_output(json_encode($array));  
    }





}
