<?php

    //libraries
    include "../../include/db.php";
    include "../../include/functions.php";

    $user_id = $_GET['user_id'];
    $amount = $_GET['amount'];

    $sql = "select * from fees_limits where payment_method_id = 7 and transaction_type_id = 1";
    $r = $conn->query($sql);
    $row = mysqli_fetch_assoc($r);

    $charge_percentage = $row['charge_percentage'];
    $fees = ($row['charge_percentage'] * $amount) / 100;
    $amount_with_fee = $amount + $fees;

    $uuid = unique_code();

    //create deposit history
    $sql = "insert into deposits(user_id,currency_id,payment_method_id,uuid,charge_percentage,amount,status,created_at,updated_at) values('$user_id',1,7,'$uuid','$fees','$amount','Success',now(),now())";
    $r = $conn->query($sql);
    $last_id = $conn->insert_id;

    //update user wallet
    $sql = "update wallets set balance = balance + '$amount' where user_id = '$user_id' and currency_id = 1";
    $r = $conn->query($sql);

    //create transaction
    $sql = "insert into transactions(user_id,currency_id,payment_method_id,uuid,transaction_reference_id,transaction_type_id,user_type,subtotal,percentage,charge_percentage,total,status,created_at,updated_at)
            values('$user_id',1,7,'$uuid','$last_id',1,'registered','$amount','$charge_percentage','$fees','$amount_with_fee','Success',now(),now())";
    $r = $conn->query($sql);


    $success = [];
    $success['status'] = 'success';
    $success['response'] = 'Payment Success';
    echo json_encode($success);

