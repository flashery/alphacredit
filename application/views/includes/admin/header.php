<!DOCTYPE  html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery_ui.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" media="screen"  charset="utf-8">

        <!--<script src="<?php echo base_url(); ?>js/angular.min.js"></script>-->
        <script src="<?php echo base_url(); ?>js/jquery-3.1.0.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>-->
    </head>
    <body>
        <div id="admin">
            <div id="top-panel">
                <div id="hidden-menu">
                    <div id="nav_menu">
                        <ul class="nav-bar">
                            <li class="nb-items">
                                <a href="<?php echo base_url(); ?>admin/">
                                    <div class="nb-items-image">
                                        <image src="<?php echo base_url(); ?>images/dashboard.png" >
                                    </div>
                                    <div class="nb-items-name">Dashboard</div>
                                </a>
                            </li>
                            <li class="nb-items">
                                <a href="<?php echo base_url(); ?>admin/settings">
                                    <div class="nb-items-image">
                                        <image src="<?php echo base_url(); ?>images/settings.png" >
                                    </div>
                                    <div class="nb-items-name">Settings</div>
                                </a>
                                <div class="sub-nav">
                                    <ul class="nav-bar2">
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Configure Accounts</div>
                                            </a>
                                            <div class="arrow-right">
                                                <span></span>
                                            </div>
                                            <div class="sub-nav">
                                                <ul class="nav-bar3">
                                                    <li class="nb3-items">
                                                        <a href="<?php echo base_url(); ?>admin/settings/charges">
                                                            <div class="nb2-items-name">Charges</div>
                                                        </a>
                                                    </li>
                                                    <li class="nb3-items">
                                                        <a href="<?php echo base_url(); ?>admin/settings/rates">
                                                            <div class="nb2-items-name">Rates</div>
                                                        </a>
                                                    </li>
                                                    <li class="nb3-items">
                                                        <a href="<?php echo base_url(); ?>admin/settings/penalties">
                                                            <div class="nb2-items-name">Penalties</div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="<?php echo base_url(); ?>admin/settings/sms">
                                                <div class="nb2-items-name">SMS</div>
                                            </a>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Paybill</div>
                                            </a>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">USSD</div>
                                            </a>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Employee</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nb-items">
                                <a href="<?php echo base_url(); ?>admin/accounts">
                                    <div class="nb-items-image">
                                        <image src="<?php echo base_url(); ?>images/accounts.png" >
                                    </div>
                                    <div class="nb-items-name">Accounts</div>
                                </a>
                                <div class="sub-nav">
                                    <ul class="nav-bar2">
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Customer Details</div>
                                            </a>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Deposits</div>
                                            </a>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Withdrawal</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nb-items">
                                <a href="<?php echo base_url(); ?>admin/users">
                                    <div class="nb-items-image">
                                        <image src="<?php echo base_url(); ?>images/users.png" >
                                    </div>
                                    <div class="nb-items-name">Users</div>
                                </a>
                                <div class="sub-nav">
                                    <ul class="nav-bar2">
                                        <li class="nb2-items">
                                            <a href="<?php echo base_url(); ?>admin/users/profile">
                                                <div class="nb2-items-name">My Profile</div>
                                            </a>
                                        </li>
                                        <li class="nb2-items">
                                            <a href="#">
                                                <div class="nb2-items-name">Add New</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nb-items">
                                <a href="<?php echo base_url(); ?>admin/sms">
                                    <div class="nb-items-image">
                                        <image src="<?php echo base_url(); ?>images/sms.png" >
                                    </div>
                                    <div class="nb-items-name">SMS</div>
                                </a>
                            </li>
                            <li class="nb-items">
                                <a href="<?php echo base_url(); ?>admin/reports">
                                    <div class="nb-items-image">
                                        <image src="<?php echo base_url(); ?>images/reports.png" >
                                    </div>
                                    <div class="nb-items-name">Reports</div>
                                </a
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="admin-title">
                    <h1>Admin Area</h1>
                </div>
                <div id="top-panel-right">
                    <div id="notfications">
                        <ul>
                            <!--
                            <li  class="notfications-items notfications-notify">
                                <img src="http://localhost/cyclos/images/notification.png" />
                                <div class="sub-nav">
                                    <ul class="notify-popup">
                                        <header>Notifications</header>
                                        <li>Notfication 1</li>
                                        <li>Notfication 2</li>
                                    </ul>
                                </div>
                            </li>
                            -->
                            <li class="notfications-items notfications-profile">
                                <?php
                                if ($this->session->has_userdata('user_image')) {
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($this->session->userdata('user_image')) . '" />';
                                } else {
                                    echo '<img src="' . base_url() . 'images/profile/no-photo.jpg" />';
                                }
                                ?>
                                <div class="sub-nav">
                                    <ul class="notify-popup">
                                        <header><?php echo ucwords($this->session->userdata('username')); ?></header>
                                        <li><a href="<?php echo base_url(); ?>admin/users/profile">View Profile</a></li>
                                        <li><a href="<?php echo base_url(); ?>admin/logout">Log Out</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
