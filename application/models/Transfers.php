<?php

/**
 * Description of Transfer_fees
 *
 * @author flashery
 */
class Transfers extends CI_Model {

    const WITHDRAWAL_TID = 32;
    const DEPOSIT_TID = 14;

    public function __construct() {
        $this->load->database();
    }

    public function get_tranfers_row($id) {
        $query = $this->db->get_where('transfers', array('id' => $id));
        return $query->row_array();
    }

    public function get_some($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('transfers');
        return $query->result_array();
    }

    public function get_some_where($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('transfers');
        return $query->result_array();
    }

    public function get_loans_id($tranfer_id) {
        $this->db->select('id');
        $this->db->where('transfer_id', $tranfer_id);
        $query = $this->db->get('loans');
        return $query->row_array();
    }

    public function get_transfers_id($from_account_id, $to_account_id, $date) {
        $this->db->select('id');
        $this->db->where(array('from_account_id' => $from_account_id, 'to_account_id' => $to_account_id, 'date' => $date));
        $query = $this->db->get('transfers');
        return $query->row_array();
    }

    public function get_transfer_type_id($transfer_type_name) {
        $this->db->select('id');
        $this->db->where('name', $transfer_type_name);
        $query = $this->db->get('transfer_types');
        return $query->row_array();
    }

    public function get_transfer_type($id) {
        $query = $this->db->get_where('transfer_types', array('id' => $id));
        return $query->row_array();
    }

    public function get_transaction_fees() {
        $this->db->where('enabled', 1);
        $query = $this->db->get('transaction_fees');
        return $query->result_array();
    }

    public function get_some_transaction_fees_where($fields, $field_name) {
        $this->db->select($fields);
        $this->db->where('name', $field_name);
        $query = $this->db->get('transaction_fees');
        return $query->row_array();
    }

    public function update_tranfer_fees($data) {
        return $this->db->update('transfer_fees', $data);
    }

    public function grant_members_loan($data) {
        // Insert some data into tranfers tables
        $success = ($this->db->insert('transfers', $data['transfers'])) ? TRUE : FALSE;
        // Get the id of the just inserted data into tranfers table
        $tranfer_id = $this->get_transfers_id(
                $data['transfers']['from_account_id'], $data['transfers']['to_account_id'], $data['transfers']['date']
        );
        // Put the id data to the loans associative array
        $data['loans']['transfer_id'] = $tranfer_id['id'];
        // Insert the loans data into the loans table
        $success = ($this->db->insert('loans', $data['loans'])) ? TRUE : FALSE;
        // Get the id of the just inserted data into the loans table
        $loan_id = $this->get_loans_id($tranfer_id['id']);
        // Put the id data to the members_loans associative array
        $data['members_loans']['loan_id'] = $loan_id['id'];
        // Insert the members_loans data into the loans table
        $success = ($this->db->insert('members_loans', $data['members_loans'])) ? TRUE : FALSE;
        // Put the id data to the loan_payments associative array
        $data['loan_payments']['loan_id'] = $loan_id['id'];
        // Finally insert the data to the loan_payments table.
        $success = ($this->db->insert('loan_payments', $data['loan_payments'])) ? TRUE : FALSE;
        $success = $this->new_charge($data['charge']);
        // If all the data inserted to the specified table successfully. The loan is granted!
        return $success;
    }

    public function new_loan_repayment($data) {
        // Insert some data into tranfers tables
        $success = ($this->db->insert('transfers', $data['transfers'])) ? TRUE : FALSE;

        // Update Loan Payments table
        $success = $this->db->query('
            UPDATE loan_payments
                SET
                    repaid_amount = ' . $data['loan_repayment']['repaid_amount'] . ',
                    repayment_date = "' . $data['loan_repayment']['repayment_date'] . '"
                WHERE
                    loan_id = ' . $data['loan_repayment']['loan_id'] . '
           ');

        return $success;
    }

    public function new_withdrawal($data) {
        // Insert some data into tranfers tables
        $success = ($this->db->insert('transfers', $data)) ? TRUE : FALSE;
        return $success;
    }

    public function new_charge($data) {
        // Insert some data into tranfers tables
        $success = ($this->db->insert('transfers', $data)) ? TRUE : FALSE;
        return $success;
    }

    public function get_savings($username) {
        $query = $this->db->query('
            SELECT
                SUM(amount) AS amount
            FROM
                transfers
            WHERE
                type_id IN (3 , 6, 9, 20, 13, 14, 15, 16, 17, 22, 23, 24, 26, 29, 30)
                    AND to_account_id = (SELECT
                        id
                    FROM
                        accounts
                    WHERE
                        owner_name = "' . $username . '"
                            AND type_id = 5)
                ');
        return $query->row_array();
    }

    public function get_member_loan($username) {
        $query = $this->db->query('
            SELECT
                loans.id,
                SUM(loans.total_amount) amount
            FROM
                loans
                    INNER JOIN
                members_loans ON loans.id = members_loans.loan_id
                    INNER JOIN
                members ON members.id = members_loans.member_id
                    INNER JOIN
                transfers ON transfers.id = loans.transfer_id
                    INNER JOIN
                loan_payments ON loan_payments.loan_id = loans.id
            WHERE
                members.id = (SELECT id FROM users WHERE username = "' . $username . '")
                    AND loan_payments.repaid_amount = 0
                    AND loan_payments.repaid_amount != loans.total_amount
        ');
        return $query->row_array();
    }

    public function get_withdrawals($username) {
        $query = $this->db->query('SELECT sum(amount) AS amount FROM transfers WHERE type_id = ' . $this::WITHDRAWAL_TID . ' '
                . 'AND from_account_id = (SELECT
                        id
                    FROM
                        accounts
                    WHERE
                        owner_name = "' . $username . '"
                            AND type_id = 5)');
        return $query->row_array();
    }

    public function new_deposit($data) {
        $success = ($this->db->insert('transfers', $data)) ? TRUE : FALSE;
        return $success;
    }

    public function get_deposits($username) {
        $query = $this->db->query('
                SELECT sum(amount) AS amount FROM transfers WHERE type_id = ' . $this::DEPOSIT_TID . '
                    AND to_account_id = (SELECT
                        id
                FROM
                    accounts
                WHERE
                    owner_name = "' . $username . '"
                        AND type_id = 5)');
        return $query->row_array();
    }

}
