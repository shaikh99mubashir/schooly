<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Leaverequest_model extends MY_model
{

    public function staff_leave_request($id = null)
    {
        $session = $this->setting_model->getCurrentSession();

        if ($id != null) {
            $this->db->where("staff_leave_request.staff_id", $id);
        } elseif ($this->session->has_userdata('admin')) {
            $getStaffRole = $this->customlib->getStaffRole();
            $staffrole    = json_decode($getStaffRole);

            $superadmin_visible = $this->customlib->superadmin_visible();
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $this->db->where("roles.id !=", 7);
            }
        }

        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type')
            ->join("staff", "staff.id = staff_leave_request.staff_id")
            ->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id")
            ->join("staff_roles", "staff_roles.staff_id = staff.id")
            ->join("roles", "staff_roles.role_id = roles.id")
            ->where("staff.is_active", "1")
            ->where("staff_leave_request.session_id", $session)
            ->order_by("staff_leave_request.id", "desc")
            ->get("staff_leave_request");

        $result = $query->result_array();
        foreach ($result as $key => $value) {
            $applied_by = $this->staff_model->get($value['applied_by']);
            if (!empty($applied_by['employee_id'])) {
                $result[$key]['applied_by'] = $applied_by['name'] . ' ' . $applied_by['surname'] . ' (' . $applied_by['employee_id'] . ')';
            } else {
                $result[$key]['applied_by'] = '';
            }
        }
        return $result;
    }

    public function user_leave_request($id = null)
    {
        $session = $this->setting_model->getCurrentSession();

        $this->db->select('staff.name,staff.surname,staff.employee_id,staff_leave_request.*,leave_types.type');
        $this->db->join("staff", "staff.id = staff_leave_request.staff_id");
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->join("leave_types", "leave_types.id = staff_leave_request.leave_type_id");
        $this->db->where("staff.is_active", "1");
        $this->db->where("staff_leave_request.session_id", $session);
        $this->db->where("staff.id", $id);

        if ($this->session->has_userdata('admin')) {
            $getStaffRole = $this->customlib->getStaffRole();
            $staffrole    = json_decode($getStaffRole);
            $superadmin_visible = $this->customlib->superadmin_visible();
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $this->db->where("roles.id !=", 7);
            }
        }

        $this->db->order_by("staff_leave_request.id", "desc");
        $query = $this->db->get("staff_leave_request");

        $result = $query->result_array();
        foreach ($result as $key => $value) {
            $applied_by = $this->staff_model->get($value['applied_by']);
            if (!empty($applied_by['employee_id'])) {
                $result[$key]['applied_by'] = $applied_by['name'] . ' ' . $applied_by['surname'] . ' (' . $applied_by['employee_id'] . ')';
            } else {
                $result[$key]['applied_by'] = '';
            }

        }
        return $result;
    }

    public function allotedLeaveType($id)
    {
        $session = $this->setting_model->getCurrentSession();

        $query = $this->db->select('staff_leave_details.*,leave_types.type,leave_types.id as typeid')->where(array('staff_id' => $id,'session_id' => $session))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");
        return $query->result_array();
    }

    public function myallotedLeaveType($id, $leave_type_id)
    {
        $session = $this->setting_model->getCurrentSession();

        $query = $this->db->select('staff_leave_details.*,leave_types.type,leave_types.id as typeid , (SELECT sum(leave_days) from staff_leave_request WHERE leave_type_id=' . $leave_type_id . ' and staff_id=' . $id . ' and status !="disapprove" and session_id=' . $session.'  ) as `total_applied`', null, false)->where(array('staff_id' => $id, 'leave_types.id' => $leave_type_id,'session_id' => $session))->join("leave_types", "staff_leave_details.leave_type_id = leave_types.id")->get("staff_leave_details");
        return $query->row_array();
    }

    public function countLeavesData($staff_id, $leave_type_id)
    {
        $session = $this->setting_model->getCurrentSession();
        $query1 = $this->db->select('sum(leave_days) as approve_leave')->where(array('staff_id' => $staff_id, 'status!=' => 'disapprove', 'leave_type_id' => $leave_type_id,'session_id' => $session))->get("staff_leave_request");
        return $query1->row_array();
    }

    public function changeLeaveStatus($data, $staff_id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $staff_id)->update("staff_leave_request", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On staff leave request id " . $staff_id;
        $action    = "Update";
        $record_id = $staff_id;
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

    public function getLeaveSummary()
    {
        $query = $this->db->select('*')->get("staff");
        return $query->result_array();
    }

    public function leave_remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('staff_leave_request');
        $message   = DELETE_RECORD_CONSTANT . " On staff leave request id " . $id;
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

    public function addLeaveRequest($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {

            $this->db->where("id", $data["id"]);
            $this->db->update("staff_leave_request", $data);
            $message   = UPDATE_RECORD_CONSTANT . " On staff leave request id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {

            $this->db->insert("staff_leave_request", $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On staff leave request id " . $id;
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
        }
    }

    public function get_staff_leave($id)
    {
        $this->db->select('staff_leave_request.*');
        $this->db->from('staff_leave_request');
        $this->db->where('staff_leave_request.id', $id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function getleaverequestreport($condition)
    {
        $query = "SELECT staff_leave_request.*,staff.name,staff.date_of_joining, staff.surname, staff.employee_id,leave_types.type from staff_leave_request 
        left JOIN staff on staff.id = staff_leave_request.staff_id
        left JOIN leave_types on leave_types.id = staff_leave_request.leave_type_id
        left JOIN staff_roles on staff_roles.staff_id = staff.id
        left JOIN roles on staff_roles.role_id = roles.id 
        where 1 " .  $condition . " and staff.is_active = 1 ";
        $query = $this->db->query($query);        
        $result = $query->result_array();
        return $result;
    }

     public function getmyleaverequestreport($condition)
    {
        $query = "SELECT staff_leave_request.*,staff.name,staff.date_of_joining, staff.surname, staff.employee_id,leave_types.type from staff_leave_request 
        left JOIN staff on staff.id = staff_leave_request.staff_id
        left JOIN leave_types on leave_types.id = staff_leave_request.leave_type_id
        left JOIN staff_roles on staff_roles.staff_id = staff.id
        left JOIN roles on staff_roles.role_id = roles.id 
        where 1 " .  $condition . " and staff.is_active = 1 ";
        $query = $this->db->query($query);        
        $result = $query->result_array();
        return $result;
    }

}
