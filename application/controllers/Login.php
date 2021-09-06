<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function index() {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        }elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }else {
            redirect(site_url('home/login'), 'refresh');
        }
    }

    //Validate OTP.
    public function validate_otp(){
        if(isset($_POST['login_otp']) && $_POST['login_otp'] !=''){

            if($_POST['login_otp'] == $this->session->userdata('otp_no')){
                //Valid OTP.
                $this->session->set_flashdata('flash_message', get_phrase('welcome to').' '.$this->session->userdata('name'));

                if ($this->session->userdata('role_id') == 1) {
                    $this->session->set_userdata('admin_login', '1');
                    redirect(site_url('admin/dashboard'), 'refresh');
                }else if($this->session->userdata('role_id') == 2){
                    $this->session->set_userdata('user_login', '1');
                    redirect(site_url('home'), 'refresh');
                }
            }else{
                //Invalid OTP. Redirect to login form.
                $this->session->set_flashdata('error_message', get_phrase('error_wrong_otp_entered,_please_enter_valid_OTP'));
                redirect(site_url('home/login/OTP'), 'refresh');
            }
        }
    }

    // Generate token
    public function getToken($length){
      $token = "";
      $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
      $codeAlphabet.= "0123456789";
      $max = strlen($codeAlphabet); // edited

      for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
      }
      return $token;
    }

    public function validate_login($from = "") {

        if($this->crud_model->check_recaptcha() == false && get_frontend_settings('recaptcha_status') == true){
            $this->session->set_flashdata('error_message',get_phrase('recaptcha_verification_failed'));
            redirect(site_url('home/login'), 'refresh');
        }
        
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');
        $credential = array('mobile' => $mobile, 'password' => sha1($password), 'status' => 1);

        // Checking login credential for admin
        $query = $this->db->get_where('users', $credential);

        if ($query->num_rows() > 0) {      
            $row = $query->row();

           

            //update user session token..
            $token = $this->getToken(10);
            $this->session->set_userdata('mobile', $mobile);
            $this->session->set_userdata('session_token', $token);

            // Update user token 
            $condition = array('mobile' => $mobile);
            $query_token = $this->db->get_where('user_token', $condition);
            $token_count = $query_token->num_rows();

            if($token_count > 0){
                $this->db->where('mobile' , $mobile);
                $this->db->update('user_token', array('token' => $token) );
            }else{
                $this->db->set('mobile', $mobile);
                $this->db->set('token', $token);
                $this->db->insert('user_token');
            }
            // End Update user token 

            $this->session->set_userdata('user_id', $row->id);
            $this->session->set_userdata('role_id', $row->role_id);
            $this->session->set_userdata('role', get_user_role('user_role', $row->id));
            $this->session->set_userdata('name', $row->first_name.' '.$row->last_name);
            $this->session->set_userdata('is_instructor', $row->is_instructor);

            if ($row->role_id == 1) {
                $this->session->set_userdata('admin_login', '1');
                redirect(site_url('admin/dashboard'), 'refresh');
            }

        //*******Generate the OTP and send it on mobile number*****************//
            $otpno = rand(1000000,99999);
            $otp_text_msg = ('TheScholarPoint OTP: '.$otpno. ' Login access code for your https://thescholarpoint.com');

             $url = 'http://mobi1.blogdns.com/httpapi/httpapisms.aspx';

            //Set the otp value in session.
            $this->session->set_userdata('otp_no', $otpno);
            
            $ch = curl_init();
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
            );
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,'UserID=Vishalkotak&UserPass=India@123&MobileNo='.$mobile.'&GSMID=INFORM&Message='.$otp_text_msg.'&UNICODE=TEXT');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);

            /*Print error if any*/
            if(curl_errno($ch))
            {
                $error =  'error:' . curl_error($ch);
            }

            curl_close($ch);
            $op_arr = explode('=',$output);

            if($op_arr[0] == 100){
                redirect(site_url('home/login/OTP'), 'refresh');
            }else{
                 $this->session->set_flashdata('info_message', get_phrase('error_something_went_worng'));
                redirect(site_url('home/login'), 'refresh');
            }
            //End the OTP generation...

            $this->session->set_flashdata('flash_message', get_phrase('welcome').' '.$row->first_name.' '.$row->last_name);

            

            if ($row->role_id == 1) {
                $this->session->set_userdata('admin_login', '1');
                redirect(site_url('admin/dashboard'), 'refresh');
            }else if($row->role_id == 2){
                $this->session->set_userdata('user_login', '1');
                redirect(site_url('home'), 'refresh');
            }

        }else {
            $this->session->set_flashdata('error_message',get_phrase('invalid_login_credentials'));
            redirect(site_url('home/login'), 'refresh');
        }
    }

    public function register() {
        if($this->crud_model->check_recaptcha() == false && get_frontend_settings('recaptcha_status') == true){
            $this->session->set_flashdata('error_message',get_phrase('recaptcha_verification_failed'));
            redirect(site_url('home/login'), 'refresh');
        }


        $data['first_name'] = html_escape($this->input->post('first_name'));
        $data['last_name']  = html_escape($this->input->post('last_name'));
        $data['email']  = html_escape($this->input->post('email'));
        $data['password']  = sha1($this->input->post('password'));
        $data['stream']  = html_escape($this->input->post('stream'));
        $data['mobile']  = html_escape($this->input->post('mobile'));

        //Need to check the
        
        if(empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['stream']) || empty($data['mobile']) ){
            $this->session->set_flashdata('error_message',site_phrase('your_sign_up_form_is_empty').'. '.site_phrase('fill_out_the_form with_your_valid_data'));
            redirect(site_url('home/sign_up'), 'refresh');
        }

        $verification_code =  rand(100000, 200000);
        $data['verification_code'] = $verification_code;

        if (get_settings('student_email_verification') == 'enable') {
            $data['status'] = 0;
        }else {
            $data['status'] = 1;
        }

        $data['wishlist'] = json_encode(array());
        $data['watch_history'] = json_encode(array());
        $data['date_added'] = strtotime(date("Y-m-d H:i:s"));
        $social_links = array(
            'facebook' => "",
            'twitter'  => "",
            'linkedin' => ""
        );
        $data['social_links'] = json_encode($social_links);
        $data['role_id']  = 2;

        // Add paypal keys
        $paypal_info = array();
        $paypal['production_client_id'] = "";
        array_push($paypal_info, $paypal);
        $data['paypal_keys'] = json_encode($paypal_info);
        // Add Stripe keys
        $stripe_info = array();
        $stripe_keys = array(
            'public_live_key' => "",
            'secret_live_key' => ""
        );
        array_push($stripe_info, $stripe_keys);
        $data['stripe_keys'] = json_encode($stripe_info);

        $validity = $this->user_model->check_duplication('on_create', $data['email']);
        $validity_mobile = $this->user_model->check_duplication_mobile('on_create', $data['mobile']);

        if( ($validity === 'unverified_user' || $validity == true) && ( $validity_mobile === 'unverified_user' || $validity_mobile == true ) ) {
            if($validity === true || $validity_mobile === true){
                $this->user_model->register_user($data);
            }else{
                $this->user_model->register_user_update_code($data);
            }

            if (get_settings('student_email_verification') == 'enable') {
                $this->email_model->send_email_verification_mail($data['email'], $verification_code);

                if($validity === 'unverified_user' || $validity_mobile === 'unverified_user'){
                    $this->session->set_flashdata('info_message', get_phrase('you_have_already_registered').'. '.get_phrase('please_verify_your_email_address'));
                }else{
                    $this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done').'. '.get_phrase('please_check_your_mail_inbox_to_verify_your_email_address').'.');
                }
                $this->session->set_userdata('register_email', $this->input->post('email'));
                redirect(site_url('home/verification_code'), 'refresh');
            }else {
                $this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done'));
                redirect(site_url('home/login'), 'refresh');
            }

        }else {
            $this->session->set_flashdata('error_message', get_phrase('you_have_already_registered_use_unique_mobile_and_email'));
            redirect(site_url('home/login'), 'refresh');
        }
    }

    public function logout($from = "") {
        //destroy sessions of specific userdata. We've done this for not removing the cart session
        $this->session_destroy();
        redirect(site_url('home/login'), 'refresh');
    }

    public function session_destroy() {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('is_instructor');
        if ($this->session->userdata('admin_login') == 1) {
            $this->session->unset_userdata('admin_login');
        }else {
            $this->session->unset_userdata('user_login');
        }
    }

    function forgot_password($from = "") {
        if($this->crud_model->check_recaptcha() == false && get_frontend_settings('recaptcha_status') == true){
            $this->session->set_flashdata('error_message',get_phrase('recaptcha_verification_failed'));
            redirect(site_url('home/login'), 'refresh');
        }
        $email = $this->input->post('email');
        //resetting user password here
        $new_password = substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for admin
        $query = $this->db->get_where('users' , array('email' => $email));
        if ($query->num_rows() > 0)
        {
            $this->db->where('email' , $email);
            $this->db->update('users' , array('password' => sha1($new_password)));
            // send new password to user email
            $this->email_model->password_reset_email($new_password, $email);
            $this->session->set_flashdata('flash_message', get_phrase('please_check_your_email_for_new_password'));
            if ($from == 'backend') {
                redirect(site_url('login'), 'refresh');
            }else {
                redirect(site_url('home'), 'refresh');
            }
        }else {
            $this->session->set_flashdata('error_message', get_phrase('password_reset_failed'));
            if ($from == 'backend') {
                redirect(site_url('login'), 'refresh');
            }else {
                redirect(site_url('home'), 'refresh');
            }
        }
    }

    public function resend_verification_code(){
        $email = $this->input->post('email');
        $verification_code = $this->db->get_where('users', array('email' => $email))->row('verification_code');
        $this->email_model->send_email_verification_mail($email, $verification_code);
        
        return true;
    }

    public function verify_email_address() {
        $email = $this->input->post('email');
        $verification_code = $this->input->post('verification_code');
        $user_details = $this->db->get_where('users', array('email' => $email, 'verification_code' => $verification_code));
        if($user_details->num_rows() > 0) {        
            $user_details = $user_details->row_array();
            $updater = array(
                'status' => 1
            );
            $this->db->where('id', $user_details['id']);
            $this->db->update('users', $updater);
            $this->session->set_flashdata('flash_message', get_phrase('congratulations').'!'.get_phrase('your_email_address_has_been_successfully_verified').'.');
            $this->session->set_userdata('register_email', null);
            echo true;
        }else{
            $this->session->set_flashdata('error_message', get_phrase('the_verification_code_is_wrong').'.');
            echo false;
        }
    }


    function check_recaptcha_with_ajax(){
        if($this->crud_model->check_recaptcha()){
           echo true; 
        }else{
            echo false;
        }
    }
}
