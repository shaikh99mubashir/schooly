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

    /**
     * GET lesson plan / syllabus for a student
     * POST /api/webservice/getlessonplan
     * Body: {"student_id": "1", "date_from": "2026-03-09", "date_to": "2026-03-15"}
     */
    public function getlessonplan()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;
        $date_from = isset($this->_json_input['date_from']) ? $this->_json_input['date_from'] : null;
        $date_to = isset($this->_json_input['date_to']) ? $this->_json_input['date_to'] : null;

        $student_session = $this->_getStudentSession($student_id);
        if (empty($student_session)) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Student session not found',
                'timetable' => new \stdClass(),
            ));
        }

        $class_id = $student_session['class_id'];
        $section_id = $student_session['section_id'];

        try {
            $this->load->model('syllabus_model');

            // Get student's subject group class section data
            $student_class_obj = new \stdClass();
            $student_class_obj->class_id = $class_id;
            $student_class_obj->section_id = $section_id;
            $student_data = $this->syllabus_model->get_studentsyllabus($student_class_obj);

            if (empty($student_data)) {
                return $this->_json(array(
                    'status' => 1,
                    'message' => 'No syllabus data found for this class',
                    'timetable' => new \stdClass(),
                ));
            }

            $subject_group_class_section_id = $student_data[0]->subject_group_class_section_id;

            // Determine date range (default: current week)
            if (empty($date_from)) {
                $setting = $this->setting_model->getSetting();
                $start_weekday = strtolower($setting->start_week);
                $monday = strtotime("last " . $start_weekday);
                $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
                $date_from = date('Y-m-d', $monday);
            }
            if (empty($date_to)) {
                $date_to = date('Y-m-d', strtotime($date_from . ' +6 days'));
            }

            // Build timetable grouped by day
            $timetable = array();
            $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

            $current_date = $date_from;
            $day_index = 0;

            while (strtotime($current_date) <= strtotime($date_to) && $day_index < 7) {
                $day_name = date('l', strtotime($current_date)); // Monday, Tuesday, etc.
                $data = array(
                    'date' => $current_date,
                    'subject_group_class_section_id' => $subject_group_class_section_id,
                );

                $syllabus_data = $this->syllabus_model->get_subject_syllabus_student_byDate($data);

                $day_entries = array();
                if (!empty($syllabus_data)) {
                    foreach ($syllabus_data as $entry) {
                        $day_entries[] = array(
                            'id' => isset($entry['id']) ? $entry['id'] : '',
                            'subject_syllabus_id' => isset($entry['id']) ? $entry['id'] : '',
                            'subject_name' => isset($entry['subname']) ? $entry['subname'] : '',
                            'subject_code' => isset($entry['scode']) ? $entry['scode'] : '',
                            'subject_group_name' => isset($entry['sgname']) ? $entry['sgname'] : '',
                            'lesson_name' => isset($entry['lessonname']) ? $entry['lessonname'] : '',
                            'topic_name' => isset($entry['topic_name']) ? $entry['topic_name'] : '',
                            'date' => $current_date,
                            'time_from' => isset($entry['time_from']) ? $entry['time_from'] : '',
                            'time_to' => isset($entry['time_to']) ? $entry['time_to'] : '',
                            'class' => isset($entry['cname']) ? $entry['cname'] : '',
                            'section' => isset($entry['sname']) ? $entry['sname'] : '',
                            'description' => isset($entry['description']) ? $entry['description'] : '',
                            'teaching_method' => isset($entry['teaching_method']) ? $entry['teaching_method'] : '',
                            'general_objectives' => isset($entry['general_objectives']) ? $entry['general_objectives'] : '',
                            'previous_knowledge' => isset($entry['previous_knowledge']) ? $entry['previous_knowledge'] : '',
                            'attachment' => isset($entry['attachment']) ? $entry['attachment'] : '',
                            'presentation' => isset($entry['presentation']) ? $entry['presentation'] : '',
                            'lacture_video' => isset($entry['lacture_video']) ? $entry['lacture_video'] : '',
                            'video' => isset($entry['lacture_video']) ? $entry['lacture_video'] : '',
                            'created_by' => isset($entry['created_for']) ? $entry['created_for'] : '',
                        );
                    }
                }

                $timetable[$day_name] = $day_entries;
                $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
                $day_index++;
            }

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'timetable' => $timetable,
                'date_from' => $date_from,
                'date_to' => $date_to,
            ));

        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 0,
                'message' => 'Error fetching lesson plan: ' . $e->getMessage(),
                'timetable' => new \stdClass(),
            ));
        }
    }

    /**
     * Add/Edit daily assignment
     * POST /api/webservice/addeditdailyassignment
     * Body: form-data with title, subject, description, student_id, file
     */
    public function addeditdailyassignment()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->input->get_request_header('User-ID', TRUE);
        if (empty($user_id)) {
            $user_id = $this->input->post('student_id');
        }
        if (empty($user_id)) {
            return $this->_json(array('status' => 'fail', 'error' => 'User-ID required', 'message' => ''));
        }

        $student_id = $this->input->post('student_id');
        if (empty($student_id)) {
            $student_id = $user_id;
        }

        $title = $this->input->post('title');
        $subject = $this->input->post('subject');
        $description = $this->input->post('description');
        $assignment_id = $this->input->post('id');

        // Validate required fields
        if (empty($title)) {
            return $this->_json(array('status' => 'fail', 'error' => array('title' => 'Title is required'), 'message' => ''));
        }
        if (empty($subject)) {
            // Try alternative field names
            $subject = $this->input->post('subject_group_subject_id');
            if (empty($subject)) {
                $subject = $this->input->post('subject_id');
            }
            if (empty($subject)) {
                $subject = $this->input->post('subject_code');
            }
        }
        if (empty($subject)) {
            return $this->_json(array('status' => 'fail', 'error' => array('subject' => 'Subject is required'), 'message' => ''));
        }

        // Get student_session_id (required for DB insert)
        $student_session = $this->_getStudentSession($student_id);
        if (empty($student_session)) {
            return $this->_json(array('status' => 'fail', 'error' => 'Student session not found', 'message' => ''));
        }
        $student_session_id = $student_session['id'];

        try {
            // Handle file upload
            $img_name = '';
            if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
                $this->load->library('media_storage');
                $img_name = $this->media_storage->fileupload('file', './uploads/homework/daily_assignment/');
            }

            $data = array(
                'title'                    => $title,
                'student_session_id'       => $student_session_id,
                'description'              => !empty($description) ? $description : '',
                'subject_group_subject_id' => $subject,
                'date'                     => date('Y-m-d'),
                'attachment'               => $img_name,
                'evaluated_by'             => NULL,
            );

            // If editing, add the ID
            if (!empty($assignment_id)) {
                $data['id'] = $assignment_id;
            }

            $id = $this->homework_model->adddailyassignment($data);

            return $this->_json(array(
                'status' => 'success',
                'error' => '',
                'message' => 'Daily assignment saved successfully',
            ));
        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 'fail',
                'error' => array('file' => $e->getMessage()),
                'message' => '',
            ));
        }
    }

    /**
     * Delete daily assignment
     * POST /api/webservice/deletedailyassignment
     */
    public function deletedailyassignment()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $assignment_id = isset($this->_json_input['id']) ? $this->_json_input['id'] : null;
        if (empty($assignment_id)) {
            $assignment_id = $this->input->post('id');
        }

        if (empty($assignment_id)) {
            return $this->_json(array('status' => 'fail', 'message' => 'Assignment ID is required'));
        }

        try {
            $row = $this->homework_model->getsingledailyassignment($assignment_id);
            if (!empty($row) && !empty($row['attachment'])) {
                $this->load->library('media_storage');
                $this->media_storage->filedelete($row['attachment'], 'uploads/homework/daily_assignment/');
            }
            $this->homework_model->deletedailyassignment($assignment_id);

            return $this->_json(array('status' => 'success', 'message' => 'Deleted successfully'));
        } catch (Exception $e) {
            return $this->_json(array('status' => 'fail', 'message' => 'Error: ' . $e->getMessage()));
        }
    }

    /**
     * GET online exams for a student
     * POST /api/webservice/getOnlineExam
     * Body: {"student_id": "1"}
     */
    public function getOnlineExam()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;

        $student_session = $this->_getStudentSession($student_id);
        if (empty($student_session)) {
            return $this->_json(array('status' => 0, 'message' => 'Student session not found', 'exams' => array()));
        }

        $student_session_id = $student_session['id'];

        try {
            $this->load->model('onlineexam_model');
            $exams = $this->onlineexam_model->getStudentexam($student_session_id);

            $today = date('Y-m-d H:i:s');
            $upcoming = array();
            $closed = array();

            if (!empty($exams)) {
                foreach ($exams as $exam) {
                    $exam_data = array(
                        'id' => isset($exam->id) ? $exam->id : '',
                        'onlineexam_student_id' => isset($exam->onlineexam_student_id) ? $exam->onlineexam_student_id : '',
                        'exam' => isset($exam->exam) ? $exam->exam : '',
                        'attempt' => isset($exam->attempt) ? $exam->attempt : '',
                        'exam_from' => isset($exam->exam_from) ? $exam->exam_from : '',
                        'exam_to' => isset($exam->exam_to) ? $exam->exam_to : '',
                        'duration' => isset($exam->duration) ? $exam->duration : '',
                        'description' => isset($exam->description) ? $exam->description : '',
                        'passing_percentage' => isset($exam->passing_percentage) ? $exam->passing_percentage : '',
                        'publish_result' => isset($exam->publish_result) ? $exam->publish_result : '0',
                        'is_active' => isset($exam->is_active) ? $exam->is_active : '1',
                        'is_random_question' => isset($exam->is_random_question) ? $exam->is_random_question : '0',
                        'counter' => isset($exam->counter) ? $exam->counter : '0',
                    );

                    if (isset($exam->exam_to) && strtotime($exam->exam_to) >= strtotime($today)) {
                        $upcoming[] = $exam_data;
                    } else {
                        $closed[] = $exam_data;
                    }
                }
            }

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'exams' => $upcoming,
                'closed_exams' => $closed,
                'onlineexam' => array_merge($upcoming, $closed),
            ));
        } catch (Exception $e) {
            return $this->_json(array('status' => 0, 'message' => 'Error: ' . $e->getMessage(), 'exams' => array()));
        }
    }

    /**
     * GET online exam questions
     * POST /api/webservice/getOnlineExamQuestion
     */
    public function getOnlineExamQuestion()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;
        $onlineexam_id = isset($this->_json_input['onlineexam_id']) ? $this->_json_input['onlineexam_id'] : null;

        if (empty($onlineexam_id)) {
            return $this->_json(array('status' => 0, 'message' => 'onlineexam_id is required'));
        }

        try {
            $this->load->model('onlineexam_model');

            // Get exam details
            $exam_details = $this->onlineexam_model->getexamdetails($onlineexam_id);
            $is_random = false;
            if (!empty($exam_details)) {
                $exam_obj = is_array($exam_details) ? (object)$exam_details[0] : $exam_details;
                $is_random = (isset($exam_obj->is_random_question) && $exam_obj->is_random_question == 1);
            }

            // Get questions
            $questions = $this->onlineexam_model->getExamQuestions($onlineexam_id, $is_random);
            $questions_array = array();

            if (!empty($questions)) {
                foreach ($questions as $q) {
                    $questions_array[] = array(
                        'id' => isset($q->id) ? $q->id : '',
                        'question_id' => isset($q->question_id) ? $q->question_id : (isset($q->id) ? $q->id : ''),
                        'question' => isset($q->question) ? $q->question : '',
                        'question_type' => isset($q->question_type) ? $q->question_type : 'single_answer',
                        'opt_a' => isset($q->opt_a) ? $q->opt_a : '',
                        'opt_b' => isset($q->opt_b) ? $q->opt_b : '',
                        'opt_c' => isset($q->opt_c) ? $q->opt_c : '',
                        'opt_d' => isset($q->opt_d) ? $q->opt_d : '',
                        'opt_e' => isset($q->opt_e) ? $q->opt_e : '',
                        'correct' => isset($q->correct) ? $q->correct : '',
                        'marks' => isset($q->onlineexam_question_marks) ? $q->onlineexam_question_marks : '1',
                        'neg_marks' => isset($q->neg_marks) ? $q->neg_marks : '0',
                        'level' => isset($q->level) ? $q->level : '',
                        'descriptive_word_limit' => isset($q->descriptive_word_limit) ? $q->descriptive_word_limit : '',
                        'subject_name' => isset($q->subject_name) ? $q->subject_name : '',
                    );
                }
            }

            // Get student's onlineexam_student record
            $student_session = $this->_getStudentSession($student_id);
            $onlineexam_student_id = '';
            if (!empty($student_session)) {
                $oes = $this->onlineexam_model->examstudentsID($student_session['id'], $onlineexam_id);
                if (!empty($oes)) {
                    $onlineexam_student_id = $oes->id;
                }
            }

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'questions' => $questions_array,
                'onlineexam_student_id' => $onlineexam_student_id,
                'total_questions' => count($questions_array),
            ));
        } catch (Exception $e) {
            return $this->_json(array('status' => 0, 'message' => 'Error: ' . $e->getMessage(), 'questions' => array()));
        }
    }

    /**
     * Save online exam answers
     * POST /api/webservice/saveOnlineExam
     */
    public function saveOnlineExam()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $onlineexam_student_id = isset($this->_json_input['onlineexam_student_id']) ? $this->_json_input['onlineexam_student_id'] : $this->input->post('onlineexam_student_id');
        $rows = isset($this->_json_input['rows']) ? $this->_json_input['rows'] : null;

        if (empty($onlineexam_student_id)) {
            return $this->_json(array('status' => 0, 'message' => 'onlineexam_student_id is required'));
        }

        try {
            $this->load->model('onlineexam_model');
            $this->load->model('onlineexamresult_model');

            // Save each answer
            if (!empty($rows) && is_array($rows)) {
                foreach ($rows as $row) {
                    $data = array(
                        'onlineexam_student_id' => $onlineexam_student_id,
                        'onlineexam_question_id' => isset($row['onlineexam_question_id']) ? $row['onlineexam_question_id'] : '',
                        'select_option' => isset($row['select_option']) ? $row['select_option'] : '',
                    );
                    $this->onlineexamresult_model->add($data);
                }
            }

            // Mark exam as attempted
            $this->onlineexam_model->updateExamResult($onlineexam_student_id);

            return $this->_json(array('status' => 1, 'message' => 'Exam submitted successfully'));
        } catch (Exception $e) {
            return $this->_json(array('status' => 0, 'message' => 'Error: ' . $e->getMessage()));
        }
    }

    /**
     * GET online exam result
     * POST /api/webservice/getOnlineExamResult
     */
    public function getOnlineExamResult()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $onlineexam_student_id = isset($this->_json_input['onlineexam_student_id']) ? $this->_json_input['onlineexam_student_id'] : null;
        $exam_id = isset($this->_json_input['exam_id']) ? $this->_json_input['exam_id'] : null;

        if (empty($onlineexam_student_id) || empty($exam_id)) {
            return $this->_json(array('status' => 0, 'message' => 'onlineexam_student_id and exam_id are required'));
        }

        try {
            $this->load->model('onlineexamresult_model');

            $results = $this->onlineexamresult_model->getResultByStudent($onlineexam_student_id, $exam_id);
            $rank = $this->onlineexamresult_model->onlineexamrank($onlineexam_student_id, $exam_id);

            $results_array = array();
            if (!empty($results)) {
                foreach ($results as $r) {
                    $results_array[] = (array)$r;
                }
            }

            $rank_data = array();
            if (!empty($rank)) {
                $rank_data = $rank[0];
            }

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'results' => $results_array,
                'rank' => $rank_data,
            ));
        } catch (Exception $e) {
            return $this->_json(array('status' => 0, 'message' => 'Error: ' . $e->getMessage()));
        }
    }

    /**
     * GET student behaviour records
     * POST /api/webservice/getstudentbehaviour
     */
    public function getstudentbehaviour()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $user_id = $this->_authenticate();
        if ($user_id === false) return;

        $student_id = isset($this->_json_input['student_id']) ? $this->_json_input['student_id'] : $user_id;

        try {
            // Check if the behaviour module tables exist
            $table_exists = $this->db->table_exists('assign_incidents');

            if (!$table_exists) {
                return $this->_json(array(
                    'status' => 1,
                    'message' => 'Behaviour records module is not enabled',
                    'assigned_incident' => array(),
                    'behaviour_score' => 0,
                    'behaviour_settings' => array(),
                ));
            }

            // Query incidents assigned to this student
            $this->db->select('assign_incidents.*, incidents.title, incidents.point, incidents.type')
                ->from('assign_incidents')
                ->join('incidents', 'incidents.id = assign_incidents.incident_id', 'left')
                ->where('assign_incidents.student_id', $student_id)
                ->order_by('assign_incidents.id', 'DESC');
            $query = $this->db->get();
            $incidents = $query->result_array();

            // Calculate total points
            $total_points = 0;
            if (!empty($incidents)) {
                foreach ($incidents as &$inc) {
                    $points = isset($inc['point']) ? (int)$inc['point'] : 0;
                    $total_points += $points;
                    $inc['incident_name'] = isset($inc['title']) ? $inc['title'] : '';
                    $inc['incident_point'] = $points;
                    $inc['incident_type'] = isset($inc['type']) ? $inc['type'] : '';
                }
            }

            return $this->_json(array(
                'status' => 1,
                'message' => 'Success',
                'assigned_incident' => $incidents,
                'behaviour_score' => $total_points,
                'behaviour_settings' => array(),
            ));
        } catch (Exception $e) {
            return $this->_json(array(
                'status' => 1,
                'message' => 'Behaviour records not available',
                'assigned_incident' => array(),
                'behaviour_score' => 0,
                'behaviour_settings' => array(),
            ));
        }
    }

    /**
     * GET incident comments
     * POST /api/webservice/getincidentcomments
     */
    public function getincidentcomments()
    {
        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->_json(array('status' => 0, 'message' => 'Method Not Allowed'), 405);
        }

        $this->_authenticate();

        return $this->_json(array(
            'status' => 1,
            'message' => 'Success',
            'comments' => array(),
        ));
    }
}
