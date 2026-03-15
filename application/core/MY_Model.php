<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
    }

    public function log($message = null, $record_id = null, $action = null) {
        $user_id = $this->customlib->getStaffID();

        $ip = $this->input->ip_address();

        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {

            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $platform = $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)

        $insert = array(
            'message' => $message,
            'user_id' => $user_id,
            'record_id' => $record_id,
            'ip_address' => $ip,
            'platform' => $platform,
            'agent' => $agent,
            'action' => $action,
            'time' => date('Y-m-d H:i:s'),
        );

        $this->db->insert('logs', $insert);
    }

    protected function getTeacherClassSectionMatrix()
    {
        if (!isset($this->customlib)) {
            $this->load->library('customlib');
        }

        $userdata = $this->customlib->getUserData();
        if (isset($userdata['role_id'], $userdata['class_teacher']) && (int)$userdata['role_id'] === 2 && $userdata['class_teacher'] === 'yes') {
            $class_section_array = $this->customlib->get_myClassSection();
            if ($class_section_array === false) {
                return array();
            }
            return (array)$class_section_array;
        }

        return null;
    }

    protected function buildTeacherClassSectionCondition($tableAlias = 'student_session')
    {
        $matrix = $this->getTeacherClassSectionMatrix();
        if ($matrix === null) {
            return '';
        }

        if (empty($matrix)) {
            return '0=1';
        }

        $chunks = array();
        foreach ($matrix as $class_id => $sections) {
            if (empty($sections)) {
                continue;
            }
            foreach ($sections as $section_id) {
                $chunks[] = '(' . $tableAlias . '.class_id=' . (int)$class_id . ' AND ' . $tableAlias . '.section_id=' . (int)$section_id . ')';
            }
        }

        if (empty($chunks)) {
            return '0=1';
        }

        return '(' . implode(' OR ', $chunks) . ')';
    }

    protected function applyTeacherClassSectionFilter($tableAlias = 'student_session')
    {
        $condition = $this->buildTeacherClassSectionCondition($tableAlias);
        if ($condition === '') {
            return false;
        }

        $this->db->where($condition, null, false);
        return true;
    }
}
