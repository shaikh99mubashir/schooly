<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Holiday_model extends MY_model {

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id']!='') {           
            $this->db->where('id', $data['id']);
            $this->db->update('annual_calendar', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  holiday master id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        } else {            
            $this->db->insert('annual_calendar', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  holiday master  id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
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
            return $id;
        }
    }

    public function get($id = null,$holidaylist = null,$front_site = null) 
    {	
		$session	= $this->setting_model->getCurrentSession();
		
        $this->db->select("holiday_type.is_default,holiday_type.type, staff.name,staff.surname,staff.employee_id,annual_calendar.id,annual_calendar.holiday_type,annual_calendar.from_date,annual_calendar.to_date,annual_calendar.description,annual_calendar.is_active,annual_calendar.created_by,annual_calendar.created_at,annual_calendar.updated_at,annual_calendar.front_site,staff_roles.role_id");
        $this->db->from('annual_calendar');
        $this->db->join('staff', "staff.id = annual_calendar.created_by", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('holiday_type', "holiday_type.id = annual_calendar.holiday_type", "left");
           
        if ($id != null) {
            $this->db->where("annual_calendar.id", $id);
        }
		
        if ($holidaylist != null) {
            $this->db->where("annual_calendar.holiday_type", $holidaylist);
        } 
		
        if ($front_site != null) {
            $this->db->where("annual_calendar.front_site", $front_site);
			$this->db->order_by('from_date','asc');
        }else{
			$this->db->order_by('from_date','desc');
		}
		
		$this->db->where("annual_calendar.session_id", $session);
		
        $query = $this->db->get();
		if ($id != null) {
            return $query->row_array();
		}else{
			return $query->result_array();
		}
    }

    public function delete_holiday($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('annual_calendar');
        $message   = DELETE_RECORD_CONSTANT . " On Holiday Master id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
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

    public function add_holiday_type($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('holiday_type', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On holiday_type id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        } else {
            $this->db->insert('holiday_type', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On holiday_type id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
            return $id;
        }
    }

    public function get_holiday_type($id=null){
        if($id!=null){
            $this->db->where("id",$id);
        }
        $query = $this->db->get('holiday_type');
        return $query->result_array();
    }

    public function delete_holiday_type($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('holiday_type');
        $message   = DELETE_RECORD_CONSTANT . " On holiday_type id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function valid_holiday_type($str)
    {
        $type = $this->input->post('type');
        $id   = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_data_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'type' => $name);
            $query = $this->db->where($data)->get('holiday_type');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('type', $name);
            $query = $this->db->get('holiday_type');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

}
?>
