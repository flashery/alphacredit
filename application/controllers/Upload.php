<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Upload
 *
 * @author flashery
 */
class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('members');
        $this->load->model('images');
    }

    public function file_view() {
        $this->load->view('file_view', array('error' => ' '));
    }

    public function upload_image() {

        $config['upload_path'] = './images/profile';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_name'] = $this->input->post('username');
        $config['overwrite'] = TRUE;
        $config['max_size'] = 100;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('userfile')) {
            $user_id = $this->members->get_id($this->input->post('email'));

            $image = addslashes($_FILES['userfile']['tmp_name']);
            $image = file_get_contents($image);

            if (empty($this->images->get_profile_image($user_id['id']))) {
                $this->new_image($image, $user_id['id'], $this->upload->data());
            } else {
                $this->update_image($image, $user_id['id'], $this->upload->data());
            }
        } else {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);
            redirect('admin/users');
        }
    }

    private function new_image($image, $user_id, $image_data) {
        $data = array(
            'subclass' => 'mbr',
            'name' => $image_data['file_name'],
            'content_type' => $image_data['file_type'],
            'image_size' => $_FILES['userfile']['size'],
            'last_modified' => date("Y-m-d h:i:sa"),
            'thumbnail_size' => '',
            'image' => $image,
            'thumbnail' => '',
            'member_id' => $user_id,
            'order_number' => 1,
            'caption' => '',
            'ad_id' => ''
        );

        if ($this->images->new_image($data)) {
            $success = 'Image successfully uploaded';
            $this->session->set_flashdata('success', $success);
            redirect('admin/users');
        } else {
            $error = array('error' => 'Failed insert!');
            $this->session->set_flashdata('error', $error['error']);
            redirect('admin/users');
        }
    }

    private function update_image($image, $user_id, $image_data) {
        $data = array(
            'subclass' => 'mbr',
            'name' => $image_data['file_name'],
            'content_type' => $image_data['file_type'],
            'image_size' => $_FILES['userfile']['size'],
            'last_modified' => date("Y-m-d h:i:sa"),
            'thumbnail_size' => '',
            'image' => $image,
            'thumbnail' => '',
            'member_id' => $user_id,
            'order_number' => 1,
            'caption' => '',
            'ad_id' => ''
        );
        if ($this->images->update_image($data)) {
            $success = 'Image successfully updated';
            $this->session->set_flashdata('success', $success);
            redirect('admin/users');
        } else {
            $error = array('error' => 'Failed update!');
            $this->session->set_flashdata('error', $error['error']);
            redirect('admin/users');
        }
    }

    public function upload_signature() {

        $config['upload_path'] = './images/profile';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_name'] = $this->input->post('username') . '-signature';
        $config['overwrite'] = TRUE;
        $config['max_size'] = 100;
        $config['max_width'] = 325;
        $config['max_height'] = 100;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('userfile')) {
            $user_id = $this->members->get_id($this->input->post('email'));

            $image = addslashes($_FILES['userfile']['tmp_name']);
            $image = file_get_contents($image);

            if (empty($this->images->get_signature_image($user_id['id']))) {
                $this->new_signature($image, $user_id['id'], $this->upload->data());
            } else {
                $this->update_signature($image, $user_id['id'], $this->upload->data());
            }
        } else {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);
            redirect('admin/users');
        }
    }

    private function new_signature($image, $user_id, $image_data) {

        $data = array(
            'subclass' => 'mbr',
            'name' => $image_data['file_name'],
            'content_type' => $image_data['file_type'],
            'image_size' => $_FILES['userfile']['size'],
            'last_modified' => date("Y-m-d h:i:sa"),
            'thumbnail_size' => '',
            'image' => $image,
            'thumbnail' => '',
            'member_id' => $user_id,
            'order_number' => 2,
            'caption' => '',
            'ad_id' => ''
        );

        if ($this->images->new_signature($data)) {
            $success = 'Image successfully uploaded';
            $this->session->set_flashdata('success', $success);
            redirect('admin/users');
        } else {
            $error = array('error' => 'Failed insert!');
            $this->session->set_flashdata('error', $error['error']);
            redirect('admin/users');
        }
    }

    private function update_signature($image, $user_id, $image_data) {
        $data = array(
            'subclass' => 'mbr',
            'name' => $image_data['file_name'],
            'content_type' => $image_data['file_type'],
            'image_size' => $_FILES['userfile']['size'],
            'last_modified' => date("Y-m-d h:i:sa"),
            'thumbnail_size' => '',
            'image' => $image,
            'thumbnail' => '',
            'member_id' => $user_id,
            'order_number' => 2,
            'caption' => '',
            'ad_id' => ''
        );

        if ($this->images->update_signature($data)) {
            $success = 'Image successfully updated';
            $this->session->set_flashdata('success', $success);
            redirect('admin/users');
        } else {
            $error = array('error' => 'Failed update!');
            $this->session->set_flashdata('error', $error['error']);
            redirect('admin/users');
        }
    }

}
