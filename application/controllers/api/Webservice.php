<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Webservice extends MY_Controller
{
    private $_json_input = null;

    public function __construct()
    {
        parent::__construct();
        // All models are already loaded via MY_Controller
        // Cache JSON input since php://input can only be read once
        $raw = file_get_contents('php://input');
        if (!empty($raw)) {
            $this->_json_input = json_decode($raw, true);
        }
        if (!is_array($this->_json_input)) {
            $this->_json_input = array();
        }
    }

    /**
     * Authenticate API request using headers or JSON body.
     * Returns user_id on success, or sends JSON error and returns false.
     */
    private function _authenticate()
    {
        $user_id = $this->input->get_request_header('User-ID', TRUE);

        if (empty($user_id)) {
            $user_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : null;
        }

        if (empty($user_id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(array(
                    'status' => 0,
                    'message' => 'Unauthorized: User-ID header or student_id is required',
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
        return $this->studentsession_model->getStudentClass($student_id);
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
     * Safely get staff name by ID without crashing on missing session data
     */
    private function _getStaffName($staff_id)
    {
        try {
            $this->db->select('staff.name, staff.surname, staff.employee_id')
                ->from('staff')
                ->where('staff.id', $staff_id);
            $query = $this->db->get();
            return $query->row_array();
        } catch (Exception $e) {
            return null;
        }
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

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;
        $homework_status = isset($this->_json_input['homework_status']) ? $this->_json_input['homework_status'] : 'pending';

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
        $student_session_id = $student_session['id'];

        try {
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
                    try {
                        $submitted_docs = $this->homework_model->get_homeworkDocBystudentId($hw_id, $student_id);
                        if (!empty($submitted_docs)) {
                            $hw['student_document'] = isset($submitted_docs[0]['docs']) ? $submitted_docs[0]['docs'] : '';
                            $hw['student_message'] = isset($submitted_docs[0]['message']) ? $submitted_docs[0]['message'] : '';
                        }
                    } catch (Exception $e) {
                        // Skip if failed
                    }
                }

                // Get staff details for created_by (using safe direct query)
                if (!empty($hw['created_by'])) {
                    $staff = $this->_getStaffName($hw['created_by']);
                    if (!empty($staff)) {
                        $hw['created_by_name'] = isset($staff['name']) ? $staff['name'] : '';
                        $hw['created_by_surname'] = isset($staff['surname']) ? $staff['surname'] : '';
                        $hw['employee_no'] = isset($staff['employee_id']) ? $staff['employee_id'] : '';
                    }
                }

                // Add API-friendly field names
                $hw['homework_id'] = $hw['id'];
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

        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Error fetching homework: ' . $e->getMessage(),
                'homeworklist' => array(),
            ));
        }
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

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;
        $student_session_id = isset($this->_json_input['student_session_id']) ? $this->_json_input['student_session_id'] : null;

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

        try {
            $assignments = $this->homework_model->getdailyassignment($student_id, $student_session_id);

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'dailyassignment' => is_array($assignments) ? $assignments : array(),
            ));
        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Error fetching daily assignments: ' . $e->getMessage(),
                'dailyassignment' => array(),
            ));
        }
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

    /**
     * GET subject list for a student
     * POST /api/webservice/getSubjectList
     * Body: {"student_id": "1"}
     */
    public function getSubjectList()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;

        $student_session = $this->_getStudentSession($student_id);
        if (empty($student_session)) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Student session not found',
                'subjects' => array(),
            ));
        }

        $class_id = $student_session['class_id'];
        $section_id = $student_session['section_id'];

        try {
            $subjects = $this->subjectgroup_model->getAllsubjectByClassSection($class_id, $section_id);
            $subjects_array = array();

            if (!empty($subjects)) {
                foreach ($subjects as $sub) {
                    $subjects_array[] = array(
                        'subject_group_subject_id' => isset($sub->subject_group_subject_id) ? $sub->subject_group_subject_id : '',
                        'subject_id' => isset($sub->subject_id) ? $sub->subject_id : '',
                        'subject_name' => isset($sub->subject_name) ? $sub->subject_name : '',
                        'subject_code' => isset($sub->subject_code) ? $sub->subject_code : '',
                        'subject_group_name' => isset($sub->subject_group_name) ? $sub->subject_group_name : '',
                    );
                }
            }

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'subjects' => $subjects_array,
            ));
        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Error fetching subjects: ' . $e->getMessage(),
                'subjects' => array(),
            ));
        }
    }

    /**
     * GET syllabus subjects for a student
     * POST /api/webservice/getsyllabussubjects
     * Body: {"student_id": "1"}
     */
    public function getsyllabussubjects()
    {
        // Alias — same logic as getSubjectList
        return $this->getSubjectList();
    }
}
