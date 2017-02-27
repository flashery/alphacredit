<?php

/**
 * Description of Accounts
 *
 * @author flashery
 */
class Accounts extends CI_Model {

    const TABLE_NAME = 'accounts';

    public function __construct() {
        $this->load->database();
    }

    public function check_account($username) {
        $query = $this->db->query('
            SELECT 
                *
            FROM
                accounts
            WHERE
                owner_name = "' . $username . '"
                            ');

        return $query->row_array();
    }

    public function create_new_account($data) {
        return $this->db->insert(self::TABLE_NAME, $data);
    }

    public function get_savings_account_id($username) {
        $query = $this->db->query('
            SELECT 
                id
            FROM
                accounts
            WHERE
                owner_name = "' . $username . '"
                    AND type_id = 5
        ');
        return $query->row_array();
    }

    public function get_withdrawal_account_id($username) {
        $query = $this->db->query('
            SELECT 
                id
            FROM
                accounts
            WHERE
                owner_name = "' . $username . '"
                    AND type_id = 8
        ');
        return $query->row_array();
    }

    public function search_account($string_value) {
        $query = $this->db->query('
            SELECT 
                m.id,
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NAME" THEN cfv.string_value
                END) AS "Account Name",
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NO" THEN cfv.string_value
                END) AS "Account Number",
                MAX(CASE
                    WHEN cf.name = "Mobile phone" THEN cfv.string_value
                END) AS "Mobile Number",
                MAX(CASE
                    WHEN cf.name = "ID NUMBER" THEN cfv.string_value
                END) AS "ID Number"
            FROM
                custom_field_values cfv
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
                    INNER JOIN
                members m ON m.id = cfv.member_id
            WHERE
                m.id = (select custom_field_values.member_id from custom_field_values where string_value = "' . $string_value . '")
        ');

        return $query->row_array();
    }

    public function get_account($id) {
        $query = $this->db->query('
            SELECT 
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NAME" THEN cfv.string_value
                END) AS "Account Name",
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NO" THEN cfv.string_value
                END) AS "Account Number",
                MAX(CASE
                    WHEN cf.name = "Mobile phone" THEN cfv.string_value
                END) AS "Mobile phone",
                MAX(CASE
                    WHEN cf.name = "ID NUMBER" THEN cfv.string_value
                END) AS "ID Number"
            FROM
                custom_field_values cfv
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
                    INNER JOIN
                members m ON m.id = cfv.member_id
            WHERE
                m.id = ' . $id . '
        ');

        return $query->row_array();
    }

    public function get_account_by_id($id) {
        $query = $this->db->query('
            SELECT 
                m.id,
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NAME" THEN cfv.string_value
                END) AS "Account Name",
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NO" THEN cfv.string_value
                END) AS "Account Number",
                MAX(CASE
                    WHEN cf.name = "Mobile phone" THEN cfv.string_value
                END) AS "Mobile phone",
                MAX(CASE
                    WHEN cf.name = "ID NUMBER" THEN cfv.string_value
                END) AS "ID Number"
            FROM
                accounts a
                    INNER JOIN
                members m ON m.id = a.member_id
                    INNER JOIN
                custom_field_values cfv ON cfv.member_id = m.id
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
            WHERE
                a.id = ' . $id . '
        ');

        return $query->row_array();
    }

    public function get_max_acct_number() {
        $query = $this->db->query('
            SELECT 
                MAX(string_value) AS number
            FROM
                custom_field_values
            WHERE
                field_id = 14
        ');
        return $query->row_array();
    }

    public function get_account_column($field) {
        $query = $this->db->query('
            SELECT 
                ' . $field . '
            FROM
                custom_field_values cfv
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
                    INNER JOIN
                members m ON m.id = cfv.member_id
            GROUP BY m.name
            ');
        return $query->result();
    }

    public function get_account_numbers() {
        $query = $this->db->query('
            SELECT 
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NAME" THEN cfv.string_value
                END) AS "Account Name",
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NO" THEN cfv.string_value
                END) AS "Account Number",
                MAX(CASE
                    WHEN cf.name = "Gender" THEN cfv.string_value
                END) AS "Gender",
                MAX(CASE
                    WHEN cf.name = "Mobile phone" THEN cfv.string_value
                END) AS "Mobile Number",
                MAX(CASE
                    WHEN cf.name = "ID NUMBER" THEN cfv.string_value
                END) AS "ID Number"
            FROM
                custom_field_values cfv
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
                    INNER JOIN
                members m ON m.id = cfv.member_id
            GROUP BY m.name
            ');
        return $query->result_array();
    }

    public function get_due_loan_acct() {
        $query = $this->db->query('
            SELECT 
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NAME" THEN cfv.string_value
                END) AS "Account Name",
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NO" THEN cfv.string_value
                END) AS "Account Number",
                MAX(CASE
                    WHEN cf.name = "Mobile phone" THEN cfv.string_value
                END) AS "Mobile Number"
            FROM
                custom_field_values cfv
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
                    INNER JOIN
                members m ON m.id = cfv.member_id
                    INNER JOIN
                members_loans ml ON ml.member_id = m.id
                    INNER JOIN
                loan_payments lp ON lp.loan_id = ml.loan_id
                    INNER JOIN
                loans l ON l.id = lp.loan_id
            WHERE
                lp.expiration_date = DATE_ADD(CURDATE(), INTERVAL 5 DAY)
                    AND lp.repaid_amount < l.total_amount
            GROUP BY m.name;
        ');

        return $query->result_array();
    }

    public function get_expired_loan_acct() {
        $query = $this->db->query('
            SELECT 
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NAME" THEN cfv.string_value
                END) AS "Account Name",
                MAX(CASE
                    WHEN cf.name = "ACCOUNT NO" THEN cfv.string_value
                END) AS "Account Number",
                MAX(CASE
                    WHEN cf.name = "Mobile phone" THEN cfv.string_value
                END) AS "Mobile Number"
            FROM
                custom_field_values cfv
                    INNER JOIN
                custom_fields cf ON cf.id = cfv.field_id
                    INNER JOIN
                members m ON m.id = cfv.member_id
                    INNER JOIN
                members_loans ml ON ml.member_id = m.id
                    INNER JOIN
                loan_payments lp ON lp.loan_id = ml.loan_id
                    INNER JOIN
                loans l ON l.id = lp.loan_id
            WHERE
                lp.expiration_date <= NOW()
                    AND lp.repaid_amount < l.total_amount
            GROUP BY m.name;
        ');

        return $query->result_array();
    }

    public function get_accounts($limit, $start) {
        $this->db->select('members.id');
        $this->db->select_max('(CASE WHEN custom_fields.name = "ACCOUNT NAME" THEN custom_field_values.string_value END)', 'Account Name');
        $this->db->select_max('(CASE WHEN custom_fields.name = "ACCOUNT NO" THEN custom_field_values.string_value END)', 'Account Number');
        $this->db->select_max('(CASE WHEN custom_fields.name = "Mobile phone" THEN custom_field_values.string_value END)', 'Mobile Number');
        $this->db->select_max('(CASE WHEN custom_fields.name = "ID NUMBER" THEN custom_field_values.string_value END)', 'ID Number');
        $this->db->join('custom_fields', 'custom_fields.id = custom_field_values.field_id', 'inner');
        $this->db->join('members', 'members.id = custom_field_values.member_id', 'inner');
        $this->db->where('members.group_id', 5);
        $this->db->group_by("members.name");
        $this->db->limit($limit, $start);
        $query = $this->db->get('custom_field_values');
        return $query->result_array();
    }

    public function get_some($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    public function get_some_account_types_where($fields, $field_name) {
        $this->db->select($fields);
        $this->db->where('name', $field_name);
        $query = $this->db->get('transaction_fees');
        return $query->row_array();
    }

    public function get_account_fees() {
        $query = $this->db->get('account_fees');
        return $query->result_array();
    }

    public function update_account_fees() {
        $query = $this->dget_account_feesb->get('account_fees');
        return $query->result_array();
    }

    /*
      public function get_null_accounts() {
      $query = $this->db->query('
      SELECT
     *
      FROM
      custom_field_values
      WHERE
      field_id = 14 AND string_value IS NULL
      OR string_value = ""
      ');

      return $query->result_array();
      }

      public function update_account_number($id, $account_number) {
      $query = $this->db->query('
      UPDATE custom_field_values
      SET
      string_value = ' . $account_number . '
      WHERE
      field_id = 14 AND id = ' . $id . '
      ');

      return $query;
      }
     */
}
