<?php
$this->load->view('includes/admin/header');
$this->load->view('includes/admin/sidebar');
?>
<div class="container">
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
    <div style="clear: both;"></div>
</div>

<?php
$this->load->view('includes/admin/footer');
