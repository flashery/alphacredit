<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sms
 *
 * @author flashery
 */
class Sms extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounts');
    }

    public function get_male_group() {

        $accounts = $this->accounts->get_account_numbers();
        $numbers = '';
        foreach ($accounts as $account) {
            if ($account['Gender'] === 'Male') {
                $pieces = explode("/", $account['Mobile Number']);
                if ($numbers === '') {
                    $numbers .= $pieces[0];
                } else {
                    $numbers .= ',' . $pieces[0];
                }
            }
        }

        $data = array(
            'numbers' => $numbers
        );

        echo json_encode($data);
    }

    public function get_female_group() {
        $accounts = $this->accounts->get_account_numbers();
        $numbers = '';
        foreach ($accounts as $account) {
            if ($account['Gender'] === 'Female') {
                $pieces = explode("/", $account['Mobile Number']);
                if ($numbers === '') {
                    $numbers .= $pieces[0];
                } else {
                    $numbers .= ',' . $pieces[0];
                }
            }
        }

        $data = array(
            'numbers' => $numbers
        );

        echo json_encode($data);
    }

    public function due_auto_send() {
        $query = $this->accounts->get_due_loan_acct();
        $numbers = '';
        if (count($query) > 0) {
            echo '<p>Due loans found sms will be sent automatically in 5 seconds.</p>';
            foreach ($query as $key) {
                $pieces = explode("/", $key['Mobile Number']);

                if ($numbers === '') {
                    $numbers .= $pieces[0];
                } else {
                    $numbers .= ',' . $pieces[0];
                }
            }
            $this->show_sms_fields($numbers);
            $message = 'Dear customer. '
                    . 'This is a kind reminder to clear your outstanding loan. Thanks for doing business with us';
            
            // Automatically submit the form
            echo '<script type="text/javascript">';
            echo 'document.getElementById("sms-form").style.display = "none";';
            echo 'document.getElementById("message").value = "' . $message . '";';
            echo 'setTimeout(function() {';
            echo 'document.getElementById("sms-form").submit();';
            echo '},5000)';
            echo '</script>';
        }
    }

    public function expired_auto_send() {
        $query = $this->accounts->get_due_loan_acct();
        $numbers = '';
        if (count($query) > 0) {
            echo '<p>Due loans found sms will be sent automatically in 5 seconds.</p>';
            foreach ($query as $key) {
                $pieces = explode("/", $key['Mobile Number']);

                if ($numbers === '') {
                    $numbers .= $pieces[0];
                } else {
                    $numbers .= ',' . $pieces[0];
                }
            }
            $this->show_sms_fields($numbers);

            $message = 'Dear customer. 
                This is to notify you that your loan is past the date of payment. 
                Kindly clear the outstanding loan soonest possible to avoid additional penalties.';
            // Automatically submit the form
            echo '<script type="text/javascript">';
            echo 'document.getElementById("sms-form").style.display = "none";';
            echo 'document.getElementById("message").value = "' . $message . '";';
            echo 'setTimeout(function() {';
            echo 'document.getElementById("sms-form").submit();';
            echo '},5000)';
            echo '</script>';
        }
    }

    public function show_sms_fields($username, $mobile_num) {

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
