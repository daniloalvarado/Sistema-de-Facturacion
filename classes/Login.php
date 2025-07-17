<?php 
require_once '../config.php';

class Login extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings; 
        parent::__construct();
        ini_set('display_error', 1);
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function index(){
        echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
    }

    public function login(){
        extract($_POST);

        // Prevent SQL injection by using prepared statements
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $qry = $stmt->get_result();
        
        if($qry->num_rows > 0){
            $data = $qry->fetch_assoc();
            // Use password_verify to check hashed passwords
            if(password_verify($password, $data['password'])){
                foreach($data as $k => $v){
                    if(!is_numeric($k) && $k != 'password'){
                        $this->settings->set_userdata($k, $v);
                    }
                }
                $this->settings->set_userdata('login_type', 1);
                // Update last login timestamp
                $this->update_user_last_login($data['id']);
                return json_encode(array('status'=>'success'));
            } else {
                // Don't reveal specific error details in production
                return json_encode(array('status'=>'incorrect'));
            }
        } else {
            // Don't reveal specific error details in production
            return json_encode(array('status'=>'incorrect'));
        }
    }

    private function update_user_last_login($user_id){
        $stmt = $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    public function logout(){
        if($this->settings->sess_des()){
            // Add CSRF protection to logout
            redirect('admin/login.php');
        }
    }

    function login_user(){
        extract($_POST);
        
        // Prevent SQL injection by using prepared statements
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $qry = $stmt->get_result();
        
        $resp = array();
        
        if($qry->num_rows > 0){
            $data = $qry->fetch_assoc();
            // Use password_verify to check hashed passwords
            if(password_verify($password, $data['password'])){
                foreach($data as $k => $v){
                    $this->settings->set_userdata($k, $v);
                }
                $this->settings->set_userdata('login_type', 1);
                $resp['status'] = 'success';
                
                // Update last login timestamp
                $this->update_client_last_login($data['id']);
            } else {
                $resp['status'] = 'incorrect';
            }
        } else {
            $resp['status'] = 'incorrect';
        }
        
        // Add rate limiting for failed login attempts
        if($resp['status'] == 'incorrect') {
            $this->log_failed_attempt($email ?? 'unknown');
        }
        
        return json_encode($resp);
    }
    
    private function update_client_last_login($client_id){
        $stmt = $this->conn->prepare("UPDATE clients SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
    }
    
    private function log_failed_attempt($identifier){
        // Log failed login attempts (implement rate limiting logic here)
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $this->conn->prepare("INSERT INTO login_attempts (ip_address, identifier, attempt_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $ip, $identifier);
        $stmt->execute();
    }
}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();

// Validate action input to prevent potential attacks
$allowed_actions = array('login', 'login_user', 'logout');
if (!in_array($action, $allowed_actions) && $action !== 'none') {
    $action = 'none';
}

switch ($action) {
    case 'login':
        echo $auth->login();
        break;
    case 'login_user':
        echo $auth->login_user();
        break;
    case 'logout':
        echo $auth->logout();
        break;
    default:
        echo $auth->index();
        break;
}