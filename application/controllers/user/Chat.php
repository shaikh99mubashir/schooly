<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chat extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', '');
        $data = array();
        $sch_setting = $this->sch_setting_detail; 
        $user_type = $this->session->userdata['student']['role'];
        if($user_type=="student"){
            $data['delete_chat_enable']=$sch_setting->student_delete_chat;
        }else if($user_type=="parent"){
            $data['delete_chat_enable']=$sch_setting->guardian_delete_chat;
        }
        $this->load->view('layout/student/header');
        $this->load->view('user/chat/index', $data);
        $this->load->view('layout/student/footer');
    }

    public function myuser()
    {
        $user_type = $this->session->userdata['student']['role'];///added
        $data              = array();
        $student_id        = $this->customlib->getStudentSessionUserID();
        $chat_user         = $this->chatuser_model->getMyID($student_id,"$user_type");
        $data['chat_user'] = array();
        $data['userList']  = array();

        if (!empty($chat_user)) {
            $data['chat_user'] = $chat_user;
            $data['userList']  = $this->chatuser_model->myUser($student_id, $chat_user->id,"$user_type");
        }
        $userlist = $this->load->view('user/chat/_partialmyuser', $data, true);
        $array    = array('status' => '1', 'error' => '', 'page' => $userlist);
        echo json_encode($array);
    }

    public function getChatRecord()
    {
        $data['sch_setting']  = $this->sch_setting_detail;
        $student_id = $this->customlib->getStudentSessionUserID();
        $user_type = $this->session->userdata['student']['role'];
        $chat_user  = $this->chatuser_model->getMyID($student_id, "$user_type");
        $data['chat_user'] = $chat_user;
        $chat_connection_id = $this->input->post('chat_connection_id');
        $chat_to_user       = 0;
        $user_last_chat = $this->chatuser_model->getLastMessages($chat_connection_id);
        $chat_connection = $this->chatuser_model->getChatConnectionByID($chat_connection_id);
        if (!empty($chat_connection)) {
            $chat_to_user       = $chat_connection->chat_user_one;
            $chat_connection_id = $chat_connection->id;
            if ($chat_connection->chat_user_one == $chat_user->id) {
                $chat_to_user = $chat_connection->chat_user_two;
            }
        }
        $data['chatList'] = $this->chatuser_model->myChatAndUpdate($chat_connection_id, $chat_user->id);
        
        $sch_setting = $this->sch_setting_detail;
        if($user_type=="student"){
            $data['delete_chat_enable']=$sch_setting->student_delete_chat;
        }else if($user_type=="parent"){
            $data['delete_chat_enable']=$sch_setting->guardian_delete_chat;
        }
        $userlist         = $this->load->view('user/chat/_partialChatRecord', $data, true);
        $array            = array('status' => '1', 'error' => '', 'page' => $userlist, 'chat_to_user' => $chat_to_user, 'chat_connection_id' => $chat_connection_id, 'user_last_chat' => $user_last_chat);
        echo json_encode($array);
    }

    public function newMessage()
    {
        $chat_connection_id = $this->input->post('chat_connection_id');
        $chat_to_user       = $this->input->post('chat_to_user');
        $message            = $this->input->post('message');
        $time               = $this->input->post('time');
        $insert_record      = array(
            'chat_user_id'       => $chat_to_user,
            'message'            => trim($message),
            'chat_connection_id' => $chat_connection_id,
            'created_at'         => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($time, true)),
        );

        $last_insert_id = $this->chatuser_model->addMessage($this->security->xss_clean($insert_record));
        $array = array('status' => '1', 'last_insert_id' => $last_insert_id, 'error' => '', 'message' => $this->lang->line('inserted'));
        echo json_encode($array);
    }

    public function chatUpdate()
    {
        $user_type = $this->session->userdata['student']['role'];
        $chat_connection_id   = $this->input->post('chat_connection_id');
        $chat_user_id         = $this->input->post('chat_to_user');
        $last_chat_id         = $this->input->post('last_chat_id');
        $user_last_chat       = $this->chatuser_model->getLastMessages($chat_connection_id);
        $data['chat_user_id'] = $chat_user_id;
        $student_id           = $this->customlib->getStudentSessionUserID();
        $chat_user            = $this->chatuser_model->getMyID($student_id,"$user_type");
        $data['updated_chat'] = $this->chatuser_model->getUpdatedchat($chat_connection_id, $last_chat_id, $chat_user->id);
        $userlist = $this->load->view('user/chat/_chatupdate', $data, true);
        $array    = array('status' => '1', 'error' => '', 'page' => $userlist, 'user_last_chat' => $user_last_chat);
        echo json_encode($array);
    } 

    public function mychatnotification()
    {
        $student_id = $this->customlib->getStudentSessionUserID();
        $chat_user  = $this->chatuser_model->getMyID($student_id, 'student');
        $notifications = array();
        if (!empty($chat_user)) {
            $notifications = $this->chatuser_model->getChatNotification($chat_user->id);
        }
        $array = array('status' => '1', 'message' => $this->lang->line('success_message'), 'notifications' => $notifications);
        echo json_encode($array);
    }

    public function searchuser()
    {
        $user_type    = $this->session->userdata['student']['role'];
        $keyword      = $this->input->post('keyword');
        $student_id   = $this->customlib->getStudentSessionUserID();
        $chat_user    = $this->chatuser_model->getMyID($student_id, "$user_type");

        $chat_user_id = 0;
        if (!empty($chat_user)) {
            $chat_user_id = $chat_user->id;
        }
        $chat_result        = $this->chatuser_model->searchForUser($keyword, $chat_user_id, $student_id, "$user_type");
        $data['chat_user']  = $chat_result;
        $data['user_type']  = $this->session->userdata['student']['role'];
        $data['user_id']    = $this->session->userdata['student']['id'];
        $userlist = $this->load->view('admin/chat/_partialSearchUser', $data, true);
        $array    = array('status' => '1', 'error' => '', 'page' => $userlist);
        echo json_encode($array);
    }

    public function mynewuser()
    {
        $user_type = $this->session->userdata['student']['role'];///added
        $users_list            = $this->input->post('users');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $chat_user             = $this->chatuser_model->getMyID($student_id,"$user_type");
        $data['chat_user']     = $chat_user;
        $data['new_user_list'] = array();
        if (!empty($chat_user)) {
            $data['new_user_list'] = $this->chatuser_model->mynewuser($chat_user->id, $users_list);
        }
        $chat_records = $this->load->view('user/chat/_partialmynewuser', $data, true);
        $array        = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'), 'new_user_list' => $chat_records);
        echo json_encode($array);
    } 

    public function adduser()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('user_id', $this->lang->line('contact_person'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('user_type', $this->lang->line('user_type'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $errors = array(
                'user_id' => form_error('user_id'),
            );
            $array = array('status' => 0, 'error' => $errors, 'msg' => $this->lang->line('something_went_wrong'));
            echo json_encode($array);
        } else {
            $user_type          = $this->input->post('user_type');
            $user_id            = $this->input->post('user_id');
            $student_id         = $this->customlib->getStudentSessionUserID();
            $first_user_type    = $this->session->userdata['student']['role'];
            $first_user_id      = $this->session->userdata['student']['id'];

            $first_entry = array(
                'user_type'  => $first_user_type,
                'student_id' => $student_id,
            );

            $insert_data = array('user_type' => strtolower($user_type), 'create_student_id' => null);
            if ($user_type == "Student") {
                $insert_data['student_id'] = $user_id;
            } elseif ($user_type == "Staff") {
                $insert_data['staff_id'] = $user_id;
            }
            $insert_message = array(
                'message'            => 'you are now connected on chat',
                'chat_user_id'       => 0,
                'is_first'           => 1,
                'chat_connection_id' => 0,
            );

            $new_user_record = $this->chatuser_model->addNewUserForStudent($first_entry, $insert_data, $student_id, $insert_message, 'student');
            $json_record     = json_decode($new_user_record);
            $new_user        = $this->chatuser_model->getChatUserDetail($json_record->new_user_id);
			
			if((!$new_user->image)){
                if ($new_user->gender == 'Female') {
                    $new_user->image="default_female.jpg?".time();
                } elseif ($new_user->gender == 'Male') {
                    $new_user->image="default_male.jpg?".time();
                }
            }
			
            $student_id         = $this->customlib->getStudentSessionUserID();
            $user_type          = $this->session->userdata['student']['role'];///added
            $chat_user          = $this->chatuser_model->getMyID($student_id,"$user_type");
            $data['chat_user']  = $chat_user;
            $chat_connection_id = $json_record->new_user_chat_connection_id;
            $chat_to_user       = 0;
            $user_last_chat     = $this->chatuser_model->getLastMessages($chat_connection_id);
            $chat_connection    = $this->chatuser_model->getChatConnectionByID($chat_connection_id);

            if (!empty($chat_connection)) {
                $chat_to_user       = $chat_connection->chat_user_one;
                $chat_connection_id = $chat_connection->id;
                if ($chat_connection->chat_user_one == $chat_user->id) {
                    $chat_to_user = $chat_connection->chat_user_two;
                }
            }

            $sch_setting = $this->sch_setting_detail;
            if($user_type=="student"){
                $data['delete_chat_enable']=$sch_setting->student_delete_chat;
            }else if($user_type=="parent"){
                $data['delete_chat_enable']=$sch_setting->guardian_delete_chat;
            }
 
            $data['chatList'] = $this->chatuser_model->myChatAndUpdate($chat_connection_id, $chat_user->id);
            $chat_records     = $this->load->view('user/chat/_partialChatRecord', $data, true);
            $array            = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'), 'new_user' => $new_user, 'chat_connection_id' => $json_record->new_user_chat_connection_id, 'chat_records' => $chat_records, 'user_last_chat' => $user_last_chat);
            echo json_encode($array);
        }
    }

    function delete_msg(){
        $msg_id = $this->input->post('msg_id');
        $this->chatuser_model->delete_msg($msg_id);
    }

    function get_active_chat_msg(){
        $user_type          =   $this->session->userdata['student']['role'];///added
        $student_id         =   $this->customlib->getStudentSessionUserID();
        $chat_user          =   $this->chatuser_model->getMyID($student_id, "$user_type");
        $data['chat_user']  =   $chat_user;
        $chat_connection_id =   $this->input->post('chat_connection_id');
        $data['chatList']   =   $chatList = $this->chatuser_model->get_active_chat_msg($chat_connection_id, $chat_user->id);
        $array = array('status' => '1', 'error' => '', 'chatList' => $chatList);
        echo json_encode($array);
    }

    public function get_student_parent_chat_msg_count(){
        $user_type = $this->session->userdata['student']['role'];///added
        $student_or_parent_id = $this->customlib->getStudentSessionUserID();
        $result    = $this->chatuser_model->get_student_parent_chat_msg_count($student_or_parent_id,"$user_type");
        $array = array('status' => '1', 'error' => '', 'count' => count($result));
        echo json_encode($array);
    }


}
