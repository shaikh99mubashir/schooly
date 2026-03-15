<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Userlog extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $userdata = $this->customlib->getUserData();
		if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
			$class_section_array = $this->customlib->get_myClassSection();
			if (empty($class_section_array)) {
				access_denied();
			}
		}
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/userlog');
        $this->load->view('layout/header');
        $this->load->view('admin/userlog/userlogList');
        $this->load->view('layout/footer');
    }

    public function getDatatable()
    {
        $userlog = $this->userlog_model->getAllRecord();
        $userlog = json_decode($userlog);
        $dt_data = array();
        if (!empty($userlog->data)) {
            foreach ($userlog->data as $key => $value) {
                $row   = array();
                $row[] = $value->user;
                $row[] = ucfirst($value->role);
                $row[] = ($value->class_name != "") ? $value->class_name . "(" . $value->section_name . ")" : "";
                $row[] = $value->ipaddress;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->login_datetime);
                $row[] = $value->user_agent;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($userlog->draw),
            "recordsTotal"    => intval($userlog->recordsTotal),
            "recordsFiltered" => intval($userlog->recordsFiltered),
            "data"            => $dt_data,
        );

        echo json_encode($json_data);
    }

    public function getStudentDatatable()
    {
        $userlog = $this->userlog_model->getAllRecordByRole('student');
        $userlog = json_decode($userlog);

        $dt_data = array();
        if (!empty($userlog->data)) {
            foreach ($userlog->data as $key => $value) {

                $row   = array();
                $row[] = $value->user;
                $row[] = ucfirst($value->role);
                $row[] = ($value->class_name != "") ? $value->class_name . "(" . $value->section_name . ")" : "";
                $row[] = $value->ipaddress;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->login_datetime);
                $row[] = $value->user_agent;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($userlog->draw),
            "recordsTotal"    => intval($userlog->recordsTotal),
            "recordsFiltered" => intval($userlog->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function getParentDatatable()
    {
        $userlog = $this->userlog_model->getAllRecordByRole('parent');
        $userlog = json_decode($userlog);

        $dt_data = array();
        if (!empty($userlog->data)) {
            foreach ($userlog->data as $key => $value) {

                $row   = array();
                $row[] = $value->user;
                $row[] = ucfirst($value->role);
                $row[] = $value->ipaddress;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->login_datetime);
                $row[] = $value->user_agent;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($userlog->draw),
            "recordsTotal"    => intval($userlog->recordsTotal),
            "recordsFiltered" => intval($userlog->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function getStaffDatatable()
    {
        $userlog = $this->userlog_model->getAllRecordByStaff();
        $userlog = json_decode($userlog);

        $dt_data = array();
        if (!empty($userlog->data)) {
            foreach ($userlog->data as $key => $value) {

                $row   = array();
                $row[] = $value->user;
                $row[] = ucfirst($value->role);
                $row[] = $value->ipaddress;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->login_datetime);
                $row[] = $value->user_agent;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($userlog->draw),
            "recordsTotal"    => intval($userlog->recordsTotal),
            "recordsFiltered" => intval($userlog->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function delete()
    {
        $this->userlog_model->userlog_delete();
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($array));
    }

    public function getguestDatatable()
    {
        $userlog = $this->userlog_model->getAllRecordByRole('guest');
        $userlog = json_decode($userlog);

        $dt_data = array();
        if (!empty($userlog->data)) {
            foreach ($userlog->data as $key => $value) {

                $row   = array();
                $row[] = $value->user;
                $row[] = ucfirst($value->role);
                $row[] = $value->ipaddress;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->login_datetime);
                $row[] = $value->user_agent;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($userlog->draw),
            "recordsTotal"    => intval($userlog->recordsTotal),
            "recordsFiltered" => intval($userlog->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

}
