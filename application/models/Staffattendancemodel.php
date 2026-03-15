<?php
class Staffattendancemodel extends MY_Model {

    protected $current_session;
    protected $current_date;
    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }
    
    public function addorUpdate($attendances)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
      
        if(!empty($attendances)){
            foreach ($attendances as $attendance_key => $attendance_value) {
                            
                $this->db->where('staff_id',  $attendance_value['staff_id']);
                $this->db->where('date', $attendance_value['date']);
                $query = $this->db->get('staff_attendance');
                
                if ($query->num_rows() > 0) {
                    // Record exists, update it
                    $this->db->where('id', $query->row()->id);
                    $this->db->update('staff_attendance', $attendance_value);
                } else {
                    // Record does not exist, insert a new one
                    $this->db->insert('staff_attendance', $attendance_value);
                }

                }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }    
    }

    public function get($id = null) {
        $this->db->select()->join("staff", "staff.id = staff_attendance.staff_id")->from('staff_attendance');
        $this->db->where("staff.is_active", 1);
        if ($id != null) {
            $this->db->where('staff_attendance.id', $id);
        } else {
            $this->db->order_by('staff_attendance.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getUserType() {
        $query = $this->db->query("select distinct user_type from staff where is_active = 1");
        return $query->result_array();
    }

    public function searchAttendenceUserTypeWithMode($user_type, $date,$mode) {
        $condition = '';

        if ($mode == 1) {
            $condition = " and staff_attendance.biometric_attendence= 0 and staff_attendance.qrcode_attendance=0";
        } elseif ($mode == 2) {
            $condition = " and staff_attendance.biometric_attendence= 0 and staff_attendance.qrcode_attendance=1";
        } elseif ($mode == 3) {
            $condition = " and staff_attendance.biometric_attendence= 1 and staff_attendance.qrcode_attendance=0";
        }

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {                 
                $condition = " and roles.id != 7";
            } 
        }
        
        if ($user_type == "select") { 
            $query = $this->db->query("select staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id, staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style  from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.is_active = 1 $condition order by staff_attendance.created_at asc");
        } else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id ,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 $condition order by staff_attendance.created_at asc");
            
        }
        return $query->result_array();
    }

    public function searchAttendenceUserType($user_type, $date) {
        $condition = '';
        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {                 
                $condition = " and roles.id != 7";
            } 
        }
        
        if ($user_type == "select") { 

            $query = $this->db->query("select staff_attendance.out_time,staff_attendance.in_time,staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,roles.id as role_id,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id, staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style  from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.is_active = 1 $condition");
        } else {
            $query = $this->db->query("select  staff_attendance.out_time,staff_attendance.in_time,staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,roles.id as role_id,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id ,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 $condition");            
        }
        return $query->result_array();
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_attendance', $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff attendance id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_attendance', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff attendance id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getStaffAttendanceType() {
        $query = $this->db->select('*')->where("is_active", 'yes')->get("staff_attendance_type");
        return $query->result_array();
    }

    public function searchAttendanceReport($user_type, $date) {

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
             
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            $condition = '';
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $condition = "and staff_roles.role_id != 7";       
            } 
        }
        
        if ($user_type == "select") {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where staff.is_active = 1 $condition");
        } else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff  left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id  where roles.name = '" . $user_type . "' and staff.is_active = 1 $condition");
        }

        return $query->result_array();
    }

    public function attendanceYearCount() {
        $query = $this->db->select("distinct year(date) as year")->get("staff_attendance");
        return $query->result_array();
    }

    public function searchStaffattendance($date, $staff_id, $active_staff = true) {

        $sql = "select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.id = " . $this->db->escape($staff_id);
        if ($active_staff || !isset($active_staff)) {
            $sql .= " and staff.is_active = 1";
        }
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function onlineattendence($data, $role_id)
    {
        $status = false;
        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('date', $data['date']);
        $q = $this->db->get('staff_attendance');
        $time = date('H:i:s');
        if ($q->num_rows() == 0) {
            $attendance_range = $this->staffAttendaceSetting_model->getAttendanceTypeByRole($role_id, $time);
            if ($attendance_range) {
                $data['staff_attendance_type_id'] = $attendance_range->staff_attendence_type_id;
                $data['in_time'] = $time;

                $this->db->insert('staff_attendance', $data);
                $status = 1; //for successfully saving

            } else {
                $status = 2; //for range not exist to save
            }
        } else {

            $return_result = $q->row();
            if (!IsNullOrEmptyString($return_result->in_time)  && !IsNullOrEmptyString($return_result->out_time)) {

                $status = 0;
            } else {
                $updateArr = ['out_time' => $time];
                $return_attendance_type =  $this->staff_schedule_hours($return_result->staff_id, $return_result->in_time);
                if ($return_attendance_type) {
                    $updateArr['staff_attendance_type_id'] = $return_attendance_type;
                }


                $this->db->where('id', $return_result->id);
                $this->db->update('staff_attendance', $updateArr);
                $status = 1;
            }
        }
        return $status;
    }

    public function staff_schedule_hours($staff_id, $in_time)
    {
        $date = date('Y-m-d');
        $sql    = "SELECT staff_roles.role_id,staff_attendence_schedules.staff_attendence_type_id as staff_attendence_schedule_staff_attendence_type_id,staff_attendence_schedules.entry_time_from,staff_attendence_schedules.entry_time_to,staff_attendence_schedules.total_institute_hour FROM `staff` INNER JOIN staff_roles on staff_roles.staff_id=staff.id INNER join staff_attendence_schedules on staff_attendence_schedules.role_id = staff_roles.role_id WHERE staff.id=" . $this->db->escape($staff_id);     

        $current_time = date('H:i:s');
        $query  = $this->db->query($sql);
        if ($query->num_rows() > 0) {

            $return_attedance_type = false;
            $time_entry_seconds = strtotime("1970-01-01 $in_time UTC");
            $time_current_seconds = strtotime("1970-01-01 $current_time UTC");
            $total_spend_time = $time_current_seconds - $time_entry_seconds;

            $result = $query->result();
            $find_array = array();           

            foreach ($result as $result_key => $result_value) {

                $entry_time_from_seconds = strtotime("1970-01-01 $result_value->entry_time_from UTC");
                $entry_time_to_seconds = strtotime("1970-01-01 $result_value->entry_time_to UTC");

                if ($entry_time_from_seconds  <= $time_entry_seconds && $entry_time_to_seconds >= $time_entry_seconds) {

                    $find_array[] = array(
                        'staff_attendence_type_id' => $result_value->staff_attendence_schedule_staff_attendence_type_id,
                        'time_schedule_seconds' => strtotime("1970-01-01 $result_value->total_institute_hour UTC")
                    );
                }
            }
            if (count($find_array) > 1) {

                if ($total_spend_time < $find_array[0]['time_schedule_seconds'] && $total_spend_time > $find_array[1]['time_schedule_seconds']) {
                    $return_attedance_type = $find_array[1]['staff_attendence_type_id'];
                }
            }

            return $return_attedance_type;
        } else {
            return false;
        }
    }


}
