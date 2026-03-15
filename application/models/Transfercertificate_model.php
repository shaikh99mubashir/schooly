<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transfercertificate_model extends MY_Model
{

    protected $current_session;
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function addformfields($record,$iscustomfield)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $this->db->where('name', $record['name']);
        $q = $this->db->get('transfer_certificate_fields');
        
        if ($q->num_rows() > 0) {
            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('transfer_certificate_fields', $record);
            $message   = UPDATE_RECORD_CONSTANT . " On  transfer_certificate_fields id " . $results->id;
            $action    = "Update";
            $record_id = $insert_id = $results->id;
            $this->log($message, $record_id, $action);
        } else {
            $transafercertificatefields=$this->getallfields();
            if($iscustomfield==1){
                $custon_field_position=count($transafercertificatefields)+1;
                $record['position']=$custon_field_position;
            }
            $this->db->insert('transfer_certificate_fields', $record);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On transfer_certificate_fields id " . $insert_id;
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
   
    public function editguardianfield($status)
    {
        $data = array('guardian_relation', 'guardian_name', 'guardian_phone', 'guardian_photo', 'guardian_occupation', 'guardian_email', 'guardian_address');
        foreach ($data as $value) {
            $this->db->query("update transfer_certificate_fields set status=" . $status . " where name='" . $value . "'  and is_default=1 ");
        }
    }

    public function getcustomfields()
    {
        $this->db->select("name");
        $this->db->from('custom_fields');
        $this->db->where("belong_to", 'transfer_certificate');
        $query  = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getdefaultfields()
    {
        $this->db->select('*');
        $this->db->from('transfer_certificate_fields');
        $this->db->where("is_default", '1');
        $this->db->where("is_active", '1');
        $this->db->order_by('transfer_certificate_fields.position', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getallfields()
    {
        $this->db->select('*');
        $this->db->from('transfer_certificate_fields');
        $this->db->where("is_active", '1');
        $this->db->order_by('transfer_certificate_fields.position', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
  
   public function getfieldstatus($fieldname)
    {
        $this->db->where('name', $fieldname);
        $this->db->select('status');
        $this->db->from('transfer_certificate_fields');
        $this->db->where("is_active", '1');
        $query  = $this->db->get();
        $result = $query->row_array();
        if(!empty($result)){
        return $result['status'];
        }
    }

    public function updateQueue($queueData)
    {
        $status = $this->db->update_batch("transfer_certificate_fields", $queueData, "id");
        return $status;
    }

    public function getfieldsposition($fieldname)
    {
        $this->db->where('name', $fieldname);
        $this->db->select('*');
        $this->db->from('transfer_certificate_fields');
        $this->db->where("is_active", '1');
        $query  = $this->db->get();
        $result = $query->row_array();
        if(!empty($result)){
        return $result['id'];
        }
    }


    public function get($id = null){
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->db->select('IFNULL(categories.category, "") as `category`,pickup_point.name as pickup_point_name,student_session.route_pickup_point_id,student_session.transport_fees,students.app_key,students.parent_app_key,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,vehicles.vehicle_model,vehicles.manufacture_year,vehicles.driver_licence,vehicles.vehicle_photo,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.note,students.religion,students.cast, school_houses.house_name,students.dob,students.current_address,students.previous_school,students.guardian_is,students.parent_id,  students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.father_pic ,students.height,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,users.id as user_id,students.dis_reason,students.dis_note,students.disable_at,students.about,students.designation')->from('students');
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
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
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


    public function get_settings()
    {
        $this->db->select('*');
        $this->db->from('transfer_certificate_settings');
        $this->db->order_by('transfer_certificate_settings.id', 'asc');
        $this->db->limit('1');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_transfer_certificate_no()
    {
        $this->db->select('*');
        $this->db->from('transfer_certificate_no');
        $this->db->order_by('transfer_certificate_no.id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get();
        $get_next_tc_no=$query->result_array();
        $get_settings=$this->get_settings();
        $tc_no_start = $get_settings[0]['tc_no_start'];

        if(empty($get_next_tc_no)){ 
            //if tc record table is empty set inital tc id
            if($tc_no_start!=0){
                $tc_no=$tc_no_start;
            }else{
                $tc_no=1;
            }
        }else if(!empty($get_next_tc_no)){
            if($get_next_tc_no[0]['tc_no']!=0 && $tc_no_start > $get_next_tc_no[0]['tc_no']){
                $tc_no=$tc_no_start;
            }else{
                $tc_no=$get_next_tc_no[0]['tc_no']+1;
            }
        }
        return $tc_no;
    }


    public function check_is_tc_exist($tc_no_start)
    {
        $this->db->select('*');
        $this->db->from('transfer_certificate_no');
        $this->db->where('tc_no', $tc_no_start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_setting($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('transfer_certificate_settings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On transfer_certificate_settings id " . $data['id'];
            $action = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('transfer_certificate_settings', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On transfer_certificate_settings id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);             
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function save_tc_details($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('transfer_certificate_no', $data);
            $message = UPDATE_RECORD_CONSTANT . " On transfer_certificate_no id " . $data['id'];
            $action = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('transfer_certificate_no', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On transfer_certificate_no id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);             
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }


     public function getByStudentSession($student_session_id)
    {
        $this->db->select('pickup_point.name as pickup_point_name,student_session.route_pickup_point_id,student_session.transport_fees,students.app_key,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.note, students.religion, students.cast, school_houses.house_name,students.dob ,students.current_address,students.previous_school,students.guardian_is,students.parent_id,          students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.guardian_name ,students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic ,students.guardian_pic ,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('transport_route', 'route_pickup_point.transport_route_id = transport_route.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('users.role', 'student');
        $this->db->where('student_session.id', $student_session_id);
        $query = $this->db->get();
        return $query->row_array();
    }


  








}
