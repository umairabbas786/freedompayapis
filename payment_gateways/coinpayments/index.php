<?php

    require 'CoinPayments.php';

    //libraries
    include "../../include/db.php";
    include "../../include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const AMOUNT = 'amount';

    //validating inputs
    foreach ([USER_ID, AMOUNT] as $item) {
        validateGetInputs($item);
    }

    $user_id = $_GET[USER_ID];
    $amount = $_GET[AMOUNT];

    // Create an instance of the class
    $CP = new \MineSQL\CoinPayments();

    // Set the merchant ID and secret key (can be found in account settings on CoinPayments.net)
    $CP->setMerchantId('4c1c95336d8ec3303c398187ed036de7');
    $CP->setSecretKey('0437B8Dcd402B21D288f10B949894616beFb01Cfa614c0a94b7ac7119f3f4Ac5');


    // You are required to set the currency, amount and item name for coinpayments. cmd, reset, and merchant are automatically created within the class
    // there are many optional settings that you should probably set as well: https://www.coinpayments.net/merchant-tools-buttons

    //REQUIRED
    $CP->setFormElement('currency', 'USD');                    //Currency in which Invoice bill generated
    $CP->setFormElement('allow_currencies', 'BTC,LTC,LTCT,USDT.TRC20');   //here you can give list of currency allowable for payment
    $CP->setFormElement('amountf', $amount);                      //Amount for per Item
    $CP->setFormElement('item_name', 'T-shirt');               //Invoice item name
    $CP->setFormElement('allow_quantity',1);                   //Minimum number of quantity
    $CP->setFormElement('want_shipping',0);                    //Shipping not require


    //$CP->setFormElement('cancel_url','localhost/Restaurant_System/');
    //OPTIONAL
    $CP->setFormElement('custom', 'customValue235');
    $CP->setFormElement('ipn_url', 'http://minesql.me/ipn/cp');
    $CP->setFormElement('success_url', "https://freedompayuniverse.com/apis/payment_gateways/coinpayments/payment-successful.php?user_id=$user_id&amount=$amount");
    $CP->setFormElement('cancel_url', 'https://freedompayuniverse.com/apis/payment_gateways/coinpayments/payment-cancelled.php');
    // After you have finished configuring all your form elements,
    //you can call the CoinPayments::createForm method to invoke
    // the creation of a usable html form.
    echo $CP->createForm();
