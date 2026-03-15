<?php

defined('BASEPATH') or exit('No direct script access allowed');
class SaasValidation
{
    private $CI;
    private $sass_enabled;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('ResourceQuota');
        $this->CI->load->library('media_storage');
        $this->CI->load->library('customlib');
        $this->CI->load->config('saas-config');
        $this->sass_enabled = $this->CI->config->item('saas_enabled');
    }

    
    public function applicationQuotas()
    {
        return TRUE;
    }

    public function validateCanAddNewResource($input, $resource_name, $no_of_record)
    {           
        return TRUE;
    }


    public function getResourceLimit($resource)
    {
        

        return TRUE;  // return true when saas is not enabled
    }


    public function validateCanUploadFile($input, $storage_array = [])
    {

        return TRUE;
    }

    public function updateStorageLimit($resource, $storage_array)
    {

        
        return TRUE; // return true when saas is not enabled
    }


    public function updateResouceQuota($resource, $resource_usage)
    {

        
        return TRUE; // return true when saas is not enabled
    }

    public function deleteResouceQuota($resource, $resource_usage)
    {

       
        return TRUE; // return true when saas is not enabled
    }
}
