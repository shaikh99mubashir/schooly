<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Api_test extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
      
        $this->load->library('ResourceQuota');
    }


    public function index()
    {
    
       $this->resourcequota->getStaffLimit();
     
        

    }

}
