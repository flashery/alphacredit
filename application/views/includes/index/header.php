<!DOCTYPE  html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery_ui.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" media="screen" charset="utf-8">
    </head>
    <body>
        <div id="landing-page">
            <header class="landing_header">
                <div id="header_content">
                    <div class="logo">
                        <a href="<?php echo base_url(); ?>">
                            <img src="<?php echo base_url(); ?>images/landing_page/logo.png">
                        </a>
                    </div>
                    <div class="header_right">
                        <ul>
                            <?php
                            $logged_in = $this->session->userdata('logged_in');
                            $dest = ($this->session->userdata('subclass') == 'A') ? 'admin/users/profile' : 'index/members_profile';

                            if (!isset($logged_in) || $logged_in != TRUE) {
                                echo '<li><a href="' . base_url() . 'login/">Login</a> </li>';
                                echo '<li><a href="' . base_url() . 'signup">Sign up</a> </li>';
                            } else {
                                echo '<li>';
                                echo '<a href="' . base_url() . 'index/logout">';
                                echo 'Logout';
                                echo '</a> ';
                                echo '</li>';
                                if ($this->session->has_userdata('user_image')) {
                                    echo '<li>';
                                    echo '<a href="' . base_url() . $dest . '">';
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($this->session->userdata('user_image')) . '" />';
                                    echo '</a> ';
                                    echo '</li>';
                                } else {
                                    echo '<li>';
                                    echo '<a href="' . base_url() . $dest . '">';
                                    echo '<img src="' . base_url() . 'images/profile/no-photo.jpg" />';
                                    echo '</a> ';
                                    echo '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </header>