<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author flashery
 */
class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('images');
        $this->load->model('custom_fields');

        $this->load->library('table');
    }

    // 	This function will logout the current logged in user
    public function logout() {
        $this->session->sess_destroy();
        redirect('index');
    }

    public function index() {
        // Load index page
        $data['title'] = 'Index';
        $this->view_loader->load($this, 'index', $data);
    }

    public function members_profile($name = NULL) {
        if ($this->permission->check_member_permission()) {
            $username = ($name == NULL) ? $this->session->userdata('username') : $name;

            $user_profile = $this->users->get_user_profile($username);
            $member_profile = $this->members->get_member_profile($user_profile['id']);
            $profile_image = $this->images->get_profile_image($user_profile['id']);
            $custom_fields = $this->custom_fields->get_custom_fields();
            $data = array(
                'title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                'user_profile' => $user_profile,
                'member_profile' => $member_profile,
                'profile_image' => $profile_image,
                'custom_fields' => $custom_fields,
                'main_content' => 'index/members_profile',
            );
            // Load admin page
            $this->load->view('includes/index/template', $data);
        }
    }

    public function edit_profile($name = NULL) {
        if ($this->permission->check_member_permission()) {
            $username = ($name == NULL) ? $this->session->userdata('username') : $name;
            $user_profile = $this->users->get_user_profile($username);
            $member_profile = $this->members->get_member_profile($user_profile['id']);
            $profile_image = $this->images->get_profile_image($user_profile['id']);
            $data = array(
                'title' => ucwords(str_replace('_', ' ', __FUNCTION__)),
                'user_profile' => $user_profile,
                'member_profile' => $member_profile,
                'profile_image' => $profile_image,
                'main_content' => 'index/edit_profile',
            );
            // Load admin page
            $this->load->view('includes/index/template', $data);
        }
    }
}
