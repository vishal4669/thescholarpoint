<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date    : 09 July, 2020
*  Academy
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Paytm extends CI_Controller
{

    protected $unique_identifier = "paytm";
    // constructor
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        // CHECK IF THE ADDON IS ACTIVE OR NOT
        $this->check_addon_status();
    }

    function admin_login_check()
    {
        if (!$this->session->userdata('admin_login')) {
            redirect(site_url('home/login'), 'refresh');
        }
    }

    public function update_settings()
    {
        // check if admin is logged in
        $this->admin_login_check();

        $paytm_info = array();
        if (empty($this->input->post('PAYTM_MERCHANT_KEY')) || empty($this->input->post('PAYTM_MERCHANT_MID')) || empty($this->input->post('PAYTM_MERCHANT_WEBSITE')) || empty($this->input->post('INDUSTRY_TYPE_ID')) || empty($this->input->post('CHANNEL_ID'))) {
            $this->session->set_flashdata('error_message', get_phrase('nothing_can_not_be_empty'));
            redirect(site_url('admin/payment_settings'), 'refresh');
        }

        $paytm['PAYTM_MERCHANT_KEY']     = $this->input->post('PAYTM_MERCHANT_KEY');
        $paytm['PAYTM_MERCHANT_MID']     = $this->input->post('PAYTM_MERCHANT_MID');
        $paytm['PAYTM_MERCHANT_WEBSITE'] = $this->input->post('PAYTM_MERCHANT_WEBSITE');
        $paytm['INDUSTRY_TYPE_ID']       = $this->input->post('INDUSTRY_TYPE_ID');
        $paytm['CHANNEL_ID']             = $this->input->post('CHANNEL_ID');

        array_push($paytm_info, $paytm);


        $data['value']    =   json_encode($paytm_info);
        $this->db->where('key', 'paytm_keys');
        $this->db->update('settings', $data);

        $this->session->set_flashdata('flash_message', get_phrase('paytm_updated'));
        redirect(site_url('admin/payment_settings'), 'refresh');
    }

    public function checkout($payment_request = 'web')
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'mobile') {
            redirect('home', 'refresh');
        }

        //checking price
        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('total_price_of_checking_out')) :
            $total_price_of_checking_out = $this->input->post('total_price_of_checking_out');
        else :
            $total_price_of_checking_out = $this->session->userdata('total_price_of_checking_out');
        endif;

        if ($total_price_of_checking_out > 0) {
            $page_data['payment_request'] = $payment_request;
            $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $page_data['amount_to_pay']   = $total_price_of_checking_out;
            $this->load->view('payment/paytm_checkout', $page_data);
        } else {
            redirect('home', 'refresh');
        }
    }

    public function payThroughPaytm()
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytm_keys = get_settings('paytm_keys');
        $paytm_keys = json_decode($paytm_keys, true);

        $checkSum = "";
        $paramList = array();

        //$ORDER_ID = $_POST["ORDER_ID"];
        $ORDER_ID = "ORDS" . rand(10000, 99999999);
        $user_id = $this->session->userdata('user_id');
        $CUST_ID  = "CUST" . $user_id;
        $INDUSTRY_TYPE_ID = $paytm_keys[0]["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $paytm_keys[0]["CHANNEL_ID"];

        //checking price
        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('amount_to_pay')) :
            $TXN_AMOUNT = $this->input->post('amount_to_pay');
        else :
            $TXN_AMOUNT = $this->session->userdata('total_price_of_checking_out');
        endif;

        //MOBILE APP VARIABLE
        $payment_request = $this->input->post('payment_request');

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        $paramList["CALLBACK_URL"] = site_url("addons/paytm/pgResponse/" . $payment_request);

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $page_data['paramList'] = $paramList;
        $page_data['checkSum'] = $checkSum;
        $this->load->view('payment/paytm_merchant_checkout', $page_data);
    }

    public function pgResponse($payment_request)
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        $user_id = $this->session->userdata('user_id');

        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('amount_to_pay')) :
            $amount_paid = $this->input->post('amount_to_pay');
        else :
            $amount_paid = $this->session->userdata('total_price_of_checking_out');
        endif;

        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                //THESE ARE THE TASKS HAVE TO AFTER A PAYMENT
                $this->crud_model->enrol_student($user_id);
                $this->crud_model->course_purchase($user_id, 'paytm', $amount_paid);
                $this->email_model->course_purchase_notification($user_id, 'paytm', $amount_paid);
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                if ($payment_request == 'mobile') :
                    $course_id = $this->session->userdata('cart_items');
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
                else :
                    $this->session->set_userdata('cart_items', array());
                    redirect('home/my_courses', 'refresh');
                endif;
            } else {
                $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
                redirect('home', 'refresh');
            }

            if (isset($_POST) && count($_POST) > 0) {
                foreach ($_POST as $paramName => $paramValue) {
                    // YOU CAN PRINT PARAMNAMES AND PARAMVALUE HERE
                }
            }
        }elseif($payment_request == 'mobile'){
            echo site_phrase('an_error_occurred_during_payment');
        }else{
            $this->session->set_flashdata('error_message', site_phrase('Checksum_mismatched'));
            redirect('home', 'refresh');
        }
    }


    // CHECK IF THE ADDON IS ACTIVE OR NOT. IF NOT REDIRECT TO DASHBOARD
    public function check_addon_status()
    {
        $checker = array('unique_identifier' => $this->unique_identifier);
        $this->db->where($checker);
        $addon_details = $this->db->get('addons')->row_array();
        if ($addon_details['status']) {
            return true;
        } else {
            redirect(site_url(), 'refresh');
        }
    }















///////////////////BUNDLE///////////////////////
    public function bundle_checkout($payment_request = 'web'){
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'mobile') {
            redirect('home', 'refresh');
        }

        //checking price
        if ($this->session->userdata('checkout_bundle_price') > 0) {
            $page_data['payment_request'] = $payment_request;
            $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $page_data['amount_to_pay']   = $this->session->userdata('checkout_bundle_price');
            $this->load->view('bundle_payment/paytm/paytm_checkout', $page_data);
        } else {
            $this->session->set_flashdata('error_message', get_phrase('amount_less_than_1'));
            redirect(site_url('bundle_details/'.$this->session->userdata('checkout_bundle_id')), 'refresh');
        }
    }

    public function bundlePayThroughPaytm()
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytm_keys = get_settings('paytm_keys');
        $paytm_keys = json_decode($paytm_keys, true);

        $checkSum = "";
        $paramList = array();

        //$ORDER_ID = $_POST["ORDER_ID"];
        $ORDER_ID = "ORDS" . rand(10000, 99999999);
        $user_id = $this->session->userdata('user_id');
        $CUST_ID  = "CUST" . $user_id;
        $INDUSTRY_TYPE_ID = $paytm_keys[0]["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $paytm_keys[0]["CHANNEL_ID"];

        //checking price
        $TXN_AMOUNT = $this->session->userdata('checkout_bundle_price');

        //MOBILE APP VARIABLE
        $payment_request = $this->input->post('payment_request');

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $CUST_ID;
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        $paramList["CALLBACK_URL"] = site_url("addons/paytm/bundlePgResponse/" . $payment_request);

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $page_data['paramList'] = $paramList;
        $page_data['checkSum'] = $checkSum;
        $this->load->view('bundle_payment/paytm/paytm_merchant_checkout', $page_data);
    }

    public function bundlePgResponse($payment_request)
    {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

        // following files need to be included
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        $user_id = $this->session->userdata('user_id');

        $amount_paid = $this->session->userdata('checkout_bundle_price');

        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                //THESE ARE THE TASKS HAVE TO AFTER A PAYMENT
                $this->course_bundle_model->bundle_purchase('paytm', $amount_paid, null, null);
                $this->email_model->bundle_purchase_notification($user_id, 'paytm', $amount_paid);

                if ($payment_request == 'mobile') :
                    $course_id = $this->session->userdata('cart_items');
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
                else :
                    $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                    $this->session->set_userdata('checkout_bundle_price', null);
                    $this->session->set_userdata('checkout_bundle_id', null);
                    redirect('home/my_bundles', 'refresh');
                endif;
            } else {
                $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
                redirect('home', 'refresh');
            }

            if (isset($_POST) && count($_POST) > 0) {
                foreach ($_POST as $paramName => $paramValue) {
                    // YOU CAN PRINT PARAMNAMES AND PARAMVALUE HERE
                }
            }
        }elseif($payment_request == 'mobile'){
            echo site_phrase('an_error_occurred_during_payment');
        }else{
            $this->session->set_flashdata('error_message', site_phrase('Checksum_mismatched'));
            redirect('home', 'refresh');
        }
    }
}
