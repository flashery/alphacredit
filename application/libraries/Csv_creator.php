<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Csv_creator
 *
 * @author flashery
 */
class Csv_creator {
    protected $CI = null;
    public function __construct() {
        $this->CI = & get_instance();
    }

    public function make_csv($reports, $file_name = 'export') {

        $this->CI->load->dbutil();
        $this->CI->load->helper('file');

        $file_path = './documents/' . $file_name . '.csv';
        $new_report = $this->dbutil->csv_from_result($reports);

        if (!write_file($file_path, $new_report, 'w+')) {
            $error = array('error' => 'Unable to write file! ' . $file_path);
            $this->CI->session->set_flashdata('error', $error['error']);
        } else {
            return base_url() . 'documents/' . $file_name . '.csv';
        }
    }

}
