<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;

    const USER_ID = 'user_id';
    const RECIPIENT = 'recipient_email';
    const AMOUNT = 'amount';
    const NOTE = 'note';

    //validating inputs
    foreach ([USER_ID,RECIPIENT,AMOUNT,NOTE] as $item) {
        validateInputs($item);
    }

    //checking recipient
    $email = $_POST[RECIPIENT];
    $user_id = $_POST[USER_ID];
    $amount = $_POST[AMOUNT];
    $note = $_POST[NOTE];

    $sql = "select * from users where email = '$email'";
    $r = $conn->query($sql);

    if(mysqli_num_rows($r)<1){
        $error['message'] = "Recipient not Found!";
        die(json_encode($error));
    }

    $row = mysqli_fetch_assoc($r);
    $receiver_id = $row['id'];
    if($row['status'] == 'Suspended'){
        $error['message'] = "The recipient is suspended!";
        die(json_encode($error));
    }
    if($row['status'] == 'Inactive'){
        $error['message'] = "The recipient is inactive!";
        die(json_encode($error));
    }

    //check yourself
    if($row['id'] == $user_id){
        $error['message'] = "You Cannot Send Money To Yourself!";
        die(json_encode($error));
    }

    //checking balance
    $sql = "select * from wallets where user_id = '$user_id' and currency_id = 1";
    $r = $conn->query($sql);
    $row = mysqli_fetch_assoc($r);
    if($row['balance'] < $amount){
        $error['message'] = "Insufficient Balance";
        die(json_encode($error));
    }

    //getting fee
    $sql = "select * from fees_limits where transaction_type_id = 3";
    $r = $conn->query($sql);
    $row = mysqli_fetch_assoc($r);

    $charge_percentage = $row['charge_percentage'];
    $fees = ($row['charge_percentage'] * $amount) / 100;
    $amount_with_fee = $amount + $fees;

    $uuid = unique_code();



    $sql = "insert into transfers(sender_id,receiver_id,currency_id,uuid,fee,amount,note,email,status,created_at,updated_at) values('$user_id','$receiver_id',1,'$uuid','$fees','$amount','$note','$email','Success',now(),now())";
    $r = $conn->query($sql);
    $last_id = $conn->insert_id;
    if(!$r){
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }

    //for transferred
    $transferred_amount = '-' . $amount_with_fee;

    $sql = "insert into transactions(user_id,end_user_id,currency_id,uuid,transaction_reference_id,transaction_type_id,
                user_type,email,subtotal,percentage,charge_percentage,total,note,status,created_at,updated_at) values(
                '$user_id','$receiver_id',1,'$uuid','$last_id',3,'registered','$email','$amount','$charge_percentage','$fees','$transferred_amount','$note','Success',now(),now()
                )";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }


    //for received
    $sql = "insert into transactions(user_id,end_user_id,currency_id,uuid,transaction_reference_id,transaction_type_id,
                    user_type,email,subtotal,total,note,status,created_at,updated_at) values(
                    '$receiver_id','$user_id',1,'$uuid','$last_id',4,'registered','$email','$amount','$amount','$note','Success',now(),now()
                    )";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }

    $sql = "update wallets set balance = balance - '$amount_with_fee' where user_id = '$user_id' and currency_id = 1";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }

    $sql = "update wallets set balance = balance + '$amount' where user_id = '$receiver_id' and currency_id = 1";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }


    //sending response
    $success = [];
    $success['status'] = 'success';
    $success['response'] = 'Money Transferred Successfully';
    echo json_encode($success);