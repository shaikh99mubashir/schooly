<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Resume_model extends MY_Model
{
	protected $current_session;
    public function __construct()
    {
        parent::__construct();       
        
    }

    public function get($id = null){
		$this->current_session = $this->setting_model->getCurrentSession();
        $this->db->select('pickup_point.name as pickup_point_name,student_session.route_pickup_point_id,student_session.transport_fees,students.app_key,students.parent_app_key,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,vehicles.vehicle_model,vehicles.manufacture_year,vehicles.driver_licence,vehicles.vehicle_photo,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.note,students.religion,students.cast, school_houses.house_name,students.dob,students.current_address,students.previous_school,students.guardian_is,students.parent_id,  students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.father_pic ,students.height,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,users.id as user_id,students.dis_reason,students.dis_note,students.disable_at,students.about,students.designation')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        if ($id != null) {
            $this->db->where("students.id",$id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function get_student_work_experience($student_id) {
        $this->db->where("student_id in ($student_id)");
        $query =    $this->db->get("student_work_experience");      
        return $query->result_array();
    }

    public function get_student_education($student_id) {
        $this->db->where("student_id in ($student_id)");
        $query =    $this->db->get("student_educational_details");
        return $query->result_array();
    }

    public function get_student_skills($student_id) {
        $this->db->where("student_id in ($student_id)");
        $query =    $this->db->get("student_skills_detail");
        return $query->result_array();
    }

    public function get_student_reference($student_id) {
        $this->db->where("student_id in ($student_id)");
        $query =    $this->db->get("student_refrence");
        return $query->result_array();
    }

     public function add_work_experience($data){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->insert('student_work_experience', $data);
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On student_work_experience id " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function add_education_details($data){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->insert('student_educational_details', $data);
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On student_educational_details id " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function add_skills_detail($data){  
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->insert('student_skills_detail', $data);
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On student_skills_detail id " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }    
    }

    public function add_reference_detail($data){   
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->insert('student_refrence', $data);
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On student refrence id " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }     
    }

    public function delete_work_experience($student_id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->where('student_id', $student_id);
        $this->db->delete('student_work_experience');

        $message = DELETE_RECORD_CONSTANT . " On student work experience student id " . $student_id;
        $action = "Delete";
        $record_id = $student_id;
        $this->log($message, $record_id, $action);

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction       
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function delete_student_educational($student_id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->where('student_id', $student_id);
        $this->db->delete('student_educational_details');

        $message = DELETE_RECORD_CONSTANT . " On student educational details student id " . $student_id;
        $action = "Delete";
        $record_id = $student_id;
        $this->log($message, $record_id, $action);

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction       
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function delete_referensh($student_id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_id', $student_id);
        $this->db->delete('student_refrence');
        $message = DELETE_RECORD_CONSTANT . " On student refrence student id " . $student_id;
        $action = "Delete";
        $record_id = $student_id;
        $this->log($message, $record_id, $action);

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction       
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function delete_student_skills($student_id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
      
        //=======================Code Start===========================
        $this->db->where('student_id', $student_id);
        $this->db->delete('student_skills_detail');
        $message = DELETE_RECORD_CONSTANT . " On student skills detail student id " . $student_id;
        $action = "Delete";
        $record_id = $student_id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction       
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function getformfields(){
        $this->db->select('*');
        $this->db->from('resume_settings_fields');
        $query = $this->db->get();
        return $query->result();
    }

    public function addformfields($record){

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->where('name', $record['name']);
        $q = $this->db->get('resume_settings_fields');

        if ($q->num_rows() > 0) {
            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('resume_settings_fields', $record);
            $message   = UPDATE_RECORD_CONSTANT . " On  resume_settings_fields id " . $results->id;
            $action    = "Update";
            $record_id = $insert_id = $results->id;
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('resume_settings_fields', $record);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On resume_settings_fields id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function editguardianfield($status){
        $data = array('guardian_relation', 'guardian_name', 'guardian_phone', 'guardian_photo', 'guardian_occupation', 'guardian_email', 'guardian_address');
        foreach ($data as $value) {
            $this->db->query("update resume_settings_fields set status=" . $status . " where name='" . $value . "'   ");
        }
    }

    public function getfieldstatus($fieldname){
        $this->db->where('name', $fieldname);
        $this->db->select('status');
        $this->db->from('resume_settings_fields');
        $query  = $this->db->get();
        $result = $query->row_array();
        if(!empty($result)){
        return $result['status'];
        }        
    }

	public function get_additional_field_status($fieldname){
        $this->db->where('name', $fieldname);
        $this->db->select('status');
        $this->db->from('resume_additional_fields_settings');
        $query  = $this->db->get();
        $result = $query->row_array();
        if(!empty($result)){
        return $result['status'];
        }        
    }

    public function add_additional_fields_setting($record){

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->where('name', $record['name']);
        $q = $this->db->get('resume_additional_fields_settings');

        if ($q->num_rows() > 0) {
            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('resume_additional_fields_settings', $record);
            $message   = UPDATE_RECORD_CONSTANT . " On  resume_additional_fields_settings id " . $results->id;
            $action    = "Update";
            $record_id = $insert_id = $results->id;
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('resume_additional_fields_settings', $record);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On resume_additional_fields_settings id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
    public function get_additional_fields(){
        $this->db->select('*');
        $this->db->from('resume_additional_fields_settings');
        $query = $this->db->get();
        return $query->result();
    }

}