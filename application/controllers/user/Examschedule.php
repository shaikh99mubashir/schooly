<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ExamSchedule extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('media_storage');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examSchedule/index');
        $data['title'] = 'Exam Schedule';
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;
        $examSchedule          = $this->examgroupstudent_model->studentExams($student_session_id);
        $data['examSchedule']  = $examSchedule;
        $data['student_id']    =  $this->customlib->getStudentSessionUserID(); 
        $data['get_active_admitcard']  = $this->admitcard_model->get_active_admitcard();
        $data['sch_setting']     = $this->sch_setting_detail;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/exam_schedule/examList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function getexamscheduledetail()
    {
        $subjects                 = array();
        $exam_id                  = $this->input->post('exam_id');
        $subjects['subject_list'] = $this->batchsubject_model->getExamstudentSubjects($exam_id);
        $result                   = $this->load->view('user/exam_schedule/_getexamscheduledetail', $subjects, true);
        echo json_encode(array('status' => 1, 'result' => $result));
    }

    //*** download admit card ***//
    public function printCard()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('admitcard_template', $this->lang->line('template'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_id', $this->lang->line('exam'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_group_id', $this->lang->line('exam_group'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'admitcard_template'                     => form_error('admitcard_template'),
                'post_exam_id'                           => form_error('post_exam_id'),
                'post_exam_group_id'                     => form_error('post_exam_group_id'),
                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

            $post_exam_id            = $this->input->post('post_exam_id');
            $post_exam_group_id      = $this->input->post('post_exam_group_id');
            $students_array          = $this->input->post('exam_group_class_batch_exam_student_id');
            $exam                    = $this->examgroup_model->getExamByID($post_exam_id);
            $data['exam']            = $exam;
            $exam_grades             = $this->grade_model->getByExamType($exam->exam_group_type);
            $data['exam_grades']     = $exam_grades;
            $data['admitcard']       = $this->admitcard_model->get($this->input->post('admitcard_template'));
            $data['exam_subjects']   = $this->batchsubject_model->getExamstudentSubjects($post_exam_id);
            $data['student_details'] = $this->examstudent_model->getStudentsAdmitCardByExamAndStudentID($students_array, $post_exam_id);
            $data['sch_setting']     = $this->sch_setting_detail;
            $student_admit_cards     = $this->load->view('user/exam_schedule/_printadmitcard', $data, true);
            $array                   = array('status' => '1', 'error' => '', 'page' => $student_admit_cards);
            echo json_encode($array);
        }
    }







}
