<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Images
 *
 * @author flashery
 */
class Images extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_profile_image($user_id) {
         $query = $this->db->query('
                    SELECT 
                       image
                    FROM
                        images
                    WHERE
                        order_number = 1
                            AND member_id = ' . $user_id . '
                ');
        return $query->row_array();
    }

    public function update_image($data) {
        $this->db->where('member_id', $data['member_id']);
        $this->db->where('order_number', $data['order_number']);
        return $this->db->update('images', $data);
    }

    public function new_image($data) {
        return $this->db->insert('images', $data);
    }

    public function get_signature_image($user_id) {
        $query = $this->db->query('
                    SELECT 
                       image
                    FROM
                        images
                    WHERE
                        order_number = 2
                            AND member_id = ' . $user_id . '
                ');
        return $query->row_array();
    }

    public function update_signature($data) {
        $this->db->where('member_id', $data['member_id']);
        $this->db->where('order_number', $data['order_number']);
        return $this->db->update('images', $data);
    }

    public function new_signature($data) {
        return $this->db->insert('images', $data);
    }

}
