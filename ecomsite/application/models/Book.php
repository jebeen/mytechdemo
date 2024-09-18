<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function getBookDetail($id=NULL) {
        $this->db->select("*");
        $this->db->from("books");
        $this->db->where("id",$id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getUser($username=NULL,$password=NULL) {
        $this->db->select("id,accType,name,password");
        $this->db->from("users");
        $this->db->where("name", $username);
        $this->db->where("password",$password);
        $query = $this->db->get();
        return $query->result();
    }

    public function getCartItems($userid=NULL) {
        $today = date("Y-m-d");
        $this->db->select("*");
        $this->db->from("cart");
        $this->db->where("userid",$userid);
        $this->db->like("create_date",$today);
        $query = $this->db->get();
        return $query->result();
    }

    public function getDiscount($userid=NULL,$discType=NULL,$schedule=NULL) {
        $column = "perc";
        if($discType == 1) {
            $column ="amount";
        }
        $this->db->select($column." as disc");
        $this->db->from("discount d");
        
        if($schedule) {
            // For family schedule
            $this->db->join("users u","d.category=u.accType");
            $this->db->join("family f","f.fid=u.familyid");
        } else {
            //For individual schedule
            $this->db->where("category",1);
        }
        $query = $this->db->get();
        return $query->row()->disc;

    }

    public function addBookToCart($book=NULL,$userid=NULL,$schedule=NULL) {
        $discType = $book[0]->discounttype;
        
        $today =date('Y-m-d');
        
        /*** Check recurring schedule ***/

        if(!$userid) {
            throw new Exception("User is not available");
        }
        $this->db->select("*");
        $this->db->from("cart");
        $this->db->where("userid",$userid);
        $this->db->where("date_format(create_date,'%Y-%m-%d')!=",$today);
        $query = $this->db->get();
        if($query && $query->num_rows()) {
            $accType=3;
        }

        $discountVal = self :: getDiscount($userid,$discType,$schedule);

        /*** Calculate discounted price ***/

        $price = $book[0]->price;
        if($discType == 1) { 
            // Amount based discount
           $netPrice = $price - $discountVal;
        } else if($discType == 2) {
            // Percentage based discount
            $netPrice = $price * ($discountVal / 100);
        }
        $book[0]->netprice = $netPrice;
        $newBook = [];
        $newBook['bookid'] = $book[0]->id;
        $newBook['userid'] = $userid;
        $newBook['amount'] = $book[0]->price;
        $newBook['discountType'] = $book[0]->discounttype;
        $newBook['discount'] = $discountVal;
        $newBook['netamount']  = $book[0]->netprice;
       
        /*** Save Cart item ***/
        if(!$this->db->insert("cart", $newBook)) {
            return 0;
        }
        return 1;

    }
}
?>