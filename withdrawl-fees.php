<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //checking user_id

    //getting paypal fee
    $sql = "select * from fees_limits where payment_method_id = 3 and transaction_type_id = 2";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $paypal = [
            'gateway' => 'PayPal',
            'charge_percentage' => number_format($row['charge_percentage'],2),
            'min_limit' => number_format($row['min_limit'],2),
            'max_limit' => number_format($row['max_limit'],2),
        ];
    }
    else{
        $error['message'] = "No Fee Found";
        die(json_encode($error));
    }

    //getting btc fee
    $sql = "select * from fees_limits where payment_method_id = 10 and transaction_type_id = 2";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $btc = [
            'gateway' => 'BTC',
            'charge_percentage' => number_format($row['charge_percentage'],2),
            'min_limit' => number_format($row['min_limit'],2),
            'max_limit' => number_format($row['max_limit'],2),
        ];
    }
    else{
        $error['message'] = "No Fee Found";
        die(json_encode($error));
    }

    //getting bank fee
    $sql = "select * from fees_limits where payment_method_id = 6 and transaction_type_id = 2";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $bank = [
            'gateway' => 'Bank',
            'charge_percentage' => number_format($row['charge_percentage'],2),
            'min_limit' => number_format($row['min_limit'],2),
            'max_limit' => number_format($row['max_limit'],2),
        ];
    }
    else{
        $error['message'] = "No Fee Found";
        die(json_encode($error));
    }

    //getting btc fee
    $sql = "select * from fees_limits where payment_method_id = 11 and transaction_type_id = 2";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $usdt = [
            'gateway' => 'USDT',
            'charge_percentage' => number_format($row['charge_percentage'],2),
            'min_limit' => number_format($row['min_limit'],2),
            'max_limit' => number_format($row['max_limit'],2),
        ];
    }
    else{
        $error['message'] = "No Fee Found";
        die(json_encode($error));
    }

    //sending response
    $success = [];
    $success['status'] = 'success';
    $success['response'] = [
        'paypal' => $paypal,
        'Bank' => $bank,
        'USDT' => $usdt,
        'BTC' => $btc,
    ];
    echo json_encode($success);