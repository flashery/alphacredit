<?php

/**
 * Description of Login
 *
 * @author flashery
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model
        $this->load->model('users');
        $this->load->model('images');
    }

    public function index() {
        if (!$this->check_login()) {
            $data['title'] = 'Login Form';
            $this->view_loader->load($this, 'index', $data);
        }
    }

    public function validate_login() {

        // Check form security
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->view_loader->load($this, 'index');
        } else {
            $data = array(
                'destination' => $this->input->post('username'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );

            $query = $this->users->get_user_login('id,subclass,username,password,', $data);

            if ($query == NULL) {
                $data['message'] = 'Sorry, username or password is not correct';
                $this->view_loader->load($this, 'validate_login', $data);
            } else {
                $this->success($query, $data);
            }
        }
    }

    private function success($query, $data) {

        $user_id = $this->users->get_id($data['username']);
        $user_image = $this->images->get_profile_image($user_id['id']);

        $session_data = array(
            'user_id' => $query['id'],
            'subclass' => $query['subclass'],
            'username' => $query['username'],
            'role' => $query['role'],
            'user_image' => $user_image['image'],
            'logged_in' => true
        );

        $this->session->set_userdata($session_data);

        if ($query['subclass'] === 'A') {
            redirect('admin');
        } else {
            if ($query['role'] == permission::INACTIVE_MEMBER) {
                // Load admin no access
                $data['message'] = 'Sorry, administrator must set you as an active member first.';
                $this->view_loader->load_other($this, 'errors', 'no_access', $data);

            } else {
                redirect('index/members_profile');
            }
        }
    }

    // This function will check the user accesing the admin page if still logged in
    // or if has access to the admin page
    private function check_login() {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            return FALSE;
        } else {
            if ($this->session->userdata('subclass') === 'A') {
                redirect('admin');
            } else {
                if ($this->session->userdata('role') === Permission::INACTIVE_MEMBER) {
                    // Load admin no access
                    $data['message'] = 'Sorry, administrator must set you as an active member first.';
                    $this->view_loader->load_other($this, 'errors', 'no_access', $data);
                } else {
                    redirect('index/members_profile');
                }
            }
        }
    }
}
