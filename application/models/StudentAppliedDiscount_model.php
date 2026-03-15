<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class StudentAppliedDiscount_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($student_fees_deposite_id = null)
    {
        $this->db->select('student_applied_discounts.*,fees_discounts.name,fees_discounts.code,fees_discounts.type,fees_discounts.percentage,fees_discounts.amount')->from('student_applied_discounts');
        $this->db->join('student_fees_discounts', 'student_fees_discounts.id = student_applied_discounts.student_fees_discount_id');
        $this->db->join('fees_discounts', 'fees_discounts.id = student_fees_discounts.fees_discount_id');
        $this->db->order_by('student_applied_discounts.id');
        $this->db->where('student_applied_discounts.student_fees_deposite_id', $student_fees_deposite_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function remove($invoice_id, $sub_invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $this->db->where('sub_invoice_id', $sub_invoice_id);
        $this->db->delete('student_applied_discounts');
        $message = DELETE_RECORD_CONSTANT . " On student applied discounts id " . $invoice_id." and subinvoice id".$sub_invoice_id;
        $action = "Delete";
        $record_id = $invoice_id;
        $this->log($message, $record_id, $action);
    }
	
}
