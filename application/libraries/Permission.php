<?php

/**
 * Description of Permission
 *
 * @author flashery
 */
class Permission {

    const ADMINISTARTOR = 1;
    const MANAGER = 2;
    const DISABLED_ADMIN = 3;
    const REMOVED_ADMIN = 4;
    const MEMBER = 5;
    const INACTIVE_MEMBER = 6;
    const DISABLED_MEMBER = 7;
    const REMOVED_MEMBER = 8;

    private $CI = null;

    public function __construct() {
        $this->CI = &get_instance();
    }

    // This function will check the user accesing the admin page if still logged in
    // or if has access to the admin page
    public function check_login() {
        $success = FALSE;
        if ((!$this->CI->session->has_userdata('logged_in') || $this->CI->session->userdata('logged_in') !== TRUE)) {
            $this->load_no_access();
        } else {
            if ($this->check_admin_permission) {
                redirect('admin');
            } elseif ($this->check_member_permission) {
                redirect('index/members_profile');
            }
        }
        return $success;
    }

    public function check_admin_permission() {
        if ($this->CI->session->userdata('subclass') !== 'A' ||
                $this->CI->session->userdata('role') != self::ADMINISTARTOR &&
                $this->CI->session->userdata('role') != self::MANAGER) {
            $this->no_admin_access();
        } else {
            return TRUE;
        }
    }

    public function check_member_permission() {
        if ($this->CI->session->userdata('subclass') !== 'M' || $this->CI->session->userdata('role') != self::MEMBER) {
            $this->no_member_access();
        } else {
            return TRUE;
        }
    }

    public function check_admin_role() {
        if (!$this->CI->session->has_userdata('role') ||
                $this->CI->session->userdata('role') != self::ADMINISTARTOR) {
            $this->CI->session->set_flashdata('error', 'Sorry, only Administrator has access to that page.');
            redirect('admin');
        } else {
            return TRUE;
        }
    }

    private function no_admin_access() {
        // Load admin no access
        $data['main_content'] = 'errors/no_access';
        $data['message'] = 'Sorry, you have no access to this page! Please login as an Administrator.';
        $this->CI->load->view('includes/index/template', $data);
    }

    private function no_member_access() {
        // Load admin no access
        $data['main_content'] = 'errors/no_access';
        $data['message'] = 'Sorry, you have no access to this page! Please login or activate your account.';
        $this->CI->load->view('includes/index/template', $data);
    }

}
