<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of signup
 *
 * @author flashery
 */
class Signup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('custom_fields');
    }

    public function index() {
        // Check form security
        $this->validate_form();
        if ($this->form_validation->run() === FALSE) {
            // It failes. Load signup again
            $data = array(
                'title' => 'Create Account',
            );
            $this->view_loader->load($this, 'index', $data);
        } else {
            $this->new_user();
        }
    }

    public function new_user() {
        // Insert the data to the database
        $members_data = array(
            'subclass' => 'M',
            'creation_date' => date('Y-m-d'),
            'group_id' => 6,
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email')
        );

        $members_query = $this->members->new_member($members_data);
        $members_id = $this->members->get_id($members_data['email']);

        if ($members_query) {

            $users_data = array(
                'id' => $members_id['id'],
                'subclass' => 'M',
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );

            $user_query = $this->users->new_user($users_data);
            if ($user_query) {
                // Successfully inserted. Show success message
                $data['title'] = 'Create Account';
                //$this->load->view('includes/signup/template', $data);
                $this->view_loader->load($this, 'success', $data);
            }
        } else {
            echo 'Error inserting data!';
        }
    }

    private function validate_form() {

        $data = array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.'
        );
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[users.username]', $data);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[members.email]', $data);
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
    }

}
