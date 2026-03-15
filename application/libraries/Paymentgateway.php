<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Paymentgateway {

    private $CI;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('mailer');
        $this->CI->mailer;
    }

    function sentRegisterMail($id, $send_to) {


        if (!empty($this->CI->mail_config) && $send_to != "") {
            $subject = "New Registration";
            $msg = $this->getStudentRegistrationContent($id);
            $this->CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

}

?>