<?php

/**
 * Description of Members
 *
 * @author flashery
 */
class Members extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_id($email) {
        return $this->db->query('Select id FROM members WHERE email = "' . $email . '"')->row_array();
    }

    public function record_count() {
        return $this->db->count_all("members");
    }

    public function get_all() {
        $query = $this->db->get('members');
        return $query->result_array();
    }

    public function get_some($limit, $start) {

        $query = $this->db->query('
        SELECT 
            images.image,
            (SELECT 
                    MAX(CASE
                            WHEN users.id = members.id THEN users.username
                        END)
                FROM
                    users) AS username,
            members.name,
            members.email,
            members.member_activation_date,
            MAX(CASE
                WHEN members.group_id = ' . permission::ADMINISTARTOR . ' THEN "Administrator"
                WHEN members.group_id = ' . permission::MANAGER . ' THEN "Manager"
                WHEN members.group_id = ' . permission::DISABLED_ADMIN . ' THEN "Disabled Admin"
                WHEN members.group_id = ' . permission::REMOVED_ADMIN . ' THEN "Removed Admin"
                WHEN members.group_id = ' . permission::MEMBER . ' THEN "Member"
                WHEN members.group_id = ' . permission::DISABLED_MEMBER . ' THEN "Disabled Member"
                WHEN members.group_id = ' . permission::REMOVED_MEMBER . ' THEN "Removed Member"
            END) AS role,
            MAX(CASE
                WHEN members.group_id = 5 THEN "Active"
                WHEN members.group_id = 6 THEN "Inactive"
            END) AS status
                FROM
                    members
                           LEFT JOIN
                    images ON images.member_id = members.id
                GROUP BY members.name
                LIMIT ' . $start . ' , ' . $limit . '
        ');

        return $query->result_array();
    }

    // Get user logging in identity
    public function get_member_profile($user_id) {
        // Get the user id
        $query = $this->db->get_where('members', array('id' => $user_id));
        return $query->row_array();
    }

    public function get_some_members_info($fields, $username) {
        $query = $this->db->query('
            SELECT 
                ' . $fields . '
            FROM
                members
            WHERE
                members.id = (SELECT 
                        id
                    FROM
                        users
                    WHERE
                username = "' . $username . '")
            ');
        return $query->row_array();
    }

    public function new_member($member_data) {
        return $this->db->insert('members', $member_data);
    }

    // Update users data
    public function update_members($data) {
        $this->db->where('email', $data['email']);
        return $this->db->update('members', $data);
    }

    // Update users data
    public function update_member_status($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('members', $data);
    }

}
