<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Stuattendence_model extends MY_Model
{

    protected $current_session;
    protected $current_date;
    public function __construct()
    {
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
                            
                $this->db->where('student_session_id',  $attendance_value['student_session_id']);
                $this->db->where('date', $attendance_value['date']);
                $query = $this->db->get('student_attendences');
                
                if ($query->num_rows() > 0) {
                    // Record exists, update it
                    $this->db->where('id', $query->row()->id);
                    $this->db->update('student_attendences', $attendance_value);
                } else {
                    // Record does not exist, insert a new one
                    $this->db->insert('student_attendences', $attendance_value);
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

    public function batch_insert($data)
    {
        $this->db->insert_batch('student_attendences', $data);
    }

    public function get($id = null)
    {
        $this->db->select()->from('student_attendences');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function onlineattendence($data,$class_section_id)
    {
    
            $status =false;
            $this->db->where('student_session_id', $data['student_session_id']);
            $this->db->where('date', $data['date']);
            $q = $this->db->get('student_attendences');
            $time = date('H:i:s');
    
            if ($q->num_rows() == 0) {
 
                    $attendance_range = $this->studentAttendaceSetting_model->getAttendanceTypeByClassAndSectionTime($class_section_id, $time);
                    if ($attendance_range) {
                        $data['attendence_type_id'] = $attendance_range->attendence_type_id;
                        $data['in_time'] = $time;
    
                        $this->db->insert('student_attendences', $data);
                        $status = 1; //for successfully saving                        

                        $present_student_list['student_sessions_id'][$data['student_session_id']] = ($data['student_session_id']);
                        $present_student_list['in_time'][$data['student_session_id']] =$time;
                        $this->mailsmsconf->mailsms('student_present_attendence', $present_student_list, $data['date']);
    
                    } else {
                        $status = 2; //for range not exist to save
    
                    }
              
            } else {
                $return_result = $q->row();
                if (!IsNullOrEmptyString($return_result->in_time)  && !IsNullOrEmptyString($return_result->out_time)) {    
                    $status = 0; //if both attendance is exist for the day
                } else {    
    
                    $updateArr = ['out_time' => $time];
                    $return_attendance_type =  $this->student_schedule_hours($class_section_id, $return_result->in_time);
                    if ($return_attendance_type) {
                        $updateArr['attendence_type_id'] = $return_attendance_type;
                    }
    
                    $this->db->where('id', $return_result->id);
                    $this->db->update('student_attendences', $updateArr);
                    $status = 1;
                }
            }
        return $status;
    }
	
	public function student_schedule_hours($class_section_id, $in_time)
    {
        $date = date('Y-m-d');
        $sql    = "SELECT * FROM `student_attendence_schedules`WHERE class_section_id=" . $this->db->escape($class_section_id);      

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
                        'attendence_type_id' => $result_value->attendence_type_id,
                        'time_schedule_seconds' => strtotime("1970-01-01 $result_value->total_institute_hour UTC")
                    );
                }
            }

            if (count($find_array) > 1) {
                if ($total_spend_time < $find_array[0]['time_schedule_seconds'] && $total_spend_time > $find_array[1]['time_schedule_seconds']) {
                    $return_attedance_type = $find_array[1]['attendence_type_id'];
                }
            }

            return $return_attedance_type;
        } else {
            return false;
        }
    }

    public function add($insert_array, $update_array)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        if (!empty($insert_array)) {
            $this->db->insert_batch('student_attendences', $insert_array);
        }
        if (!empty($update_array)) {
            $this->db->update_batch('student_attendences', $update_array, 'id');
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

    public function searchAttendenceClassSection($class_id, $section_id, $date)
    {
        $sql = "select student_sessions.in_time,student_sessions.out_time,student_sessions.attendence_id,student_sessions.attendence_dt,students.firstname,students.middlename,students.lastname,student_sessions.date,student_sessions.remark,student_sessions.biometric_attendence,student_sessions.qrcode_attendance,student_sessions.biometric_device_data,student_sessions.user_agent,students.roll_no,students.admission_no,students.id as std_id,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key`,attendence_type.long_lang_name,attendence_type.long_name_style from students,(SELECT student_attendences.in_time,student_attendences.out_time,
        student_session.id,student_session.student_id , IFNULL(student_attendences.date, 'xxx') as date, IFNULL(student_attendences.created_at, 'xxx') as attendence_dt,
        student_attendences.remark,student_attendences.biometric_attendence,student_attendences.user_agent,student_attendences.biometric_device_data,student_attendences.qrcode_attendance, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes' ORDER BY students.admission_no asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchAttendenceClassSectionWithMode($class_id, $section_id, $date, $mode)
    {
        $condition = "";
        if ($mode == 1) {
            $condition = " and student_sessions.biometric_attendence= 0 and student_sessions.qrcode_attendance=0";
        } elseif ($mode == 2) {
            $condition = " and student_sessions.biometric_attendence= 0 and student_sessions.qrcode_attendance=1";
        } elseif ($mode == 3) {
            $condition = " and student_sessions.biometric_attendence= 1 and student_sessions.qrcode_attendance=0";
        }
        
        $sql = "select students.id,student_sessions.attendence_id,student_sessions.attendence_dt,students.firstname,students.middlename,students.lastname,student_sessions.date,student_sessions.remark,student_sessions.biometric_attendence,student_sessions.qrcode_attendance,student_sessions.biometric_device_data,student_sessions.user_agent,students.roll_no,students.admission_no,students.id as std_id,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key`,attendence_type.long_lang_name,attendence_type.long_name_style from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,IFNULL(student_attendences.created_at, 'xxx') as attendence_dt,student_attendences.remark,student_attendences.biometric_attendence,student_attendences.user_agent,student_attendences.biometric_device_data,student_attendences.qrcode_attendance, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id = students.id and students.is_active = 'yes' ".$condition." ORDER BY students.admission_no asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchAttendenceReport($class_id, $section_id, $date)
    {
        $sql = "select student_sessions.attendence_id,students.firstname,students.middlename,student_sessions.date,student_sessions.remark,students.roll_no,students.admission_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key` from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions   LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id where student_sessions.student_id=students.id  and students.is_active = 'yes' ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchAttendenceClassSectionPrepare($class_id, $section_id, $date)
    {
        $query = $this->db->query("select student_sessions.attendence_id,student_sessions.remark,students.id as std_id,students.firstname,students.middlename,students.admission_no,student_sessions.date,students.roll_no,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id from students ,(SELECT student_session.id,student_session.student_id ,IFNULL(student_attendences.date, 'xxx') as date,student_attendences.remark,IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` RIGHT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  and student_attendences.date=" . $this->db->escape($date) . " where  student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions where student_sessions.student_id=students.id ");
        return $query->result_array();
    }

    public function count_attendance_obj($month, $year, $student_id, $attendance_type = 1)
    {
        $query = $this->db->select('count(*) as attendence')->join("student_session", "student_attendences.student_session_id = student_session.id")->where(array('student_attendences.student_session_id' => $student_id, 'month(date)' => $month, 'year(date)' => $year, 'student_attendences.attendence_type_id' => $attendance_type))->get("student_attendences");
        return $query->row()->attendence;
    }

    public function attendanceYearCount()
    {
        $query = $this->db->select("distinct year(date) as year")->get("student_attendences");
        return $query->result_array();
    }

    public function getTodayDayAttendance($total_student)
    {
        $query = $this->db->query("SELECT 
            concat(round((sum( case when `attendence_type_id`=1 then 1 else 0 end)*100/" . $total_student . "),2),'%') as present, concat(round((sum( case when `attendence_type_id`=3 then 1 else 0 end)*100/" . $total_student . "),2),'%') as late,
            concat(round((sum( case when `attendence_type_id`=4 then 1 else 0 end)*100/" . $total_student . "),2),'%') as absent,concat(round((sum( case when `attendence_type_id`=6 then 1 else 0 end)*100/" . $total_student . "),2),'%') as half_day,sum( case when `attendence_type_id`=1 then 1 else 0 end) as total_present,sum( case when `attendence_type_id`=3 then 1 else 0 end) as total_late,sum( case when `attendence_type_id`=4 then 1 else 0 end) as total_absent,sum( case when `attendence_type_id`=6 then 1 else 0 end) as total_half_day FROM `student_attendences` inner join student_session on student_attendences.student_session_id=student_session.id where date_format(date,'%Y-%m-%d')='" . date('Y-m-d') . "' and student_session.session_id='" . $this->current_session . "'");
        return $query->row_array();
    }

    public function student_attendences($condition, $date_condition)
    {
        $query = $this->db->query("SELECT `classes`.`id` AS `class_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,students.middlename, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`,  `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`father_name`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`gender`, `students`.`rte`, `student_session`.`session_id`,`date`, count(student_attendences.id) as total_type FROM `student_attendences` INNER JOIN `student_session` ON `student_session`.`id` = `student_attendences`.`student_session_id` INNER JOIN `students` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` WHERE `student_session`.`session_id` = '" . $this->current_session . "' AND `students`.`is_active` = 'yes' " . $condition . " group by students.id  ORDER BY `students`.`id`");
        return $query->result_array();
    }

    public function checkholidatbydate($date)
    {
        $where['attendence_type_id'] = '5';
        $where['date'] = date('Y-m-d', strtotime($date));
        $query = $this->db->select('count(*) as day ')->where($where)->get('student_attendences')->row_array();
        return $query['day'];
    }

    public function biometric_attlog($limit = null, $offset = NULL)
    {
        return $this->db->select('student_attendences.*,CONCAT_WS(students.firstname," ",students.lastname) as name,students.firstname,students.middlename,students.lastname,students.roll_no')->from('student_attendences')->join('student_session', 'student_session.id=student_attendences.student_session_id', 'left')->join('students', 'student_session.student_id=students.id', 'left')->where('biometric_attendence', 1)->limit($limit, $offset)->get()->result_array();
    }

    public function biometric_attlogcount()
    {
        $count = $this->db->select('count(*) as total')->from('student_attendences')->where('biometric_attendence', 1)->get()->row_array();
        return $count['total'];
    }

    public function get_attendancebydate($date)
    {
        $sql = 'SELECT classes.class as class_name,classes.id as class_id, sections.id as sections_id, sections.section as section_name, SUM(CASE WHEN `attendence_type_id` = 1 THEN 1 ELSE 0 END) AS "present",SUM(CASE WHEN `attendence_type_id` = 2 THEN 1 ELSE 0 END) AS "excuse",SUM(CASE WHEN `attendence_type_id` = 4 THEN 1 ELSE 0 END) AS "absent",SUM(CASE WHEN `attendence_type_id` = 3 THEN 1 ELSE 0 END) AS "late",SUM(CASE WHEN `attendence_type_id` = 6 THEN 1 ELSE 0 END) AS "half_day" FROM `student_attendences` join student_session on student_attendences.student_session_id=student_session.id inner join class_sections on (student_session.class_id=class_sections.class_id and student_session.section_id=class_sections.section_id) inner join classes on classes.id=class_sections.class_id inner join sections on sections.id=class_sections.section_id WHERE 1  and `student_session`.`session_id`=' . $this->current_session . ' ' . $date . ' group by class_sections.id';

        $query = $this->db->query($sql);		
		$result = $query->result();
        foreach ($result as $key => $sectionList_value) {
            $classid   = $sectionList_value->class_id;
            $sectionsid 	= $sectionList_value->sections_id;	
			
			$result[$key]->male_present		=	count($this->getmalefemalecount($classid, $sectionsid, $date, "Male", "in(1,2,3,6)"));
			$result[$key]->female_present	=	count($this->getmalefemalecount($classid, $sectionsid, $date, "Female", "in(1,2,3,6)"));			
			$result[$key]->male_absent		=	count($this->getmalefemalecount($classid, $sectionsid, $date, "Male", "in(4)"));			 
			$result[$key]->female_absent	=	count($this->getmalefemalecount($classid, $sectionsid, $date, "Female", "in(4)"));			
             
        }
        return $result;		
    }	
	
	public function getmalefemalecount($class_id, $section_id, $date, $gender, $type) 
    {
        $sql = "select student_sessions.in_time,student_sessions.out_time,student_sessions.attendence_id,student_sessions.attendence_dt,students.firstname,students.middlename,students.lastname,student_sessions.date,student_sessions.remark,student_sessions.biometric_attendence,student_sessions.qrcode_attendance,student_sessions.biometric_device_data,student_sessions.user_agent,students.roll_no,students.admission_no,students.id as std_id,students.lastname,student_sessions.attendence_type_id,student_sessions.id as student_session_id, attendence_type.type as `att_type`,attendence_type.key_value as `key`,attendence_type.long_lang_name,attendence_type.long_name_style from students,
		
		(SELECT student_attendences.in_time,student_attendences.out_time,
        student_session.id,student_session.student_id , IFNULL(student_attendences.date, 'xxx') as date, IFNULL(student_attendences.created_at, 'xxx') as attendence_dt,        student_attendences.remark,student_attendences.biometric_attendence,student_attendences.user_agent,student_attendences.biometric_device_data,student_attendences.qrcode_attendance, IFNULL(student_attendences.id, 0) as attendence_id,student_attendences.attendence_type_id FROM `student_session` LEFT JOIN student_attendences ON student_attendences.student_session_id=student_session.id  " . $date . " and student_attendences.attendence_type_id ".$type." where student_attendences.attendence_type_id !='' and student_session.session_id=" . $this->db->escape($this->current_session) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_sessions  
		
		LEFT JOIN attendence_type ON attendence_type.id=student_sessions.attendence_type_id 
		where student_sessions.student_id = students.id and students.is_active = 'yes'  and students.gender = '$gender'
		group by students.id ORDER BY students.admission_no asc ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }	

    public function studentattendance($date, $student_session_id)
    {
        $sql = "select student_attendences.*,student_session.student_id,attendence_type.type as `att_type`,attendence_type.key_value as `key` from student_attendences join student_session ON student_session.id=student_attendences.student_session_id left join attendence_type ON attendence_type.id = student_attendences.attendence_type_id where student_attendences.student_session_id = $student_session_id and student_attendences.date =" . $this->db->escape($date);

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function studentattendancecount($year, $student_id, $att_type)
    {
        $query = $this->db->select('count(*) as attendence')
            ->join('student_session', 'student_session.id = student_attendences.student_session_id', 'left')
            ->where('student_attendences.student_session_id', $student_id)
            ->where('year(date)', $year)
            ->where('student_attendences.attendence_type_id', $att_type)
            ->get("student_attendences");
        return $query->row()->attendence;
    }

    public function student_attendence_bw_date($date_from, $date_to, $student_session_id)
    {
        $query = $this->db->select('student_attendences.*,attendence_type.type as `att_type`,attendence_type.key_value as `key`')
            ->join('student_session', 'student_session.id = student_attendences.student_session_id')
            ->join('attendence_type', 'attendence_type.id = student_attendences.attendence_type_id')
            ->where('student_attendences.student_session_id', $student_session_id)
            ->where("date BETWEEN '{$date_from}' AND '{$date_to}'")
            ->get("student_attendences");

        return $query->result();
    }
}
