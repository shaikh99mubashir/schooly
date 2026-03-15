<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Receive extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('media_storage');
        $this->load->model("dispatch_model");
        $this->load->library('SaasValidation');
    }

    public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('postal_receive', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'admin/receive');
        $this->form_validation->set_rules('from_title', $this->lang->line('from_title'), 'required');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_upload[file]');

        $storage_array = "file"; // use comma for multiple files       
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");      
        
        if ($this->form_validation->run() == false) {
            $data['ReceiveList'] = $this->dispatch_model->receive_list();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/receiveview', $data);
            $this->load->view('layout/footer');
        } else {

            try {
                $total_documents_failed_size = 0;
                $storage_array = ['file'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially 

                $img_name = $this->media_storage->fileupload("file", "./uploads/front_office/dispatch_receive/");
            
                if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully

                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('file');  // get temp size of image because of image not uploaded 
                }
                if ($total_documents_failed_size > 0) {
                    $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
                }

            $dispatch = array(
                'reference_no' => $this->input->post('ref_no'),
                'to_title'     => $this->input->post('to_title'),
                'address'      => $this->input->post('address'),
                'note'         => $this->input->post('note'),
                'from_title'   => $this->input->post('from_title'),
                'date'         => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'type'         => 'receive',
                'image'        => $img_name,
            );

            $dispatch_id = $this->dispatch_model->insert('dispatch_receive', $dispatch); 

            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/receive');

             } catch (Exception $e) {
                // Print the exception message for debugging or logging purposes
                echo 'Error: ' . $e->getMessage();

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $e->getMessage() . '</div>');
            }  
        }
    }

    public function editreceive($id)
    {
        if (!$this->rbac->hasPrivilege('postal_receive', 'can_view')) {
            access_denied();
        }

        $this->form_validation->set_rules('from_title', $this->lang->line('from_title'), 'required');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_upload[file]');
        $data['receiveData'] = $this->dispatch_model->dis_rec_data($id, 'receive');
        
        $storage_array = "file";
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        if ($this->form_validation->run() == false) {
            $data['receiveList'] = $this->dispatch_model->receive_list();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/receiveedit', $data);
            $this->load->view('layout/footer');
        } else {

        try {
            $prev_file_size = 0;
            $total_image_upload_size = 0;

            $receive = array(
                'reference_no' => $this->input->post('ref_no'),
                'from_title'   => $this->input->post('from_title'),
                'address'      => $this->input->post('address'),
                'note'         => $this->input->post('note'),
                'to_title'     => $this->input->post('to_title'),
                'date'         => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'type'         => 'receive',
            );

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $prev_file_size = $this->media_storage->getUploadedFileSize($data['receiveData']['image'], 'uploads/front_office/dispatch_receive');
                $img_name = $this->media_storage->fileupload("file", "./uploads/front_office/dispatch_receive/");

                if (!IsNullOrEmptyString($img_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('file');
                }

            } else {
                $img_name = $data['receiveData']['image'];
            }

            $receive['image'] = $img_name;

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                $this->media_storage->filedelete($data['receiveData']['image'], "uploads/front_office/dispatch_receive/");
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


            $this->dispatch_model->update_dispatch('dispatch_receive', $id, 'receive', $receive);        
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/receive');
             } catch (Exception $e) {
                // Handle any errors gracefully
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error: ' . $e->getMessage() . '</div>');
                redirect('admin/receive');
            }  
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('postal_receive', 'can_delete')) {
            access_denied();
        }
        $row = $this->dispatch_model->dis_rec_data($id, 'receive');

        if ($row['image'] != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row['image'], 'uploads/front_office/dispatch_receive');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);      
            $this->media_storage->filedelete($row['image'], "uploads/front_office/dispatch_receive/");
        }

        $this->dispatch_model->delete($id);
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
    
    public function download($id)
    {
        $dispatch_list = $this->dispatch_model->dis_rec_data($id, 'receive');       
        $this->media_storage->filedownload($dispatch_list['image'], "./uploads/front_office/dispatch_receive");
        
    }

}
