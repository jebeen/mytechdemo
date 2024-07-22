<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashcntl extends CI_Controller {
    public $db;
    public function __construct() {
        parent:: __construct();
        $this->db = $this->load->database('portal',TRUE);
    }
    public function index() {
        $data['page'] = 'home';
        $this->load->view('portal/dashboard',$data);
    }
    public function teachers() {
        $teachers = $this->college->getTeacherslist();
        $data['page'] = 'teachers';
        $data['teachers'] = $teachers;
        $data['add'] = getUsersPermissions('Teacher','Add');
        $this->load->view('portal/dashboard',$data);
    }

    public function savestudent() {
        if($this->input->post('slno')) {
            $slno = $this->input->post('slno');
        }
        $this->form_validation->set_rules('name','Name','required|min_length[5]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('rollno','Rollno','required|numeric|xss_clean');
        $this->form_validation->set_rules('address','Address','required|min_length[5]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('sub1','Subject1 Marks','required|numeric|xss_clean');
        $this->form_validation->set_rules('sub2','Subject2 Marks','required|numeric|xss_clean');
        $this->form_validation->set_rules('sub3','Subject3 Marks','required|numeric|xss_clean');
        $this->form_validation->set_rules('grade','Grade','required|xss_clean');
        $this->form_validation->set_rules('stuclass','Class','required|xss_clean');
        $data['error'] = [];
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
            $this->session->set_flashdata('error',$data);
        } else {
            $input_data=[
                'name' => trim($this->input->post('name')),
                'rollno' => trim($this->input->post('rollno')),
                'class' => trim($this->input->post('stuclass')),
                'subject1' => trim($this->input->post('sub1')),
                'subject2' => trim($this->input->post('sub2')),
                'subject3' => trim($this->input->post('sub3')),
                'address' => trim($this->input->post('address')),
                'grade' => trim($this->input->post('grade')),
            ];
            $message = 'Student created';
            if($slno) {
                $message = 'Student details updated ...';
            }
            $status = $this->college->saveStudent($input_data,$slno);
            
            if($status) {
                $this->session->set_flashdata('success', $message);
            } else {
                log_message('error',$this->db->error());
                $this->session->set_flashdata('error', 'Error in creation');
            }
        }
        redirect("dashcntl/students");
    }

    public function removeStudent($param) {
        try {
            $response=['status'=>0,'message'=>'Error in processing the request ...'];
            $status = $this->college->removeStudent($param);
            if(!$status) {
                throw new Exception('Not able to update the student ...');
            }
            $response['message'] = 'Details updated';
            } catch(Exception $e) {
                $response['message'] = $e->getMessage();
            }
            echo json_encode($response);
            exit;
    }

    public function students_ajax() {
        try {
            $response = ['status' =>0, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[]];
            
            $param=$this->input->post();
            $start = $param['start'];
            $limit = $param['length'];

            $students = $this->college->getStudents($start,$limit);
            
            $edit = 0;
            $remove = 0;
            if($this->session->edit) {
                $edit =1;
            }
            if($this->session->remove) {
                $remove = 1;
            }
            $tblData = [];
            $actions =[];
            foreach($students as $stud) {
                $btnedit="<button type='button' class='btn btn-warning action-btn' data-target='#editstudent' data-toggle='modal' data-action='edit' id=".$stud->slno.">Edit</button>";
                $btnremove="<button class='btn btn-danger action-btn' data-action='remove' id=".$stud->slno.">Remove</button>";
                if($edit) {
                    $actions[]= $btnedit;
                }
                if($remove) {
                    $actions[]= $btnremove;
                }
                $stud->action_button = $actions;
                $tblData[] = $stud;
            }

            $this->db->select("count(*) as total");
            $this->db->from("students");
            $query = $this->db->get();
            $totalRecords = $query->row()->total;

            $this->db->select("count(*) as total");
            $this->db->from("students");
            
            if($start && $limit) {
                $this->db->limit($start,$limit);
            }
            $query = $this->db->get();
            $totalFiltered=$query->row()->total;
            $response['recordsTotel'] = $totalRecords;
            $response['recordsFiltered'] = $totalFiltered;
            $response['data'] = $tblData;

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);

    }


    public function getstudent($param) {
        $stud = $this->college->getStudents('','',$param);
        $status = ['status'=>0, 'data'=> []];
        if($stud) {
            $status['status'] = 1;
            $status['data'] = $stud;
        }
        echo json_encode($status);
        exit;
    }
    public function students() {
        $data['page'] = 'students';
        $data['add'] = getUsersPermissions('Student','Add',$this->session->userrole);
        $data['edit'] = getUsersPermissions('Student','Edit',$this->session->userrole);
        $data['remove'] = getUsersPermissions('Student','Remove',$this->session->userrole);
        $this->session->set_userdata('add', $data['add']);
        $this->session->set_userdata('edit', $data['edit']);
        $this->session->set_userdata('remove', $data['remove']);
        $this->load->view('portal/dashboard',$data);
    }
    public function checkname($input) {
        if($this->college->checkusername($input)) {
            $this->form_validation->set_message('checkname','User already exists');
            return FALSE;
        }
        return TRUE;
    }
    public function addteacher() {
        try {
            $response = ['status'=>0, 'message' => 'Something went wrong ...'];
            $name = trim($this->input->post('name'));
            $password = trim($this->input->post('password'));
            $this->form_validation->set_rules('name', 'Name' , 'required|min_length[5]|max_length[20]|callback_checkname|xss_clean');
            $this->form_validation->set_rules('password' , 'Password', 'required|min_length[5]|max_length[30]|xss_clean');
            $this->form_validation->set_rules('role','Role','required|numeric',[
                'required' => 'Role field is mandatory',
                'numeric' => 'It should be numeric'
            ]);
            if($this->form_validation->run() == FALSE) {
                $response['message'] = validation_errors();
            } else {
                $this->encryption->initialize(
                    array(
                            'cipher' => 'aes-256',
                            'mode' => 'ctr',
                            'key' => $this->config->item('encryption_key')
                    )
            );
            
            $password = $this->encryption->encrypt($password);
            $role = trim($this->input->post('role'));
            $data = ['username' => $name, 'password' => $password, 'role' => $role,'isactive'=>1];
            $resp = $this->college->saveTeacher($data);
            if(!$resp) {
                throw new Exception('Not able to save the data');
            }
            $response['status'] = 1;
            }
        } catch(Exception $e) {
            log_message('error', $e->getMessage());
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response);
        exit;
    }
}
