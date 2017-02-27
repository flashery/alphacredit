<?php

/**
 * Description of M_Login
 *
 * @author flashery
 */
class Users extends CI_Model {

    private $salt = "";

    const TABLE_NAME = 'users';

    public function __construct() {
        $this->load->database();
    }

    public function get_id($username) {
        $this->db->select('id');
        $this->db->where('username', $username);
        return $this->db->get(self::TABLE_NAME)->row_array();
    }

    public function get_all() {
        $this->db->order_by($field_name, 'DESC');
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    public function get_some($fields, $limit, $start, $field_name = 'username') {
        $this->db->select($fields);
        $this->db->limit($limit, $start);
        $this->db->order_by($field_name, 'ASC');
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    // Get user logging in identity
    public function get_user_profile($username) {
        $query = $this->db->query('
            SELECT 
                id,
                subclass,
                username,
                (SELECT 
                        MAX(CASE
                                WHEN members.id = users.id THEN members.name
                            END)
                    FROM
                        members) AS name,
                (SELECT 
                        MAX(CASE
                                WHEN members.id = users.id THEN members.email
                            END)
                    FROM
                        members) AS email
            FROM
                users
            WHERE
            
                users.username = "' . $username . '"
            ');
        return $query->row_array();
    }

    // Get user logging in identity
    public function get_user_by_id($id) {
        $query = $this->db->query('
            SELECT 
                id,
                subclass,
                username,
                (SELECT 
                        MAX(CASE
                                WHEN members.id = users.id THEN members.name
                            END)
                    FROM
                        members) AS name,
                (SELECT 
                        MAX(CASE
                                WHEN members.id = users.id THEN members.email
                            END)
                    FROM
                        members) AS email
            FROM
                users
            WHERE
                users.id = ' . $id . '
            ');
        return $query->row_array();
    }

    public function get_user_login($fields, $data) {

        $query = $this->db->query('
            SELECT 
                ' . $fields . '
                (SELECT 
                        members.group_id    
                    FROM
                        members
                    WHERE
                        members.id = users.id) AS role
            FROM
                ' . self::TABLE_NAME . '
            WHERE
                username = "' . $data['username'] . '"');

        $array = $query->row_array();

        if (password_verify($data['password'], $array['password'])) {
            $array['password'] = NULL;
            return $array;
        } else {
            return NULL;
        }
    }

    // Insert new user signing up
    public function new_user($users_data) {

        $salt = $this->create_salt();
        $users_data['salt'] = $salt;

        $password = $this->encrypt_password($users_data['password'], $salt);

        $users_data['password'] = $password;

        return $this->db->insert(self::TABLE_NAME, $users_data);
    }

    // Update users data
    public function update_users($data) {

        if (isset($data['password']) && $data['password'] !== '') {
            $salt = $this->create_salt();

            $password = $this->encrypt_password($data['password'], $salt);

            $data['password'] = $password;
            $data['salt'] = $salt;
        }

        $this->db->where('username', $data['username']);
        return $this->db->update(self::TABLE_NAME, $data);
    }

    /*     * ***************************************
     *     Password encryption functions     *
     * ************************************** */

    private function encrypt_password($password, $salt) {

        $this->create_salt();

        $options = array(
            'cost' => 11,
            'salt' => $salt
        );

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    private function create_salt() {
        return mcrypt_create_iv(32, MCRYPT_DEV_URANDOM);
    }

}
