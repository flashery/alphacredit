<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paybill
 *
 * @author flashery
 */
class Mpesa {

    public function pushin() {
        echo '<h4>Money Comes In:</h4>' . '</br>';
        echo 'id                = ' . $_GET['id'] . '</br>';
        echo 'orig              = ' . $_GET['orig'] . '</br>';
        echo 'dest              = ' . $_GET['dest'] . '</br>';
        echo 'tstamp            = ' . $_GET['tstamp'] . '</br>';
        echo 'text              = ' . $_GET['text'] . '</br>';
        echo 'customer_id       = ' . $_GET['customer_id'] . '</br>';
        echo 'user              = ' . $_GET['user'] . '</br>';
        echo 'pass              = ' . $_GET['pass'] . '</br>';
        echo 'routemethod_id    = ' . $_GET['routemethod_id'] . '</br>';
        echo 'routemethod_name  = ' . $_GET['routemethod_name'] . '</br>';
        echo 'mpesa_code        = ' . $_GET['mpesa_code'] . '</br>';
        echo 'mpesa_acc         = ' . $_GET['mpesa_acc'] . '</br>';
        echo 'mpesa_msisdn      = ' . $_GET['mpesa_msisdn'] . '</br>';
        echo 'mpesa_trx_date    = ' . $_GET['mpesa_trx_date'] . '</br>';
        echo 'mpesa_trx_time    = ' . $_GET['mpesa_trx_time'] . '</br>';
        echo 'mpesa_amt         = ' . $_GET['mpesa_amt'] . '</br>';
        echo 'mpesa_sender      = ' . $_GET['mpesa_sender'] . '</br>';
    }

    public function pushout() {
        echo '<h4>Money Comes Out:</h4>' . '</br>';
        echo 'id                = ' . $_GET['id'] . '</br>';
        echo 'orig              = ' . $_GET['orig'] . '</br>';
        echo 'dest              = ' . $_GET['dest'] . '</br>';
        echo 'tstamp            = ' . $_GET['tstamp'] . '</br>';
        echo 'text              = ' . $_GET['text'] . '</br>';
        echo 'customer_id       = ' . $_GET['customer_id'] . '</br>';
        echo 'user              = ' . $_GET['user'] . '</br>';
        echo 'pass              = ' . $_GET['pass'] . '</br>';
        echo 'routemethod_id    = ' . $_GET['routemethod_id'] . '</br>';
        echo 'routemethod_name  = ' . $_GET['routemethod_name'] . '</br>';
        echo 'mpesa_code        = ' . $_GET['mpesa_code'] . '</br>';
        echo 'mpesa_acc         = ' . $_GET['mpesa_acc'] . '</br>';
        echo 'mpesa_msisdn      = ' . $_GET['mpesa_msisdn'] . '</br>';
        echo 'mpesa_trx_date    = ' . $_GET['mpesa_trx_date'] . '</br>';
        echo 'mpesa_trx_time    = ' . $_GET['mpesa_trx_time'] . '</br>';
        echo 'mpesa_amt         = ' . $_GET['mpesa_amt'] . '</br>';
        echo 'mpesa_sender      = ' . $_GET['mpesa_sender'] . '</br>';
    }

    public function pushneutral() {
        echo '<h4>No Money Comes In or Out:</h4>' . '</br>';
        echo 'id                = ' . $_GET['id'] . '</br>';
        echo 'orig              = ' . $_GET['orig'] . '</br>';
        echo 'dest              = ' . $_GET['dest'] . '</br>';
        echo 'tstamp            = ' . $_GET['tstamp'] . '</br>';
        echo 'text              = ' . $_GET['text'] . '</br>';
        echo 'customer_id       = ' . $_GET['customer_id'] . '</br>';
        echo 'user              = ' . $_GET['user'] . '</br>';
        echo 'pass              = ' . $_GET['pass'] . '</br>';
        echo 'routemethod_id    = ' . $_GET['routemethod_id'] . '</br>';
        echo 'routemethod_name  = ' . $_GET['routemethod_name'] . '</br>';
        echo 'mpesa_code        = ' . $_GET['mpesa_code'] . '</br>';
        echo 'mpesa_acc         = ' . $_GET['mpesa_acc'] . '</br>';
        echo 'mpesa_msisdn      = ' . $_GET['mpesa_msisdn'] . '</br>';
        echo 'mpesa_trx_date    = ' . $_GET['mpesa_trx_date'] . '</br>';
        echo 'mpesa_trx_time    = ' . $_GET['mpesa_trx_time'] . '</br>';
        echo 'mpesa_amt         = ' . $_GET['mpesa_amt'] . '</br>';
        echo 'mpesa_sender      = ' . $_GET['mpesa_sender'] . '</br>';
    }

}
