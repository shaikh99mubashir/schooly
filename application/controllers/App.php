<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class App extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('setting_model');
        $this->load->library('customlib');
    }

    public function index()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $setting_result = $this->setting_model->getSetting();

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'url'                      => $setting_result->mobile_api_url,
                    'site_url'                 => site_url(),
                    'app_logo'                 => $setting_result->app_logo,
                    'app_primary_color_code'   => $setting_result->app_primary_color_code,
                    'app_secondary_color_code' => $setting_result->app_secondary_color_code,
                    'lang_code'                => $setting_result->language_code,
                    'app_ver'                  => $this->customlib->getAppVersion(),
                    'languages'                => $setting_result->activelanguage2,
                )));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(array(
                    'error' => "Method Not Allowed",
                )));
        }
    }

    public function zoom()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "authorization: Bearer sY6xc8tAS7Wj8-MXyXxheg",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function login()
    {
        // Suppress deprecation warnings on PHP 8.x that can cause 500 errors
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

        if ($this->input->server('REQUEST_METHOD') != 'POST') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(array('status' => 0, 'error' => 'Method Not Allowed')));
        }

        // Get raw JSON input or form data
        $json_input = json_decode(file_get_contents('php://input'), true);
        $username = isset($json_input['username']) ? $json_input['username'] : $this->input->post('username');
        $password = isset($json_input['password']) ? $json_input['password'] : $this->input->post('password');
        $email    = isset($json_input['email']) ? $json_input['email'] : $this->input->post('email');

        if (empty($username) && empty($email)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array('status' => 0, 'error' => 'Username or email is required')));
        }
        if (empty($password)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array('status' => 0, 'error' => 'Password is required')));
        }

        $setting_result = $this->setting_model->getSetting();

        // Try Staff login first (if email provided)
        if (!empty($email) || (!empty($username) && strpos($username, '@') !== false)) {
            $login_email = !empty($email) ? $email : $username;
            $staff_result = $this->staff_model->checkLogin(array('email' => $login_email, 'password' => $password));
            if ($staff_result) {
                $roles = array();
                if (isset($staff_result->roles)) {
                    $roles = $staff_result->roles;
                }
                $response = array(
                    'status'  => 1,
                    'message' => 'Login successful',
                    'record'  => array(
                        'id'        => $staff_result->id,
                        'username'  => $staff_result->name . ' ' . $staff_result->surname,
                        'email'     => $staff_result->email,
                        'image'     => !empty($staff_result->image) ? base_url('uploads/staff_images/' . $staff_result->image) : '',
                        'role'      => 'staff',
                        'roles'     => $roles,
                        'is_active' => $staff_result->is_active,
                        'token'     => bin2hex(random_bytes(32)),
                    ),
                    'site_url'    => site_url(),
                    'base_url'    => base_url(),
                    'school_name' => isset($setting_result->name) ? $setting_result->name : '',
                );
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response));
            }
        }

        // Try Student/Parent login
        $login_username = !empty($username) ? $username : $email;
        $login_data = array(
            'username' => $login_username,
            'password' => $password,
        );

        try {
            $user_result = $this->user_model->checkLogin($login_data);
        } catch (\Throwable $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array('status' => 0, 'error' => 'Login error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine())));
        }

        if (isset($user_result) && !empty($user_result)) {
            $user = $user_result[0];
            if ($user->is_active == "yes") {
                $user_info = null;
                $role = $user->role;

                if ($role == "student") {
                    $user_info = $this->user_model->read_user_information($user->id);
                } else if ($role == "parent") {
                    $user_info = $this->user_model->checkLoginParent($login_data);
                }

                if ($user_info && !empty($user_info)) {
                    $record = $user_info[0];
                    $image = '';
                    $display_name = '';

                    if ($role == "parent") {
                        $display_name = isset($record->guardian_name) ? $record->guardian_name : $record->username;
                        if (isset($record->guardian_is)) {
                            if ($record->guardian_is == "father") $image = isset($record->father_pic) ? $record->father_pic : '';
                            elseif ($record->guardian_is == "mother") $image = isset($record->mother_pic) ? $record->mother_pic : '';
                            else $image = isset($record->guardian_pic) ? $record->guardian_pic : '';
                        }
                    } else {
                        $display_name = trim((isset($record->firstname) ? $record->firstname : '') . ' ' . (isset($record->lastname) ? $record->lastname : ''));
                        $image = isset($record->image) ? $record->image : '';
                    }

                    // Get student class
                    $classes = array();
                    if ($role == "student" && isset($record->user_id)) {
                        $student_session = $this->studentsession_model->getStudentClass($record->user_id);
                        if (!empty($student_session)) {
                            $classes[] = array(
                                'id'           => isset($student_session['id']) ? $student_session['id'] : '',
                                'class'        => isset($student_session['class']) ? $student_session['class'] : '',
                                'section'      => isset($student_session['section']) ? $student_session['section'] : '',
                                'session_id'   => isset($student_session['session_id']) ? $student_session['session_id'] : '',
                                'class_id'     => isset($student_session['class_id']) ? $student_session['class_id'] : '',
                                'section_id'   => isset($student_session['section_id']) ? $student_session['section_id'] : '',
                            );
                        }
                    }

                    $response = array(
                        'status'  => 1,
                        'message' => 'Login successful',
                        'record'  => array(
                            'id'           => $user->id,
                            'user_id'      => isset($record->user_id) ? $record->user_id : $user->id,
                            'username'     => $display_name,
                            'email'        => isset($record->email) ? $record->email : '',
                            'admission_no' => isset($record->admission_no) ? $record->admission_no : '',
                            'image'        => !empty($image) ? base_url('uploads/student_images/' . $image) : '',
                            'role'         => $role,
                            'gender'       => isset($record->gender) ? $record->gender : '',
                            'dob'          => isset($record->dob) ? $record->dob : '',
                            'phone'        => isset($record->mobileno) ? $record->mobileno : '',
                            'is_active'    => $user->is_active,
                            'token'        => bin2hex(random_bytes(32)),
                            'classes'      => $classes,
                        ),
                        'site_url'    => site_url(),
                        'base_url'    => base_url(),
                        'school_name' => isset($setting_result->name) ? $setting_result->name : '',
                    );
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($response));
                }
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array('status' => 0, 'error' => 'Account is deactivated')));
            }
        }

        // Login failed
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array('status' => 0, 'error' => 'Invalid username or password')));
    }

    public function admin()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $setting_result = $this->setting_model->getSetting();

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'url'                      => $setting_result->admin_mobile_api_url,
                    'site_url'                 => site_url(),
                    'app_logo'                 => $setting_result->app_logo,
                    'app_primary_color_code'   => $setting_result->admin_app_primary_color_code,
                    'app_secondary_color_code' => $setting_result->admin_app_secondary_color_code,
                    'lang_code'                => $setting_result->language_code,
                    'app_ver'                  => $this->customlib->getAppVersion(),
                    'languages'                => $setting_result->activelanguage2,
                )));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(array(
                    'error' => "Method Not Allowed",
                )));
        }
    }

}
