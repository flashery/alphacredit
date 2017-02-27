<?php

/**
 * Description of Deposit
 *
 * @author flashery
 */
class Deposit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('reports');
        $this->load->model('transfers');
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('accounts');
        $this->load->helper('date');
    }

    public function do_deposit($username) {
        $savings_account_id = $this->accounts->get_savings_account_id($username);
        $transfer_type = $this->transfers->get_transfer_type_id('Deposits');

        $admin_name = $this->members->get_some_members_info('name', $this->session->userdata('username'));
        $members_name = $this->members->get_some_members_info('name', $username);

        $amount = $this->input->post('amount');

        $data = array(
            'from_account_id' => 1,
            'to_account_id' => $savings_account_id['id'],
            'type_id' => $transfer_type['id'],
            'date' => date("Y-m-d h:i:sa"),
            'amount' => $amount,
            'status' => 'O',
            'process_date' => date("Y-m-d h:i:sa"),
            'description' => 'Deposited to ' . $members_name['name'] . '. Done by: ' . $admin_name['name']
        );

        if ($this->transfers->new_deposit($data)) {
            $this->generate_receipt($members_name, $admin_name, $amount);
            $message = 'Dear ' . $members_name['name'] . ' Alpha Credit Limited has approved your deposit ' . $amount . ' '
                    . 'Thanks for doing business with us';
            $this->send_sms($this->input->post('mobile_number'), $message);
        } else {
            echo '<p class="error">Failed to deposit.</p>';
        }
    }

    public function show_deposit_info($username, $amount) {
        $user_deposits = $this->transfers->get_deposits($username);
        $total_balance = $user_deposits['amount'] + $amount;
        $data = array(
            'amount' => number_format($amount, 2),
            'user_deposits' => number_format($user_deposits['amount'], 2),
            'total_balance' => number_format($total_balance, 2)
        );
        echo json_encode($data);
    }

    //put your code here
    public function deposit_fields($username) {
        if (isset($username)) {
            echo '<span class="close">x</span>';
            echo '<form action="' . base_url() . 'deposit/do_deposit/' . $username . '" method="post" accept-charset="utf-8">';
            echo '<fieldset>';
            echo '<legend>Deposit</legend>';
            echo '<div class="input-label">';
            echo '<label for="deposit_amount">Deposit to:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<p>' . $username . '</p>';
            echo '</div>';
            echo '<div class="input-label">';
            echo '<label for="deposit_amount">Deposit Amount:</label>';
            echo '</div>';
            echo '<div class="form-input">';
            echo '<input class="S" type="text" id="deposit_amount" name="deposit_amount" onchange="showDepositInfo(\'' . $username . '\')">';
            echo '</div>';
            echo '<div id="transaction-info">';
            echo '<p>Transaction info will be generated here.';
            echo '</div>';
            echo '<input type="submit" id="submit" onclick="deposit_form_submit(event,\'' . $username . '\')" value="Deposit" />';
            echo '</fieldset>';
            echo '</form>';
        } else {
            echo 'Username not set.';
        }
    }

    private function generate_receipt($members_name, $admin_name, $amount) {
        echo '<p>Successfully deposited to ' . $members_name['name'] . '.</p>';
        echo '<p>Receipt generated below.</p>';
        echo '<div id="receipt">';
        echo '<h1>Deposit Receipt</h1>';
        echo '<img src="' . base_url() . 'images/landing_page/logo.png">';
        echo '<h2>Alpachredit</h2>';
        echo '<div style="clear:both"></div>';
        echo '<hr style="background:#000000; border:0; height:4px" />';
        echo '<p>Date: ' . date('F j, Y') . '</p>';
        echo '<p>Time: ' . date('h:i A') . '</p>';
        echo '<p>Deposited to: ' . $members_name['name'] . '</p>';
        echo '<p>Deposit amount: ' . $amount . '</p>';
        echo '<p>Transaction done by: ' . $admin_name['name'] . '</p>';
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
