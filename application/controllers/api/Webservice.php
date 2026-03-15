<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Webservice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // All models (homework_model, studentsession_model, staff_model, setting_model)
        // are already loaded via MY_Controller
    }

    /**
     * Authenticate API request using headers.
     * Returns student_id on success, or sends JSON error and returns false.
     */
    private function _authenticate()
    {
        $user_id = $this->input->get_request_header('User-ID', TRUE);

        if (empty($user_id)) {
            // Also check JSON body
            $json = json_decode(file_get_contents('php://input'), true);
            $user_id = isset($json['student_id']) ? $json['student_id'] : null;
        }

        if (empty($user_id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(array(
                    'status' => 0,
                    'message' => 'Unauthorized: User-ID header is required',
                )));
            return false;
        }

        return $user_id;
    }

    /**
     * Get student's class info from student_id
     */
    private function _getStudentSession($student_id)
    {
        $session = $this->studentsession_model->getStudentClass($student_id);
        return $session;
    }

    /**
     * Send JSON response
     */
    private function _json($data, $status_code = 200)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode($data));
    }

    /**
     * GET homework list for a student
     * POST /api/webservice/gethomework
     * Body: {"student_id": "1", "homework_status": "pending"}
     */
    public function gethomework()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        // Parse JSON body
        $json = json_decode(file_get_contents('php://input'), true);
        $student_id = isset($json['student_id']) ? $json['student_id'] : $user_id;
        $homework_status = isset($json['homework_status']) ? $json['homework_status'] : 'pending';

        // Get student's class info
        $student_session = $this->_getStudentSession($student_id);

        if (empty($student_session)) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Student session not found for student_id: ' . $student_id,
                'homeworklist' => array(),
            ));
        }

        $class_id = $student_session['class_id'];
        $section_id = $student_session['section_id'];
        $student_session_id = $student_session['id']; // student_session.id

        // Get active homework (submit_date >= today)
        $active_homework = $this->homework_model->getStudentHomeworkWithStatus($class_id, $section_id, $student_session_id);

        // Get closed homework (submit_date < today)
        $closed_homework = $this->homework_model->getstudentclosedhomeworkwithstatus($class_id, $section_id, $student_session_id);

        // Merge all homework
        $all_homework = array_merge(
            is_array($active_homework) ? $active_homework : array(),
            is_array($closed_homework) ? $closed_homework : array()
        );

        // Add submission status and filter by homework_status
        $filtered_list = array();

        foreach ($all_homework as $hw) {
            $hw_id = $hw['id'];
            $checkstatus = $this->homework_model->checkstatus($hw_id, $student_id);
            $is_submitted = (isset($checkstatus['record_count']) && $checkstatus['record_count'] > 0);

            // Determine status
            $has_evaluation = (isset($hw['homework_evaluation_id']) && $hw['homework_evaluation_id'] > 0);

            if ($has_evaluation) {
                $hw['status'] = 'evaluated';
            } elseif ($is_submitted) {
                $hw['status'] = 'submitted';
            } else {
                $hw['status'] = 'pending';
            }

            // Get student's submitted docs if any
            if ($is_submitted) {
                $submitted_docs = $this->homework_model->get_homeworkDocBystudentId($hw_id, $student_id);
                if (!empty($submitted_docs)) {
                    $hw['student_document'] = isset($submitted_docs[0]['docs']) ? $submitted_docs[0]['docs'] : '';
                    $hw['student_message'] = isset($submitted_docs[0]['message']) ? $submitted_docs[0]['message'] : '';
                }
            }

            // Get staff details for created_by
            if (!empty($hw['created_by'])) {
                $staff = $this->staff_model->get($hw['created_by']);
                if (!empty($staff)) {
                    $hw['created_by_name'] = isset($staff['name']) ? $staff['name'] : '';
                    $hw['created_by_surname'] = isset($staff['surname']) ? $staff['surname'] : '';
                    $hw['employee_no'] = isset($staff['employee_id']) ? $staff['employee_id'] : '';
                }
            }

            // Rename fields for API consistency
            $hw['homework_id'] = $hw['id'];
            $hw['homework_date'] = isset($hw['homework_date']) ? $hw['homework_date'] : '';
            $hw['submit_date'] = isset($hw['submit_date']) ? $hw['submit_date'] : '';
            $hw['submission_date'] = isset($hw['submit_date']) ? $hw['submit_date'] : '';
            $hw['attached_document'] = isset($hw['document']) ? $hw['document'] : '';
            $hw['student_id'] = $student_id;

            // Filter by requested status
            if ($homework_status === $hw['status']) {
                $filtered_list[] = $hw;
            }
        }

        return $this->_json(array(
            'status' => 1,
            'message' => 'Success',
            'homeworklist' => $filtered_list,
        ));
    }

    /**
     * GET daily assignments for a student
     * POST /api/webservice/getdailyassignment
     * Body: {"student_id": "1"}
     */
    public function getdailyassignment()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $json = json_decode(file_get_contents('php://input'), true);
        $student_id = isset($json['student_id']) ? $json['student_id'] : $user_id;
        $student_session_id = isset($json['student_session_id']) ? $json['student_session_id'] : null;

        // If no student_session_id provided, look it up
        if (empty($student_session_id)) {
            $student_session = $this->_getStudentSession($student_id);
            if (!empty($student_session)) {
                $student_session_id = $student_session['id'];
            }
        }

        if (empty($student_session_id)) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Student session not found',
                'dailyassignment' => array(),
            ));
        }

        $assignments = $this->homework_model->getdailyassignment($student_id, $student_session_id);

        return $this->_json(array(
            'status' => 1,
            'message' => 'Success',
            'dailyassignment' => is_array($assignments) ? $assignments : array(),
        ));
    }

    /**
     * Submit homework
     * POST /api/webservice/addhomework
     */
    public function addhomework()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $homework_id = $this->input->post('homework_id');
        $student_id = $this->input->post('student_id');
        $message = $this->input->post('message');

        if (empty($homework_id) || empty($student_id)) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'homework_id and student_id are required',
            ));
        }

        $data = array(
            'homework_id' => $homework_id,
            'student_id'  => $student_id,
            'message'     => !empty($message) ? $message : 'Homework Upload',
        );

        // Handle file upload
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            $this->load->library('media_storage');
            $img_name = $this->media_storage->fileupload('file', './uploads/homework/assignment/');
            if (!empty($img_name)) {
                $data['docs'] = $img_name;
            }
        } elseif (isset($_FILES['userfile']) && !empty($_FILES['userfile']['name'])) {
            $this->load->library('media_storage');
            $img_name = $this->media_storage->fileupload('userfile', './uploads/homework/assignment/');
            if (!empty($img_name)) {
                $data['docs'] = $img_name;
            }
        }

        try {
            $this->homework_model->upload_docs($data);
            return $this->_json(array(
                'status' => 1,
                'msg' => 'Success',
                'message' => 'Homework submitted successfully',
            ));
        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 0,
                'msg' => $e->getMessage(),
                'message' => 'Failed to submit homework: ' . $e->getMessage(),
            ));
        }
    }
}
