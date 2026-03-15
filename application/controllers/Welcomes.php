<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcomes extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}
 
    public function show()
    {
        $data['projects'] = $this->setting_model->get();
        $this->load->view('welcome_message');
    }
}
