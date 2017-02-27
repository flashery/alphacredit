<section class="bg_dark content">
    <div id="error"> 
        <image src="<?php echo base_url() . 'images/wrong-login.jpg'; ?>"/>
        <?php echo '<p>' . $message . '! Please ' . anchor('login/', 'try again') . '</p>'; ?>
    </div>
</section>