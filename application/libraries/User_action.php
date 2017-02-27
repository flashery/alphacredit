<?php

/**
 * Description of User_action
 *
 * @author flashery
 */
class User_action {

    protected $CI = null;
    protected $custom_fields = null;
    protected $username = '';

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function show_actions($username, $subclass) {
        if ($subclass === 'M') {
            if ($this->CI->session->userdata('username') === $username) {
                echo '<div class = "action">';
                echo '<a id = "deposit-lnk" class = "link-action" href="#deposit-lnk" onclick = "deposit_click(\'' . $username . '\')">Deposit</a>';
                echo '<a id = "pay-loan-lnk" class = "link-action" href="#pay-loan-lnk" onclick = "pay_loan_click(\'' . $username . '\')">Pay Loan</a>';
                echo '<a id = "withdraw-lnk" class = "link-action" href="#withdraw-lnk" onclick = "withdraw_click(\'' . $username . '\')">Withdraw</a>';
                echo '</div>';
            } else {
                echo '<div class = "action">';
                echo '<a id = "deposit-lnk" class = "link-action" href="#deposit-lnk" onclick = "deposit_click(\'' . $username . '\')">Deposit</a>';
                echo '<a id = "withdraw-lnk" class = "link-action" href="#withdraw-lnk" onclick = "withdraw_click(\'' . $username . '\')">Withdraw</a>';
                echo '<a id = "pay-loan-lnk" class = "link-action" href="#pay-loan-lnk" onclick = "pay_loan_click(\'' . $username . '\')">Pay Loan</a>';
                echo '<a id = "grant-loan-lnk" class = "link-action" href="#grant-loan-lnk" onclick = "grant_loan_click(\'' . $username . '\')">Grant Loan</a>';
                echo '<a id = "send-sms-lnk" class = "link-action" href="#send-sms-lnk" onclick = "send_sms_click(\'' . $username . '\')">Send Message</a>';
                echo '</div>';
            }
        }
    }

    public function show_admin_actions($username, $members_profile = array()) {
        if ($members_profile['subclass'] === 'A') {
            $this->create_admin_action_lnk($members_profile, $username);
        } else {
            if ($this->CI->session->userdata('username') !== $username && $this->CI->session->userdata('role') == permission::ADMINISTARTOR) {
                $this->create_action_lnk($members_profile, $username);
            }
        }
    }

    private function create_admin_action_lnk($members_profile, $username) {
        echo '<div class = "action">';
        if ($members_profile['group_id'] != permission::DISABLED_MEMBER) {
            echo '<a id = "disable-lnk" class = "link-action" href = "' . base_url() . 'admin/update_member_status/' . $members_profile['id'] . '/' . $username . '/' . Permission::DISABLED_ADMIN . '">Disable</a>';
        }
        if ($members_profile['group_id'] != Permission::REMOVED_MEMBER) {
            echo '<a id = "remove-lnk" class = "link-action" href = "' . base_url() . 'admin/update_member_status/' . $members_profile['id'] . '/' . $username . '/' . Permission::REMOVED_ADMIN . '")">Remove</a>';
        }
        echo '</div>';
    }

    private function create_action_lnk($members_profile, $username) {
        echo '<div class = "action">';
        if ($members_profile['group_id'] != permission::MEMBER) {
            echo '<a class = "link-action" href = "' . base_url() . 'admin/update_member_status/' . $members_profile['id'] . '/' . $username . '/' . Permission::MEMBER . '">Activate Member</a>';
        }
        if ($members_profile['group_id'] != permission::DISABLED_MEMBER) {
            echo '<a class = "link-action" href = "' . base_url() . 'admin/update_member_status/' . $members_profile['id'] . '/' . $username . '/' . Permission::DISABLED_MEMBER . '">Disable Member</a>';
        }
        if ($members_profile['group_id'] != Permission::REMOVED_MEMBER) {
            echo '<a class = "link-action" href = "' . base_url() . 'admin/update_member_status/' . $members_profile['id'] . '/' . $username . '/' . Permission::REMOVED_MEMBER . '")">Remove Member</a>';
        }
        echo '</div>';
    }

}
