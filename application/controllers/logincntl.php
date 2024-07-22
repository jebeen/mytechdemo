<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logincntl extends CI_Controller {
    public $db;
    public function __construct() {
        parent:: __construct();
        $this->db = $this->load->database('portal',TRUE);
    }
    public function index() {
        $this->load->view('portal/login');
    }
    public function login() {
        try {
            $response = ['status' => 0, 'message' =>'Not able to process the request'];
            $name = trim($this->input->post('name'));
            $password = $this->input->post('password');
            $this->form_validation->set_rules('name', 'Name','required|xss_clean');
            $this->form_validation->set_rules('password', 'Password','required|xss_clean');
            if($this->form_validation->run() == FALSE) {
                $response['message'] = validation_errors();
            } else {
                $params = array(
                'cipher' => 'aes-256',
                'mode' => 'ctr',
                'key' => $this->config->item('encryption_key')
            );
            $password = $this->encryption->encrypt($password, $params);
            $valid = $this->college->validateuser($name,$password);
            if($valid) {
            $status = $this->college->setuserdata($name);
            if(!$status) {
                throw new Exception('Not able to set user data');
            }
            $response['status'] = 1;
        }
        }
        
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        exit;
    }

    public function disconnect() {
        $this->session->sess_destroy();
        redirect('/');
    }
}
