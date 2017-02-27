<?php

/**
 * Description of Loan
 *
 * @author flashery
 */
class Loan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reports');
        $this->load->model('transfers');
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('accounts');
        $this->load->helper('date');
    }

    public function grant_loan($username) {
        $savings_account_id = $this->accounts->get_savings_account_id($username);
        $users_profile = $this->users->get_user_profile($username);
        $transfer_type = $this->transfers->get_transfer_type_id('Loans');
        $charge_t_type = $this->transfers->get_transfer_type_id('Transaction Charges');

        $admin_name = $this->members->get_some_members_info('name', $this->session->userdata('username'));
        $members_name = $this->members->get_some_members_info('name', $username);

        $total_amount = $this->input->post('total_amount');
        $interest_amount = $this->input->post('interest_amount');
        $repayment_date = $this->input->post('loan_repayment');

        $data = array(
            'transfers' => array(
                'from_account_id' => 1,
                'to_account_id' => $savings_account_id['id'],
                'type_id' => $transfer_type['id'],
                'date' => date("Y-m-d h:i:sa"),
                'amount' => $total_amount,
                'status' => 'O',
                'process_date' => date("Y-m-d h:i:sa"),
                'description' => 'Loan granted to ' . $members_name['name'] . '. Done by: ' . $admin_name['name']
            ),
            'charge' => array(
                'from_account_id' => $savings_account_id['id'],
                'to_account_id' => 1,
                'type_id' => $charge_t_type['id'],
                'date' => date("Y-m-d h:i:sa"),
                'amount' => $interest_amount,
                'status' => 'O',
                'process_date' => date("Y-m-d h:i:sa"),
                'description' => 'Loan interest to ' . $members_name['name'] . '. Done by: ' . $admin_name['name']
            ),
            'loans' => array(
                'total_amount' => $total_amount,
                'type' => 'S'
            ),
            'members_loans' => array(
                'member_id' => $users_profile['id']
            ),
            'loan_payments' => array(
                'expiration_date' => $repayment_date,
                'amount' => $total_amount,
                'repaid_amount' => 0,
                'status' => 'S',
                'repayment_date' => NULL
            )
        );

        if ($this->transfers->grant_members_loan($data)) {
            $this->generate_receipt($members_name['name'], $admin_name['name'], $total_amount, $interest_amount, $repayment_date);
            $message = 'Dear ' . $members_name['name'] . ', Alpha Credit Limited has approved your loan of ' . $amount . ' '
                    . 'the loan is payable by ' . $repayment_date . '. '
                    . 'Thanks for doing business with ';
            $this->send_sms($this->input->post('mobile_number'), $message);
        } else {
            echo '<p class="error">Failed to grant the loan.</p>';
        }
    }

    public function grant_loan_fields($username) {
        if (isset($username)) {
            echo '<span class="close">x</span>';
            echo '<form action="' . base_url() . 'loan/grant_loan/' . $username . '" method="post" accept-charset="utf-8">';
            echo '<fieldset>';
            echo '<legend>Grant Loan:</legend>';
            echo '<div class="input-label">';
            echo '<label for="loan_amount">This loan is for:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<p>' . $username . '</p>';
            echo '</div>';
            echo '<div class="input-label">';
            echo '<label for="loan_amount">Loan Amount:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<input class="S" type="text" id="loan_amount" name="loan_amount" onchange="showLoanInfo(\'' . $username . '\')">';
            echo '</div>';
            echo '<div class="input-label">';
            echo '<label for="loan_repayment">Loan Repayment</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<input class="S datepicker" type="text" id="loan_repayment" name="loan_repayment" onchange="showLoanInfo(\'' . $username . '\')">';
            echo '</div>';
            echo '<div id="transaction-info">';
            echo '<p>Transaction info will be generated here.';
            echo '</div>';
            echo '<input type="submit" id="submit" disabled="disabled" onclick="loan_form_submit(event,\'' . $username . '\')" value="Grant Loan" />';
            echo '</fieldset>';
            echo '</form>';
        } else {
            echo 'Members not found!';
        }
    }

    public function get_loan_info($username, $payment_date, $amount) {
        $date1 = date_create(date("Y-m-d"));
        $date2 = date_create($payment_date);
        $diff = date_diff($date1, $date2);

        if ($diff->format("%a") <= 30) {
            $interest = '<p>Loan interest for 1 month: 15%</p>';
            $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Loan Repayment');
            $interest_amount = (($fees['amount'] / 100) * $amount);
            $total_amount = $amount + $interest_amount;
        } else {
            $interest = '<p>Loan interest for more than a month: 20%</p>';
            $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Loan Repayment 2');
            $interest_amount = (($fees['amount'] / 100) * $amount);
            $total_amount = $amount + $interest_amount;
        }

        $user_id = $this->users->get_id($username);
        $allowed = $this->check_loan_balance($username);

        $data = array(
            'user_id' => $user_id['id'],
            'allowed' => $allowed,
            'interest' => $interest,
            'f_interest_amount' => number_format($interest_amount, 2),
            'interest_amount' => $interest_amount,
            'amount' => number_format($amount, 2),
            'fees' => number_format($fees['amount'], 2),
            'total_amount' => $total_amount,
            'f_total_amount' => number_format($total_amount, 2)
        );

        echo json_encode($data);
    }

    private function generate_receipt($members_name, $admin_name, $total_amount, $interest_amount, $repayment_date) {
        echo '<p>Successfully granted loan to ' . $members_name . '.</p>';
        echo '<p>Receipt generated below.</p>';
        echo '<div id="receipt">';
        echo '<h1>Loan Receipt</h1>';
        echo '<img src="' . base_url() . 'images/landing_page/logo.png">';
        echo '<h2>Alpachredit</h2>';
        echo '<div style="clear:both"></div>';
        echo '<hr style="background:#000000; border:0; height:4px" />';
        echo '<p>Date: ' . date('F j, Y') . '</p>';
        echo '<p>Time: ' . date('h:i A') . '</p>';
        echo '<p>Loan granted to: ' . $members_name . '</p>';
        echo '<p>Interest amount: ' . $interest_amount . '</p>';
        echo '<p>Loan amount: ' . $total_amount . '</p>';
        echo '<p>Repayment date: ' . $repayment_date . '</p>';
        echo '<p>Transaction done by: ' . $admin_name . '</p>';
        echo '</div>';
        echo '<button value="Print" onclick="print_receipt()">Print</button>';
    }

    /*     * ****************************************************************************
     * **************************** Paying a loan functions ************************
     * **************************************************************************** */

    public function pay_loan_fields($username) {
        if (isset($username)) {
            echo '<span class="close">x</span>';
            echo '<form action="' . base_url() . 'loan/pay_loan/' . $username . '" method="post" accept-charset="utf-8">';
            echo '<fieldset>';
            echo '<legend>Pay Loan:</legend>';
            echo '<div class="input-label">';
            echo '<label for="username">Pay loan to:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<p>' . $username . '</p>';
            echo '</div>';
            echo '<div class="input-label">';
            echo '<label for="pay_amount">Pay Amount:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<input class="S" type="text" id="pay_amount" name="pay_amount" onchange="showPayLoanInfo(\'' . $username . '\')">';
            echo '</div>';
            echo '<div id="transaction-info">';
            echo '<p>Transaction info will be generated here.';
            echo '</div>';
            echo '<input type="submit" id="submit" onclick="pay_loan_submit(event,\'' . $username . '\')" value="Pay Loan" />';
            echo '</fieldset>';
            echo '</form>';
        } else {
            echo 'Member not found!';
        }
    }

    public function pay_loan_info($username, $amount) {
        $total_loan = $this->transfers->get_member_loan($username);
        $loan_balance = $total_loan['amount'] - $amount;
        $data = array(
            'total_loan' => number_format($total_loan['amount'], 2),
            'amount' => $amount,
            'f_amount' => number_format($amount, 2),
            'loan_balance' => ($loan_balance),
            'f_loan_balance' => number_format($loan_balance, 2)
        );
        echo json_encode($data);
    }

    public function pay_loan($username) {

        $amount = $this->input->post('amount');
        $total_loan = $this->transfers->get_member_loan($username);
        $savings_account_id = $this->accounts->get_savings_account_id($username);
        $transfer_type = $this->transfers->get_transfer_type_id('Loan Repayments');

        $admin_name = $this->members->get_some_members_info('name', $this->session->userdata('username'));
        $members_name = $this->members->get_some_members_info('name', $username);

        $loan_balance = $total_loan['amount'] - $amount;
        $loan = $this->transfers->get_member_loan($username);

        $receipt_data = array(
            'total_loan' => number_format($total_loan['amount'], 2),
            'amount' => number_format($amount, 2),
            'loan_balance' => number_format($loan_balance, 2)
        );

        $data = array(
            'transfers' => array(
                'from_account_id' => $savings_account_id['id'],
                'to_account_id' => 1,
                'type_id' => $transfer_type['id'],
                'date' => date("Y-m-d h:i:sa"),
                'amount' => $amount,
                'status' => 'O',
                'process_date' => date("Y-m-d h:i:sa"),
                'description' => 'Loan Repayment to ' . $members_name['name'] . '. Done by: ' . $admin_name['name']
            ),
            'loan_repayment' => array(
                'loan_id' => $loan['id'],
                'repaid_amount' => $amount,
                'repayment_date' => date("Y-m-d h:i:sa")
            )
        );
        if ($this->transfers->new_loan_repayment($data)) {
            $this->generate_payment_receipt($members_name['name'], $admin_name['name'], $receipt_data);
            $message = 'Dear ' . $members_name['name'] . ' Alpha Credit Limited has approved your repaid ' . $amount . ' '
                    . 'the loan balance is '.$this->input->post('loan_balance').''
                    . 'Thanks for doing business with us';
            $this->send_sms($this->input->post('mobile_number'), $message);
        }
    }

    private function generate_payment_receipt($members_name, $admin_name, $data) {
        echo '<p>Successfully paid loan to ' . $members_name . '.</p>';
        echo '<p>Receipt generated below.</p>';
        echo '<div id="receipt">';
        echo '<h1>Loan Payment Receipt</h1>';
        echo '<img src="' . base_url() . 'images/landing_page/logo.png">';
        echo '<h2>Alpachredit</h2>';
        echo '<div style="clear:both"></div>';
        echo '<hr style="background:#000000; border:0; height:4px" />';
        echo '<p>Date: ' . date('F j, Y') . '</p>';
        echo '<p>Time: ' . date('h:i A') . '</p>';
        echo '<p>Loan payment to: ' . $members_name . '</p>';
        echo '<p>Total Loan: ' . $data['total_loan'] . '</p>';
        echo '<p>Payment amount: ' . $data['amount'] . '</p>';
        echo '<p>Loan Balance: ' . $data['loan_balance'] . '</p>';
        echo '<p>Transaction done by: ' . $admin_name . '</p>';
        echo '</div>';
        echo '<button value="Print" onclick="print_receipt()">Print</button>';
    }

    public function check_loan_balance($username) {
        $unpaid_loans = $this->transfers->get_member_loan($username);
        if ($unpaid_loans['amount'] == 0 || $unpaid_loans['amount'] == NULL) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function send_sms($number, $message) {
        $this->show_sms_fields($number);

        // Automatically submit the form
        echo '<script type="text/javascript">';
        echo 'document.getElementById("sms-form").style.display = "none";';
        echo 'document.getElementById("message").value = "' . $message . '";';
        echo 'setTimeout(function() {';
        echo 'document.getElementById("sms-form").submit();';
        echo '},5000)';
        echo '</script>';
    }

    private function show_sms_fields($mobile_num) {

        $mobile = str_replace(',', '%2C', $mobile_num);

        echo '<form action="http://www.wivupsms.com/sendsms.php" method="get" id="sms-form" target="_blank" accept-charset="utf-8">';
        echo '<fieldset>';
        echo '<legend>Send SMS:</legend>';
        echo '<div class="form-input">';
        echo '<input type="hidden" name="user" value="Alphacredit">';
        echo '</div>';
        echo '<div class="form-input">';
        echo '<input type="hidden" name="password" value="Xtr@Dcoded4u">';
        echo '</div>';
        echo '<div class="input-label">';
        echo '<label for="mobile">Mobile:</label>';
        echo '</div>';
        echo '<div class="form-input">';
        echo '<input class="D" type="text" name="mobile" value="' . $mobile . '">';
        echo '</div>';
        echo '<div class="form-input">';
        echo '<input type="hidden" name="senderid" value="ALPHACREDIT">';
        echo '</div>';
        echo '<div class="input-label">';
        echo '<label for="message">Message:</label>';
        echo '</div>';
        echo '<div class="form-input">';
        echo '<textarea name="message" id="message" style="margin: 0px; width: 218px; height: 100px;"></textarea>';
        echo '</div>';
        //echo '<input type="submit" id="submit" onclick="send_sms_submit(event)" value="Send" />';
        echo '<input type="submit" value="Send"/>';
        echo '</fieldset>';
        echo '</form>';
    }

}
