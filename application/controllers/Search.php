<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Search
 *
 * @author flashery
 */
class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('custom_fields');
        $this->load->model('accounts');
    }

    public function get_search_data($field = NULL, $string_value_id) {

        $datas = $this->custom_fields->get_search_account($field, $string_value_id);

        foreach ($datas as $row) {
            $suggest[] = $row['string_value'];
        }
        echo json_encode($suggest);
    }

    public function do_search($name) {

        $clear_str = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($name))))));
        $account = $this->accounts->search_account(str_replace('%20', ' ', $clear_str));
        $table = '<table>';
        $table .= '<thead>';
        $table .= '<tr>';
        foreach ($account as $key => $value) {
            if ($key !== 'id') {
                $table .= '<th>' . ucwords(str_replace('_', ' ', $key)) . '</th>';
            }
        }
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        $table .= '<tr>';
        foreach ($account as $key => $value) {
            if ($key !== 'id') {
                if ($key === 'Account Name') {
                    $table .= '<td><a href="' . base_url() . 'admin/accounts/details/' . $account['id'] . '">' . ucwords(str_replace('_', ' ', $value)) . '</a></td>';
                } else {
                    $table .= '<td>' . ucwords(str_replace('_', ' ', $value)) . '</td>';
                }
            }
        }
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        echo $table;
    }

}
