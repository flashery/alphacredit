<?php

/**
 * Description of Custom_Fields
 *
 * @author flashery
 */
class Custom_Fields extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_search_account($field, $string_value_id) {

        $query = $this->db->query('SELECT
                                      string_value
                                  FROM
                                      custom_field_values
                                  WHERE
                                      custom_field_values.string_value LIKE "' . $field . '%"
                                  AND
                                  		custom_field_values.field_id = '. $string_value_id .'
                                  LIMIT 30
                                 ');

        return $query->result_array();
    }

    public function get_custom_fields() {
        $query = $this->db->get_where('custom_fields', array('subclass' => 'member'));
        return $query;
    }

    public function get_some_cf($fields) {
        $this->db->select($fields);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('custom_fields');
        return $query->result_array();
    }

    public function get_cf_values($username, $field_id) {
        // Get the user id
        $user_id = $this->db->query('SELECT id FROM users WHERE username = "' . $username . '"')->row_array();
        $query = $this->db->query('SELECT string_value FROM custom_field_values cfv WHERE member_id = ' . $user_id['id'] . ' AND field_id = ' . $field_id . '');
        return $query->row_array();
    }

    public function get_cf_possible_values($field_id) {
        $query = $this->db->get_where('custom_field_possible_values', array('field_id' => $field_id));
        return $query->result_array();
    }

    public function update_cf_values($datas, $cf_fields) {
        $success = FALSE;

        foreach ($cf_fields as $cf_field) {

            $query = $this->db->query(
                            'SELECT field_id FROM custom_field_values '
                            . 'WHERE member_id = ' . $datas['member_id']
                            . ' AND field_id = ' . $cf_field['id'] . ''
                    )->row_array();

            if (!empty($query['field_id'])) {
                $success = $this->db->query('UPDATE custom_field_values '
                        . 'SET string_value="' . $cf_field['string_value'] . '" '
                        . 'WHERE member_id = ' . $datas['member_id'] . ' '
                        . 'AND field_id = ' . $cf_field['id'] . '');
            } else {
                $success = $this->db->query('INSERT INTO custom_field_values '
                        . '(subclass, field_id, string_value, member_id) '
                        . 'values ("member", ' . $cf_field['id'] . ',"' . $cf_field['string_value'] . '", ' . $datas['member_id'] . ')');
            }
        }
        return $success;
    }

    public function new_cf_values($cfv_fields) {
        $success = FALSE;
        foreach ($cfv_fields as $cfv_field) {
            $success = $this->db->insert('custom_field_values', $cfv_field);
        }
        return $success;
    }

}
