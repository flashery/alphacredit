<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reports
 *
 * @author flashery
 */
class Reports extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_members_witdrawal($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name, transfers.amount, transfers.date
            FROM
                transfers
                    INNER JOIN
                accounts ON transfers.to_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND transfers.type_id = 32
            ORDER BY transfers.date DESC
       ');
        return $query;
    }

    // Loan Reports
    public function get_members_loans($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.repaid_amount < loans.total_amount
            UNION SELECT 
                "Total",
                "",
                "",
                sum(loans.total_amount),
                sum(loan_payments.repaid_amount),
                "",
                sum(loans.total_amount - loan_payments.repaid_amount)
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.repaid_amount < loans.total_amount
        ');
        return $query;
    }

    public function get_repaid_loans($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.repaid_amount > 0
            UNION ALL SELECT 
                "Total",
                "",
                "",
                sum(loans.total_amount),
                sum(loan_payments.repaid_amount),
                "",
                sum(loans.total_amount - loan_payments.repaid_amount)
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.repaid_amount > 0
            ');
        return $query;
    }

    public function get_expired_loans($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                (transfers.date > "' . $start_date . '"
                   AND transfers.date < "' . $end_date . '")
                   AND loan_payments.expiration_date <= NOW()
                   AND loan_payments.repaid_amount < loans.total_amount
            UNION ALL SELECT 
                "Total",
                "",
                "",
                sum(loans.total_amount),
                sum(loan_payments.repaid_amount),
                "",
                sum(loans.total_amount - loan_payments.repaid_amount)
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.expiration_date <= NOW()
                    AND loan_payments.repaid_amount < loans.total_amount
            ');
        return $query;
    }

    public function get_due_loans($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.expiration_date = DATE_ADD(CURDATE(), INTERVAL 5 DAY)
            UNION SELECT 
                "Total",
                "",
                "",
                SUM(loans.total_amount),
                SUM(loan_payments.repaid_amount),
                "",
                SUM(loans.total_amount - loan_payments.repaid_amount)
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND loan_payments.expiration_date = DATE_ADD(CURDATE(), INTERVAL 5 DAY)
        ');
        return $query;
    }

    public function get_members_savings($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                    members.name, transfers.amount, transfers.date
                FROM
                    transfers
                WHERE
                    (transfers.date > "' . $start_date . '"
                        AND transfers.date < "' . $end_date . '")
                        AND transfers.type_id in (3, 6, 9, 20, 13, 14, 15, 16, 17, 22, 23, 24, 26, 29, 30)
       ');
        return $query;
    }

    public function get_members_deposits($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name, `transfers`.`amount`, transfers.date
            FROM
                transfers
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND transfers.type_id = 14
            GROUP BY transfers.date DESC
            ');
        return $query;
    }

    public function get_members_investments($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                members.name, `transfers`.`amount`, transfers.date
            FROM
                transfers
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND transfers.type_id = 2
            GROUP BY members.id)'
        );
        return $query;
    }

    public function get_members_charges($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.amount, transfers.description, transfers.date
            FROM
                transfers
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                    AND transfers.type_id = 33
            GROUP BY transfers.date DESC
        ');

        return $query;
    }

    public function get_cumulative_reports($start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfer_types.name AS Name, sum(transfers.amount) AS "Total Amount"
            FROM
                transfers
                    INNER JOIN
                transfer_types ON transfer_types.id = transfers.type_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
            GROUP BY transfers.type_id
        ');
        return $query;
    }

    /*     * *************************************************************************
     * ************************** Individual Reports ****************************
     * ************************************************************************* */

    public function get_individual_witdrawal($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id, members.name, transfers.amount, transfers.date
            FROM
                transfers
                    INNER JOIN
                accounts ON transfers.from_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
            (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND transfers.type_id = 32
                    AND members.id = ' . $member_id . '
            ORDER BY transfers.date DESC
       ');
        return $query;
    }

    public function get_individual_loans($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id,
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND members.id = ' . $member_id . ' 
                    AND loan_payments.repaid_amount < loans.total_amount
            UNION SELECT 
                "",
                "Total",
                "",
                "",
                SUM(loans.total_amount),
                SUM(loan_payments.repaid_amount),
                "",
                SUM(loans.total_amount - loan_payments.repaid_amount)
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND members.id = ' . $member_id . ' 
                    AND loan_payments.repaid_amount < loans.total_amount
        ');
        return $query;
    }

    public function get_individual_r_loans($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id,
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND members.id = ' . $member_id . ' 
                    AND loan_payments.repaid_amount > 0
            UNION SELECT 
                "",
                "Total",
                "",
                "",
                SUM(loans.total_amount),
                SUM(loan_payments.repaid_amount),
                "",
                SUM(loans.total_amount - loan_payments.repaid_amount)
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
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND members.id = ' . $member_id . ' 
                    AND loan_payments.repaid_amount > 0
        ');
        return $query;
    }
    
    public function get_individual_savings($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id, members.name, transfers.amount, transfers.date
            FROM
                transfers
                    INNER JOIN
                accounts ON transfers.to_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND transfers.type_id in (3, 6, 9, 20, 13, 14, 15, 16, 17, 22, 23, 24, 26, 29, 30)
                    AND members.id = ' . $member_id . '
            GROUP BY transfers.date DESC
       ');
        return $query;
    }

    public function get_individual_deposits($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id, members.name, transfers.amount, transfers.date
            FROM
                transfers
                    INNER JOIN
                accounts ON transfers.to_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND transfers.type_id = 14
                    AND members.id = ' . $member_id . '
            GROUP BY transfers.date DESC
            ');
        return $query;
    }

    public function get_individual_investments($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id, members.name, `transfers`.`amount`, transfers.date
            FROM
                transfers
                    INNER JOIN
                accounts ON transfers.from_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND transfers.type_id = 2
                   AND members.id = ' . $member_id . '
            GROUP BY transfers.date DESC
        ');
        return $query;
    }

    public function get_individual_charges($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id, transfers.amount, transfers.description, transfers.date
            FROM
                transfers
                    INNER JOIN
                accounts ON transfers.from_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND transfers.type_id = 33
                    AND members.id = ' . $member_id . '
            GROUP BY transfers.date DESC
            ');

        return $query;
    }

    public function get_individual_cr($member_id, $start_date, $end_date) {
        $query = $this->db->query('
            SELECT 
                transfers.id, transfer_types.name AS Name,
                SUM(transfers.amount) AS "Total Amount"
            FROM
                transfers
                    INNER JOIN
                transfer_types ON transfer_types.id = transfers.type_id
                    INNER JOIN
                accounts ON transfers.from_account_id = accounts.id
                    INNER JOIN
                members ON members.id = accounts.member_id
            WHERE
                (transfers.date > "' . $start_date . '"
                    AND transfers.date < "' . $end_date . '")
                AND members.id = ' . $member_id . '
            GROUP BY transfers.type_id
        ');
        return $query;
    }

    public function get_single_loan($transfer_id) {
        $query = $this->db->query('
            SELECT 
                transfers.id,
                members.name,
                transfers.date,
                loan_payments.expiration_date,
                loans.total_amount,
                loan_payments.repaid_amount,
                loan_payments.repayment_date,
                (loans.total_amount - loan_payments.repaid_amount) AS balance
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
                loan_payments.repaid_amount < loans.total_amount
                    AND transfers.id = ' . $transfer_id . '
        ');
        return $query->row_array();
    }

}
