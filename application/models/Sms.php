<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sms
 *
 * @author flashery
 */
class Sms extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_settings() {
        $query = $this->db->get('sms_settings');
        return $query->result_array();
    }
	
	public function update_settings($data) {
		$this->db->select_max('id', 'id');
		$query = $this->db->get('sms_settings')->row_array();
		for($i = 0; $i < $query['id']; $i++) {
			$this->db->set('name', $data[$i]);
		}
		$this->db->update('sms_settings', $data);
	}
}
