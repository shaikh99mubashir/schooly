<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Balancefees_model extends MY_Model
{

    protected $balance_group;
    protected $balance_type;

    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('ci-blog');
        $this->balance_group   = $this->config->item('ci_balance_group');
        $this->balance_type    = $this->config->item('ci_balance_type');
        $this->current_session = $this->setting_model->getCurrentSession();
    }


    public function getTransStudentFees($student_session_id)
    {
        $sql    = "SELECT `student_fees_master`.*,fee_groups.name FROM `student_fees_master` INNER JOIN fee_session_groups on student_fees_master.fee_session_group_id=fee_session_groups.id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id  WHERE `student_session_id` = " . $student_session_id . " ORDER BY `student_fees_master`.`id`";
        $query  = $this->db->query($sql);
        $result_value = $query->result();

        $class_id = "";
        if (isset($_POST['class_id']) && !empty($_POST['class_id'])) {
            $class_id = $_POST['class_id'];
        }
        $section_id = "";
        if (isset($_POST['section_id']) && !empty($_POST['section_id'])) {
            $section_id = $_POST['section_id'];
        }
        $module = $this->module_model->getPermissionByModulename('transport');
        if ($module['is_active']) {
            $this->db->select('`student_fees_deposite`.*,0 as previous_balance_amount,route_pickup_point.fees as amount,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,"Transport Fees" as fee_group,"Transport Fees" as name, "Transport Fees" as `fee_type`, "" as `fee_code`,0 as is_system,student_transport_fees.student_session_id,students.admission_no, students.mobileno,`student_session`.`id` as `student_session_id`,0 as is_system, "" as fee_session_group_id')->from('student_transport_fees');

            $this->db->join('student_fees_deposite', 'student_transport_fees.id = `student_fees_deposite`.`student_transport_fee_id`', 'left');
            $this->db->join('transport_feemaster', '`student_transport_fees`.`transport_feemaster_id` = `transport_feemaster`.`id`');
            $this->db->join('student_session', 'student_session.id= `student_transport_fees`.`student_session_id`', 'INNER');
            $this->db->join('route_pickup_point', 'route_pickup_point.id = student_transport_fees.route_pickup_point_id');

            $this->db->join('classes', 'classes.id= student_session.class_id');
            $this->db->join('sections', 'sections.id= student_session.section_id');
            $this->db->join('students', 'students.id=student_session.student_id');
            $this->db->where('student_session.session_id', $this->current_session);
            $this->db->where('student_session.id', $student_session_id);
            $this->db->order_by('student_fees_deposite.id', 'desc');

            if ($class_id != null) {
                $this->db->where('student_session.class_id', $class_id);
            }

            if ($section_id != null) {
                $this->db->where('student_session.section_id', $section_id);
            }

            $query1        = $this->db->get();
            $result_value1 = $query1->result();
        } else {
            $result_value1 = array();
        }

        if (empty($result_value)) {
            $result_value2 = $result_value1;
        } elseif (empty($result_value1)) {
            $result_value2 = $result_value;
        } else {
            $result_value2 = array_merge($result_value, $result_value1);
        }

        if (!empty($result_value2)) {
            foreach ($result_value2 as $result_key => $result_value) {
                $result_value->fees = array();
                $fee_session_group_id   = $result_value->fee_session_group_id;
                $student_fees_master_id = $result_value->id;
                if (empty($result_value->fee_session_group_id)) {
                    $result_value->fees[0]     = (object)array('amount_detail' => $result_value->amount_detail, 'amount' => $result_value->amount);
                } else {
                    $result_value->fees     = (object)$this->getDueFeeByFeeSessionGroup($fee_session_group_id, $student_fees_master_id);
                }

                if ($result_value->is_system != 0) {
                    $result_value->fees->{"0"}->{'amount'} = $result_value->amount;
                }
            }
        }
        return $result_value2;
    }


    public function getDueFeeByFeeSessionGroup($fee_session_groups_id, $student_fees_master_id)
    {

        $sql = "SELECT student_fees_master.*,fee_groups_feetype.fine_type,fee_groups_feetype.id as `fee_groups_feetype_id`,fee_groups_feetype.amount,fee_groups_feetype.due_date,fee_groups_feetype.fine_amount,fee_groups_feetype.fee_groups_id,fee_groups.name,fee_groups_feetype.feetype_id,feetype.code,feetype.type, IFNULL(student_fees_deposite.id,0) as `student_fees_deposite_id`, IFNULL(student_fees_deposite.amount_detail,0) as `amount_detail` FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id = student_fees_master.fee_session_group_id INNER JOIN fee_groups_feetype on  fee_groups_feetype.fee_session_group_id = fee_session_groups.id  INNER JOIN fee_groups on fee_groups.id=fee_groups_feetype.fee_groups_id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id WHERE student_fees_master.fee_session_group_id =" . $fee_session_groups_id . " and student_fees_master.id=" . $student_fees_master_id . " order by fee_groups_feetype.due_date desc";
        $query = $this->db->query($sql);
        $result_value = $query->result();
        return $result_value;
    }
}
