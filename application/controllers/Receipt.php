<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Receipt
 *
 * @author flashery
 */
class Receipt extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transfers');
        $this->load->model('users');
        $this->load->model('members');
        $this->load->model('accounts');
        $this->load->model('images');
        $this->load->model('reports');
    }

    public function regenerate_receipt($id) {
        $transfers = $this->transfers->get_tranfers_row($id);
        $transfers_type = $this->transfers->get_transfer_type($transfers['type_id']);

        if ($transfers['type_id'] == 21 || $transfers['type_id'] == 32 || $transfers['type_id'] == 33) {
            $members_info = $this->accounts->get_account_by_id($transfers['from_account_id']);
        } else {
            $members_info = $this->accounts->get_account_by_id($transfers['to_account_id']);
        }

        $total_amount = $transfers['amount'];
        $admin_name = explode('.', $transfers['description']);
        $date_time = explode(' ', $transfers['date']);

        echo '<div id="receipt">';
        echo '<h1>' . $transfers_type['name'] . ' Receipt</h1>';
        echo '<img src="' . base_url() . 'images/landing_page/logo.png">';
        echo '<h2>Alpha Credit LTD</h2>';
        echo '<div style="clear:both"></div>';
        echo '<hr style="background:#000000; border:0; height:4px" />';


        echo '<p>' . $transfers_type['name'] . ' for: ' . $members_info['Account Name'] . '</p>';
        echo '<p>' . $transfers_type['name'] . ' amount: ' . number_format($total_amount, 2) . '</p>';

        if ($transfers['type_id'] == 22) {
            $loan = $this->compute_loan_interest($id);
            echo $loan['interest'];
            echo '<p>Interest Amount: ' . $loan['interest_amount'] . '</p>';
        }


        if ($transfers['type_id'] == 32) {
            $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Trasaction fee');
            echo '<p>Withdrawal Fee: ' . number_format($fees['amount'], 2) . '</p>';
        }

        echo '<p>Date: ' . $date_time[0] . '</p>';
        echo '<p>Time: ' . $date_time[1] . '</p>';

        echo '<p>Customers Signature:</p>';

        $signature_image = $this->images->get_signature_image($members_info['id']);

        if (!empty($signature_image['image'])) {
            echo '<img class="img-signature" src="data:image/jpeg;base64,' . base64_encode($signature_image['image']) . '" />';
        } else {
            echo '<img class="img-signature" src="' . base_url() . 'images/profile/no-signature.jpg" />';
        }

        echo '<p>Description: ' . $transfers['description'] . '</p>';

        echo '</div>';
        echo '<button value="Print" onclick="print_receipt()">Print</button>';
    }

    private function compute_loan_interest($tranfer_id) {
        $loan = $this->reports->get_single_loan($tranfer_id);

        $date1 = date_create($loan['date']);
        $date2 = date_create($loan['expiration_date']);
        $diff = date_diff($date1, $date2);

        $amount = $loan['total_amount'];

        if ($diff->format("%a") <= 30) {
            $interest = '<p>Loan interest for 1 month: 15%</p>';
            $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Loan Repayment');
            $orig_amount = ($amount / (($fees['amount'] + 100) / 100));
            $interest_amount = $amount - $orig_amount;
        } else {
            $interest = '<p>Loan interest for more than a month: 20%</p>';
            $fees = $this->transfers->get_some_transaction_fees_where('amount', 'Loan Repayment 2');
            $orig_amount = ($amount / (($fees['amount'] + 100) / 100));
            $interest_amount = $amount - $orig_amount;
        }

        $data = array(
            'interest' => $interest,
            'orig_amount' => $orig_amount,
            'fees' => number_format($fees['amount'], 0),
            'interest_amount' => $interest_amount
        );

        return $data;
    }

}
