<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class College extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    public function getPermissions() {
        $res=[];
        $this->db->select("l1.title as t1,l2.title as t2,l2.permissions");
        $this->db->from("leftsidebar l1");
        $this->db->join("leftsidebar l2","l1.id=l2.parent");
        $this->db->group_by(array('l1.id','l2.title'));
        $query = $this->db->get();
        if($query && $query->num_rows()) {
            return $query->result_array();
        }
        return $res;
    }

    public function removeStudent($id) {
        if($this->db->where('id',$id)->delete('students')) {
            return TRUE;
        }
        return FALSE;
    }
    public function saveStudent($data,$slno=NULL) {
        if($option) {
            if($this->db->where('slno',$slno)->update('students',$data)) {
                return TRUE;
            }
        } else {
            if($this->db->insert('students',$data)) {
                return TRUE;
            }
        }
        
        return FALSE;
    }

    public function getStudents($start=NULL,$limit=NULL,$id=NULL) {
        $res = [];
        $this->db->select("*");
        $this->db->from("students");
        $this->db->order_by('create_date');
        if($id) {
            $this->db->where('slno', $id);
        }
        if($start && $limit) {
            $this->db->limit($start,$limit);
        }
        $query = $this->db->get();
        if($query && $query->num_rows()) {
            return $query->result();
        }
        return $res;
    }
    public function validateuser($name,$password) {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where('username', $name);
        $this->db->where('password',$password);
        $query = $this->db->get();
        if($query && $query->num_rows()) {
            return  1;
        }
        return 0;
    }
    public function setuserdata($name) {
        $this->db->select("*");
            $this->db->from("users");
            $this->db->where("username",$name);
            $query = $this->db->get();
            $role = $query->row()->role;
            $this->session->set_userdata('username',$name);
            $this->session->set_userdata('userrole',$role);
            return 1;
    }

    public function getTeacherslist() {
        $result = [];
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("role", 1);
        $query = $this->db->get();
        if($query && $query->num_rows()) {
            return $query->result();
        }
        return $result;
    }
    public function checkusername($user) {
        $this->db->select("username");
        $this->db->from("users");
        $this->db->where("role",1);
        $this->db->where("username",$user);
        $query = $this->db->get();
        if($query && $query->num_rows()) {
            return TRUE;
        }
        return FALSE;
    }
    public function saveTeacher($data) {
        if($this->db->insert('users', $data)) {
            return 1;
        }
        return 0;
    }
}