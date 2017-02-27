<?php

/**
 * Description of Modal_creator
 *
 * @author flashery
 */
class Modal_creator {

    public function create_js_vars() {
        echo '<script>';
        echo 'var baseurl = "' . base_url() . '";';
        echo 'var email = $("input[name=email]").val();';
        echo 'var username = $("input[name=username]").val();';
        // Get the modal
        echo 'var modal = document.getElementById("myModal");';
        // Get the button that opens the modal
        echo 'var upload_new_photo = document.getElementById("upload-btn");';
        echo 'var grant_loan = document.getElementById("grant-loan-lnk");';
        echo 'var add_contact = document.getElementById("add-contact-lnk");';
        echo 'var view_transactions = document.getElementById("view-transactions-lnk");';
        echo 'var send_sms = document.getElementById("send-sms-lnk");';
        echo 'var loan_select = document.getElementById("loan_duration");';
        // When the user clicks on the button, open the modal 
        echo 'upload_new_photo.onclick = function () 
                {
                    alert("WEW");
                }';
        echo '</script>';
    }

    public function create_image_uploader($action_url = 'upload/upload_image') {
        $html = form_open_multipart($action_url);
        $html .= '<fieldset>';
        /*
          echo '<legend>Upload Image:</legend>';
          echo '<input type = "file" name = "userfile" size = "20" />';
          echo '<br /><br />';
          echo '<input type = "hidden" name = "email" value = "' + email + '" />';
          echo '<input type = "hidden" name = "username" value = "' + username + '" />';
          echo '<input type = "submit" value = "Upload Image" />'; */
        $html .= '</fieldset>';
        $html .= '</form>';
        
        return $html;
    }

    public function create_grant_loan($action_url = '') {
        echo form_open($action_url);
        echo validation_errors('<p class = "error">');
        echo '<fieldset>';
        echo '<legend>Grant Loan:</legend>';
        echo '<div class = "input-label">';
        echo '<label for = "loan_amount">Loan Amount:</label>';
        echo '</div>';
        echo '<div class = "form-input">';
        echo '<input type = "text" name = "loan_amount">';
        echo '</div>';
        echo '<div class = "input-label">';
        echo '<label for = "loan_duration">Loan Duration</label>';
        echo '</div>';
        echo '<div class = "form-input">';
        echo '<select class = "D" name = "loan_duration" >';
        echo '<option selected = "selected" value = "1 month">1 month</opion>';
        echo '<option value = "2 months">2 months</opion>';
        echo '<option value = "3 months">3 months</opion>';
        echo '</select>';
        echo '</div>';
        echo form_submit('submit', 'Grant Loan');
        echo '</form>';
    }

}
