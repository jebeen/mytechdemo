<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends CI_Controller {
	public function __construct() {
		parent::__construct();
        $this->load->model('book');
        $this->db=$this->load->database('default',TRUE);
    }
 
    public function login() {
        self :: auth();
    }
    
    public function auth($resptype=0) {
        try {
            $response = ['Status'=>0,'Message'=>'Something went wrong'];
        $headers = getallheaders();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_SERVER['PATH_INFO'] == '/Discount/login') {
                $username = trim($this->input->post('username',TRUE));
            $password = trim($this->input->post('password', TRUE));
            $user = $this->book->getUser($username,$password);
            if(!$username || !$password) {
                throw new Exception('User credentials are required');
            }
            if($user) {
                $userid = $user[0]->id;
                $this->session->set_userdata('userid',$userid);
                $token=$this->jwtauth->getToken($userid);
                $response=[
                    'Status'=>1,
                    'token'=>$token,
                    'Message'=>TOKEN_RET
                ];
            } else {
                throw new Exception('Invalid input');
            }

            } else {
                if(isset($headers['Authorization']) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                    $token = $matches[1];
                    if($this->jwtauth->decodeJWTtoken($token)) {
                        $response['Status']=1;
                        $message = AUTHORIZED;
                    } else {
                        $response['Status'] = 1;
                        $message = TOKEN_NOTMATCHED;
                    }
                } else {
                    $message = NOT_AUTHORIZED;
                }
                $response['Message']=$message;
            }
            
        } else {
            $response =['Message'=>METHOD_NOT_ALLOWED];
        }
        } catch(Exception $ex) {
            $response['Message']=$ex->getMessage();
        }
        if($resptype) {
            return $response;
        }
        echo json_encode($response);
    }

    public function getBooks() {
        $bookid = trim($this->input->post('id'));
        try {
            $response = ['Status'=>0,'Message'=>'Something went wrong'];
            $authresp = self::auth(1);
            if($authresp['Status']) {
                if(isset($_POST['id']) && !$bookid) {
                    throw new Exception(BOOKID_REQ);
                }
                $this->db->select("*");
                $this->db->from("books");
                if($bookid) {
                $this->db->where('id',$bookid);
                }
            $query = $this->db->get();
            $response['Status'] = 1;
            $response['Message'] = NO_BOOKS;
            if($query && $query->num_rows()) {
                $response['books'] = $query->result();
                $response['Message'] = count($query->result()). BOOKS_AVAILABLE;
            }
            } else {
                throw new Exception($authresp['Message']);
            }
        } catch(Exception $e) {
            $response['Message'] = $e->getMessage();
        }
        echo json_encode($response);
    }

    public function addtocart() {
        try {
            $response=['Status'=>0,'Message'=>'Something went wrong...'];
            $authresp = self :: auth(1);
            if(!$authresp['Status']) {
                throw new Exception($authresp['Message']);
            }
            $bookid = trim($this->input->post('id',TRUE));
            $schedule = trim($this->input->post('schedule',TRUE));
            if(!$bookid) {
                throw new Exception(BOOKID_REQ);
            }
            $book = $this->book->getBookDetail($bookid);
            $result = $this->book->addBookToCart($book,$this->session->userid,$schedule);

            if(!$result) {
                throw new Exception('Exception in processing cartitems.');
            }
            $cartItems = $this->book->getCartItems($this->session->userid);

            $response['Status'] = 1;
            $response['cart'] = $cartItems;
            $response['Message']='Book is added to cart';            

        } catch(Exception $ex) {
            $response['Message']=$ex->getMessage();
        }
        echo json_encode($response);
    }
}

?>