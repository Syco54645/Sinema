<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function login(){

        
    }

    public function register(){

        $data = [];
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $name = $this->input->post('username');
            $email =  $this->input->post('email');
            $password = $this->input->post('password');

            $this->auth->insert_user($name, $email, $password);
            $this->session->set_flashdata('register_info', 'User Registered Successfully');
            redirect('register');
        }

        $this->load->view('partials/template-header', $data);
        $this->load->view('user/register');
        $this->load->view('partials/template-footer', $data);
    }
}