<?php

class Calendar_model extends CI_Model
{


    protected $current_session;
    
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function saveEvent($data)
    {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("events", $data);
        } else {
            $this->db->insert("events", $data);
        }
    }

    public function getEventsById($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get("events");
            return $query->row_array();
        } else {
            $query = $this->db->get("events");
            return $query->result_array();
        }
    }

    public function getEvents()
    {
        return $data = $this->db->query("select null as is_default,null as holiday_type,a.event_title,a.id,a.event_type, a.start_date, a.end_date,a.event_description,a.event_color,a.event_for,a.role_id from events a
        UNION ALL
        select holiday_type.is_default,annual_calendar.holiday_type,type as event_title,annual_calendar.id,annual_calendar.holiday_type as event_type, annual_calendar.from_date as start_date, 
        annual_calendar.to_date as end_date, annual_calendar.description as event_description, annual_calendar.holiday_color as event_color,0 as event_for,null as role_id from annual_calendar 
            left join holiday_type on holiday_type.id=annual_calendar.holiday_type
            where annual_calendar.holiday_type!=0")->result_array();
    }

    public function getStudentEvents($id = null)
    {
        return $data = $this->db->query("select null as is_default,null as holiday_type,a.event_title,a.id,a.event_type, a.start_date, a.end_date,a.event_description,a.event_color,a.event_for,a.role_id,null as front_site from events a where a.event_type = 'public' or a.event_type = 'task'
        UNION ALL
        select holiday_type.is_default,annual_calendar.holiday_type,type as event_title,annual_calendar.id,annual_calendar.holiday_type as event_type, annual_calendar.from_date as start_date, 
        annual_calendar.to_date as end_date, annual_calendar.description as event_description, annual_calendar.holiday_color as event_color,0 as event_for,null as role_id,annual_calendar.front_site from annual_calendar 
            left join holiday_type on holiday_type.id=annual_calendar.holiday_type
            where annual_calendar.holiday_type!=0 and annual_calendar.front_site=1")->result_array();
    }

    public function deleteEvent($id)
    {
        $this->db->where("id", $id)->delete("events");
    }

    public function getTask($id, $role_id, $limit = null, $offset = null)
    {
        $query = $this->db->where(array('event_type' => 'task', 'event_for' => $id, 'role_id' => $role_id))->order_by("is_active,start_date", "asc")->limit($limit, $offset)->get("events");

        return $query->result_array();
    }

    public function countEventByUser($user_id)
    {
        $query = $this->db->where(array("event_type" => "task", 'event_for' => $user_id))->get("events");
        return $query->num_rows();
    }

    public function countrows($id, $role_id)
    {
        $query = $this->db->where(array('event_type' => 'task', 'event_for' => $id, 'role_id' => $role_id))->order_by("is_active,start_date", "asc")->get("events");
        return $query->num_rows();
    }

    public function countincompleteTask($id, $role_id = null)
    {
        $query = $this->db->where("event_type", "task")->where("is_active", "no")->where("event_for", $id)->where('role_id', $role_id)->where("start_date", date("Y-m-d"))->get("events");
        return $query->num_rows();
    }

    public function getincompleteTask($id, $role_id = null)
    {
        $query = $this->db->where("event_type", "task")->where("is_active", "no")->where("event_for", $id)->where('role_id', $role_id)->where("start_date", date("Y-m-d"))->order_by("start_date", "asc")->get("events");
        return $query->result_array();
    }

    public function geteventreminder($reminder_date)
    {
        $query = $this->db->where("date_format(start_date,'%Y-%m-%d')", $reminder_date)->get("events");
        return $query->result_array();
    }

    public function getstaffandstudentemail()
    {
        $this->db->select("email");
        $this->db->distinct();
        $this->db->from("staff");
        $this->db->where('staff.is_active', 1);
        $this->db->get();
        $query1 = $this->db->last_query();
        $this->db->select("email");
        $this->db->distinct();
        $this->db->from("students");
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->get();
        $query2 = $this->db->last_query();
        $query = $this->db->query($query1 . " UNION " . $query2);
        return $query->result_array();
    }
}
