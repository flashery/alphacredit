<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Withdraw
 *
 * @author flashery
 */
class Withdraw extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transfers');
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('accounts');
    }

    public function do_withdraw($username) {

        $withdrawal_account_id = $this->accounts->get_withdrawal_account_id($username);
        $transfer_type = $this->transfers->get_transfer_type_id('Withdrawals');

        $admin_name = $this->members->get_some_members_info('name', $this->session->userdata('username'));
        $members_name = $this->members->get_some_members_info('name', $username);

        $total_amount = $this->input->post('total_amount');
        $charge_amount = $this->input->post('charge_amount');

        $data = array(
            'from_account_id' => $withdrawal_account_id['id'],
            'to_account_id' => 1,
            'type_id' => $transfer_type['id'],
            'date' => date("Y-m-d h:i:sa"),
            'amount' => $total_amount,
            'status' => 'O',
            'process_date' => date("Y-m-d h:i:sa"),
            'description' => 'Successful withdrawal to ' . $members_name['name'] . '. Done by: ' . $admin_name['name']
        );

        $charge_t_type = $this->transfers->get_transfer_type_id('Transaction Charges');
        $savings_account_id = $this->accounts->get_savings_account_id($username);

        $charge_data = array(
            'from_account_id' => $savings_account_id['id'],
            'to_account_id' => 1,
            'type_id' => $charge_t_type['id'],
            'date' => date("Y-m-d h:i:sa"),
            'amount' => $charge_amount,
            'status' => 'O',
            'process_date' => date("Y-m-d h:i:sa"),
            'description' => 'Withdrawal fees to ' . $members_name['name'] . '. Done by: ' . $admin_name['name']
        );

        if ($this->transfers->new_withdrawal($data) && $this->transfers->new_charge($charge_data)) {
            $this->generate_receipt($members_name['name'], $admin_name['name'], $total_amount);
            $message = 'Dear ' . $members_name['name'] . ' Alpha Credit Limited has approved your withdraw ' . $total_amount . ' '
                    . 'Thanks for doing business with us';
            $this->send_sms($this->input->post('mobile_number'), $message);
        } else {
            echo '<p class="error">Failed to withdraw.</p>';
        }
    }

    // This function will compute the total  withdrawal 
    // process including the charges of the withdrawal process 
    public function get_withdraw_info($username, $amount) {

        $user_savings = $this->transfers->get_savings($username);
        $user_withdrawals = $this->transfers->get_withdrawals($username);
        $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Trasaction fee');

        $total_balance = $user_savings['amount'] - $user_withdrawals['amount'];
        $total_amount = ($amount - $fees['amount']);

        $user_id = $this->users->get_id($username);
        $allowed = ($amount > $total_balance) ? FALSE : TRUE;

        $data = array(
            'user_id' => $user_id['id'],
            'allowed' => $allowed,
            'user_savings' => number_format($user_savings['amount'], 2),
            'users_withdrawals' => number_format($user_withdrawals['amount'], 2),
            'fees' => number_format($fees['amount'], 2),
            'total_balance' => number_format($total_balance, 2),
            'f_total_amount' => number_format($total_amount, 2),
            'total_amount' => $total_amount
        );

        echo json_encode($data);
    }

    public function validate_form() {
        $this->form_validation->set_rules('withdraw_amount', 'Withdraw Amount', 'trim|required');
    }

    // This function will return the withdrawal fields when the 
    // withdrawal link is click on the profile page
    public function show_withdraw_fields($username) {
        if (isset($username)) {
            echo '<span class="close">x</span>';
            echo '<form id="withdrawal-form" action="' . base_url() . 'withdraw/do_withdraw/' . $username . '" method="post" target="_blank" accept-charset="utf-8">';
            echo '<fieldset>';
            echo '<legend>Withdraw Cash:</legend>';
            echo '<div class="input-label">';
            echo '<label for="withdraw_amount">This withdrawal is for:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<p>' . $username . '</p>';
            echo '</div>';
            echo '<div class="input-label">';
            echo '<label for="withdraw_amount">Withdraw Amount:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<input class="S" type="text" id="withdraw_amount" name="withdraw_amount" onkeyup="showWithdrawInfo(\'' . $username . '\')">';
            echo '</div>';
            echo '<div id="transaction-info">';
            echo '<p>Transaction info will be generated here.';
            echo '</div>';
            echo '<input type="submit" id="submit" disabled="disabled" onclick="withdrawal_form_submit(event,\'' . $username . '\')" value="Withdraw" />';
            echo '</fieldset>';
            echo '</form>';
        } else {
            echo 'Username not set.';
        }
    }

    private function generate_receipt($members_name, $admin_name, $total_amount) {
        $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Trasaction fee');

        echo '<p>Successfull withdrawal to ' . $members_name . '.</p>';
        echo '<p>Receipt generated below.</p>';
        echo '<div id="receipt">';
        echo '<h1>Withdrawal Receipt</h1>';
        echo '<img src="' . base_url() . 'images/landing_page/logo.png">';
        echo '<h2>Alpha Credit LTD</h2>';
        echo '<div style="clear:both"></div>';
        echo '<hr style="background:#000000; border:0; height:4px" />';
        echo '<p>Date: ' . date('F j, Y') . '</p>';
        echo '<p>Time: ' . date('h:i A') . '</p>';
        echo '<p>Withdrawal for: ' . $members_name . '</p>';
        echo '<p>Withdrawal amount: ' . $total_amount . '</p>';
        echo '<p>Withdrawal Fee: ' . $fees['amount'] . '</p>';
        echo '<p>Transaction done by: ' . $admin_name . '</p>';
        echo '</div>';
        echo '<button value="Print" onclick="print_receipt()">Print</button>';
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

        echo '<form action="http://www.wivupsms.com/sendsms.php" method="get" id="sms-form" accept-charset="utf-8">';
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
