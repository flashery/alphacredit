<?php $this->load->view('includes/index/header'); ?>
<div id="main">
    <?php
    $error = $this->session->flashdata('error');
    $success = $this->session->flashdata('success');
    if (!empty($error)) {
        echo '<div class="admin-errors">' . $error . '</div>';
    }
    if (!empty($success)) {
        echo '<div class="admin-success">' . $success . '</div>';
    }
    ?>
    
    <?php $this->load->view($main_content); ?>
</div>
<?php $this->load->view('includes/index/footer'); ?>