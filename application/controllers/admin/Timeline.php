<?php

class Timeline extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('media_storage');
        $this->load->model('timeline_model');
		$this->load->library('SaasValidation');
    }
	
	public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }
	
    public function add()
    {
        $this->form_validation->set_rules('timeline_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_date', $this->lang->line('date'), 'trim|required|xss_clean');
		
		$storage_array = "timeline_doc"; // use comma for multiple files
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");	
		
        $this->form_validation->set_rules('timeline_doc', $this->lang->line('image'), 'callback_doc_handle_upload[timeline_doc]');

        $title = $this->input->post("timeline_title");

        if ($this->form_validation->run() == false) {

            $msg = array(
                'timeline_title' => form_error('timeline_title'),
                'timeline_date'  => form_error('timeline_date'),
                'timeline_doc'   => form_error('timeline_doc'),
                'validate_storage'   => form_error('validate_storage'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
			try {
				
				$total_documents_failed_size = 0;
                $storage_array = ['timeline_doc'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially 
				
				$visible_check = $this->input->post('visible_check');
				$timeline_date = $this->input->post('timeline_date');
				if (empty($visible_check)) {
					$visible = '';
				} else {
					$visible = 'yes';
				}              
					
				if (isset($_FILES["timeline_doc"]) && !empty($_FILES['timeline_doc']['name'])) {                    
					$img_name = $this->media_storage->fileupload("timeline_doc", "./uploads/student_timeline/"); 
					
					if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully

						$total_documents_failed_size += $this->media_storage->getTmpFileSize('documents');  // get temp size of image because of image not uploaded 
					}

					if ($total_documents_failed_size > 0) {
						$this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
					}
				
				} else {
					$img_name = '';
				}
	
				$timeline = array(
					'title'         => $this->input->post('timeline_title'),
					'description'   => $this->input->post('timeline_desc'),
					'timeline_date' => date('Y-m-d', $this->customlib->datetostrtotime($timeline_date)),
					'status'        => $visible,
					'date'          => date('Y-m-d'),
					'student_id'    => $this->input->post('student_id'),
					'created_student_id'    => '',
					'document'    => $img_name
				);
	
				$this->timeline_model->add($timeline);
				
				$msg   = $this->lang->line('success_message');
				$array = array('status' => 'success', 'error' => '', 'message' => $msg);
			} catch (Exception $e) {
                // Print the exception message for debugging or logging purposes
                 
				$array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
                 
            }	
        }
		
        echo json_encode($array);
    }

    public function add_staff_timeline()
    {
        $this->form_validation->set_rules('timeline_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_doc', $this->lang->line('image'), 'callback_doc_handle_upload[timeline_doc]');
        $title = $this->input->post("timeline_title");
		
		$storage_array = "timeline_doc"; // use comma for multiple files
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");
		
        if ($this->form_validation->run() == false) {

            $msg = array(
                'timeline_title' => form_error('timeline_title'),
                'timeline_date'  => form_error('timeline_date'),
                'timeline_doc'   => form_error('timeline_doc'),
                'validate_storage'   => form_error('validate_storage'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
			try {
				$total_documents_failed_size = 0;
				
				$storage_array = ['timeline_doc'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially 
				
				$visible_check = $this->input->post('visible_check');
				$timeline_date = $this->input->post('timeline_date');
				if (empty($visible_check)) {
					$visible = '';
				} else {
					$visible = $visible_check;
				}
				
				if (isset($_FILES["timeline_doc"]) && !empty($_FILES['timeline_doc']['name'])) {                    
					$img_name = $this->media_storage->fileupload("timeline_doc", "./uploads/staff_timeline/"); 
					
					if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully

						$total_documents_failed_size += $this->media_storage->getTmpFileSize('documents');  // get temp size of image because of image not uploaded 
					}

					if ($total_documents_failed_size > 0) {
						$this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
					}
				
				} else {
					 
					$img_name = '';
				}
					
				$timeline = array(
					'title'         => $this->input->post('timeline_title'),
					'timeline_date' => date('Y-m-d', $this->customlib->datetostrtotime($timeline_date)),
					'description'   => $this->input->post('timeline_desc'),
					'status'        => $visible,
					'date'          => date('Y-m-d'),
					'staff_id'      => $this->input->post('staff_id'),
					'document'      => $img_name);

				$id = $this->timeline_model->add_staff_timeline($timeline);				
				
				$msg   = $this->lang->line('success_message');
				$array = array('status' => 'success', 'error' => '', 'message' => $msg);
				
			} catch (Exception $e) {
                // Print the exception message for debugging or logging purposes
                 
				$array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
                 
			
			}
		}
        echo json_encode($array);
    }

    public function download($timeline_id)
    {
      $doc_details=$this->timeline_model->getstudentsingletimeline($timeline_id);
        $this->media_storage->filedownload($doc_details['document'], "./uploads/student_timeline/"); 
    }

    public function download_staff_timeline($timeline_id)
    {
        $doc_details=$this->timeline_model->getstaffsingletimeline($timeline_id);
        $this->media_storage->filedownload($doc_details['document'], "./uploads/staff_timeline/"); 

    }

    public function delete_timeline()
    {
        $id = $this->input->post('id');       
      
        $row = $this->timeline_model->getstudentsingletimeline($id);

        if (!empty($row) && !empty($row['document'])) {
           
            $delete_file_size = $this->media_storage->getUploadedFileSize($row['document'], 'uploads/student_timeline');

            if (!empty($delete_file_size) && $delete_file_size > 0) {              
                $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);
            }
           
            $this->media_storage->filedelete($row['document'], "uploads/student_timeline");
        }
       
        $this->timeline_model->delete_timeline($id);
        echo json_encode(array('status' => 'success', 'message' => $this->lang->line('delete_message')));
    }

    public function delete_staff_timeline($id)
    {
        if (!empty($id)) {
           
            $row = $this->timeline_model->getstaffsingletimeline($id);

            if (!empty($row) && !empty($row['document'])) {
               
                $delete_file_size = $this->media_storage->getUploadedFileSize($row['document'], 'uploads/staff_timeline');

                if (!empty($delete_file_size) && $delete_file_size > 0) {
                    $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);
                }
               
                $this->media_storage->filedelete($row['document'], "uploads/staff_timeline");
            }
           
            $this->timeline_model->delete_staff_timeline($id);
        }
    }

    public function staff_timeline($id = 77)
    {
        $userdata = $this->customlib->getUserData();
        $userid   = $userdata['id'];
        $status   = '';
        if ($userid == $id) {
            $status = 'yes';
        }

        $result = $this->timeline_model->getStaffTimeline($id, $status);
        $data["result"] = $result;
        $this->load->view("admin/staff_timeline", $data);
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

    public function getstudentsingletimeline()
    {
        $id                         = $this->input->post('id');
        $data['singletimelinelist'] = $this->timeline_model->getstudentsingletimeline($id);
        $page                       = $this->load->view("admin/_edit_student_timeline", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function editstudenttimeline()
    {
        $this->form_validation->set_rules('timeline_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_date', $this->lang->line('date'), 'trim|required|xss_clean');		
		
		$storage_array = "timeline_doc"; // use comma for multiple files
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");		
		
        $this->form_validation->set_rules('timeline_doc', $this->lang->line('image'), 'callback_doc_handle_upload[timeline_doc]');
		
        $title = $this->input->post("timeline_title");

        if ($this->form_validation->run() == false) {

            $msg = array(
                'timeline_title' => form_error('timeline_title'),
                'timeline_date'  => form_error('timeline_date'),
                'timeline_doc'   => form_error('timeline_doc'),
                'validate_storage'   => form_error('validate_storage'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
			try {
				$prev_file_size = 0;
                $total_image_upload_size = 0;
				
				$timeline_date = $this->input->post('timeline_date');
				$visible_check = $this->input->post('visible_check');
				
				if (empty($visible_check)) {
					$visible = '';
				} else {
					$visible = 'yes';
				}
	
				$timeline = array(
					'id'            => $this->input->post('id'),
					'title'         => $this->input->post('timeline_title'),
					'description'   => $this->input->post('timeline_desc'),
					'timeline_date' => date('Y-m-d', $this->customlib->datetostrtotime($timeline_date)),
					'status'        => $visible,
					'date'          => date('Y-m-d'),
					'student_id'    => $this->input->post('student_id'),
					'created_student_id'    => ''
					);                
	
				
				$gettimelinedata = $this->timeline_model->getstudentsingletimeline($this->input->post('id'));				
				
				if (isset($_FILES["timeline_doc"]) && $_FILES['timeline_doc']['name'] != '' && (!empty($_FILES['timeline_doc']['name']))) {
					
						$prev_file_size = $this->media_storage->getUploadedFileSize($gettimelinedata['document'], 'uploads/student_timeline');
	
						$img_name = $this->media_storage->fileupload("timeline_doc", "./uploads/student_timeline/");
	
						if (!IsNullOrEmptyString($img_name)) {
	
							$total_image_upload_size += $this->media_storage->getTmpFileSize('timeline_doc');
						}
						
				} else {					 
					$img_name = $gettimelinedata['document'];
				}				
				
	
				$timeline['document'] = $img_name;
				
				
				if (isset($_FILES["timeline_doc"]) && $_FILES['timeline_doc']['name'] != '' && (!empty($_FILES['timeline_doc']['name']))) {
					if ($gettimelinedata['document'] != '') {
						$this->media_storage->filedelete($gettimelinedata['document'], "uploads/school_income");
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
				
				
				$this->timeline_model->add($timeline);			
				
				
				$msg   = $this->lang->line('success_message');
				$array = array('status' => 'success', 'error' => '', 'message' => $msg);
			} catch (Exception $e) {
                // Handle any errors gracefully                
				$array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
                
			}
		}
        echo json_encode($array);
    }

    public function getstaffsingletimeline()
    {
        $id                         = $this->input->post('id');
        $data['singletimelinelist'] = $this->timeline_model->getstaffsingletimeline($id);        
        $page                       = $this->load->view("admin/_edit_staff_timeline", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function editstafftimeline()
    {
        $this->form_validation->set_rules('timeline_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_date', $this->lang->line('date'), 'trim|required|xss_clean');
		
		$storage_array = "timeline_doc"; // use comma for multiple files
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");	
		
        $this->form_validation->set_rules('timeline_doc', $this->lang->line('image'), 'callback_doc_handle_upload[timeline_doc]');
        $title = $this->input->post("timeline_title");

        if ($this->form_validation->run() == false) {

            $msg = array(
                'timeline_title' => form_error('timeline_title'),
                'timeline_date'  => form_error('timeline_date'),
                'timeline_doc'   => form_error('timeline_doc'),
                'validate_storage'   => form_error('validate_storage'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
			try {
				
				$prev_file_size = 0;
                $total_image_upload_size = 0;
				
				$timeline_date = $this->input->post('timeline_date');            
				$visible_check = $this->input->post('visible_check');
				 
				if (empty($visible_check)) {
					$visible = '';
				} else {
					$visible = 'yes';
				}
				$id = $this->input->post('id');
				$timeline = array(
					'id'            => $this->input->post('id'),
					'title'         => $this->input->post('timeline_title'),
					'description'   => $this->input->post('timeline_desc'),
					'timeline_date' => date('Y-m-d', $this->customlib->datetostrtotime($timeline_date)),
					'status'        => $visible,
					'date'          => date('Y-m-d'),
					'staff_id'      => $this->input->post('edit_staff_id'));
					
					
				$gettimelinedata = $this->timeline_model->getstaffsingletimeline($id); 
				
				if (isset($_FILES["timeline_doc"]) && $_FILES['timeline_doc']['name'] != '' && (!empty($_FILES['timeline_doc']['name']))) {
					
						$prev_file_size = $this->media_storage->getUploadedFileSize($gettimelinedata['document'], 'uploads/staff_timeline');
	
						$img_name = $this->media_storage->fileupload("timeline_doc", "./uploads/staff_timeline/");
	
						if (!IsNullOrEmptyString($img_name)) {
	
							$total_image_upload_size += $this->media_storage->getTmpFileSize('timeline_doc');
						}
						
				} else {
					$img_name = $gettimelinedata['document'];
				}				
	
				$timeline['document'] = $img_name;
				
				
				if (isset($_FILES["timeline_doc"]) && $_FILES['timeline_doc']['name'] != '' && (!empty($_FILES['timeline_doc']['name']))) {
					if ($gettimelinedata['document'] != '') {
						$this->media_storage->filedelete($gettimelinedata['document'], "uploads/staff_timeline");
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
				
				
				$this->timeline_model->add_staff_timeline($timeline);			
				
				$msg   = $this->lang->line('success_message');
				$array = array('status' => 'success', 'error' => '', 'message' => $msg);
			} catch (Exception $e) {
                             
				$array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
                
			}
        }
        echo json_encode($array);
    }

    public function doc_handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["timeline_doc"]) && !empty($_FILES['timeline_doc']['name'])) {

            $file_type = $_FILES["timeline_doc"]['type'];
            $file_size = $_FILES["timeline_doc"]["size"];
            $file_name = $_FILES["timeline_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['timeline_doc']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('doc_handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('doc_handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('doc_handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
				
				
            } else {
                $this->form_validation->set_message('doc_handle_upload', $this->lang->line('invalid_file_format_or_size'));
                return false;
            }

            return true;
        }
        return true;
    }
	
 


}
