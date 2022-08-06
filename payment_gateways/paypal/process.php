<?php

    // For test payments we want to enable the sandbox mode. If you want to put live
    // payments through then this setting needs changing to `false`.

    $enableSandbox = true;

    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = stripslashes($value);
    }

    // for your site.
    $paypalConfig = [
        'email' => 'genuinebiz4@gmail.com',
        'return_url' => 'https://freedompayuniverse.com/apis/payment_gateways/paypal/payment-successful.php?user_id=' . $data['user_id'] . '&amount=' . $data['amount'],
        'cancel_url' => 'http://localhost:3000/payment_gateways/paypal/payment-cancelled.php',
        'notify_url' => 'https://freedompayuniverse.com/apis/payment_gateways/paypal/process.php'
    ];

    $paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

    // Include Functions
//    require 'functions.php';

    // Check if paypal request or response
    if (!isset($_GET["PayerID"])) {

        // Grab the post data so that we can set up the query string for PayPal.
        // Ideally we'd use a whitelist here to check nothing is being injected into
        // our post data.
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = stripslashes($value);
        }

        // Set the PayPal account.
        $data['business'] = $paypalConfig['email'];

        // Set the PayPal return addresses.
        $data['return'] = stripslashes($paypalConfig['return_url']);
        $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
        $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

        // Add any custom fields for the query string.
        //$data['custom'] = USERID;

        // Build the query string from the data.
        $queryString = http_build_query($data);

        // Redirect to paypal IPN
        header('location:' . $paypalUrl . '?' . $queryString);
        exit();
    }
