<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const PAYMENT_METHOD = 'payment_method_id';
    const AMOUNT = 'amount';

    //validating inputs
    foreach ([USER_ID,PAYMENT_METHOD,AMOUNT] as $item) {
        validateInputs($item);
    }

    //getting data
    $user_id = $_POST[USER_ID];
    $payment_method = $_POST[PAYMENT_METHOD];
    $amount = $_POST[AMOUNT];
    $uuid = unique_code();

    $type = 0;
    $info = '';

    $sql = "select * from payout_settings where id = '$payment_method'";
    $r = $conn->query($sql);
    if($r){
        $row = mysqli_fetch_assoc($r);
        $type = $row['type'];
        if($type == 6){
            $info = $row['account_name'];
            $account_name = $row['account_name'];
            $account_number = $row['account_number'];
            $branch_name = $row['bank_branch_name'];
            $branch_city = $row['bank_branch_city'];
            $branch_address = $row['bank_branch_address'];
            $country = $row['country'];
            $swift_code = $row['swift_code'];
            $bank_name = $row['bank_name'];

        }
        else{
            $info = $row['email'];
        }
    }else{
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }

    //getting fee
    $sql = "select * from fees_limits where payment_method_id = '$type' and transaction_type_id = 2";
    $r = $conn->query($sql);
    $charge = 0;
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $charge = number_format($row['charge_percentage'],2);
    }

    $fees = ($charge * $amount) / 100;
    $amount_with_fee = $amount + $fees;

    $sql = "insert into withdrawals(user_id,currency_id,payment_method_id,uuid,charge_percentage,subtotal,amount,payment_method_info,status,created_at,updated_at) 
            values('$user_id',1,'$type','$uuid','$fees','$amount','$amount_with_fee','$info','Pending',now(),now())";
    $r = $conn->query($sql);
    $last_id = $conn->insert_id;
    if(!$r){
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }

    if($type == 6) {
        $sql = "insert into withdrawal_details(withdrawal_id,type,account_name,account_number,bank_branch_name,bank_branch_city,bank_branch_address,country,swift_code,bank_name,created_at,updated_at) 
            values('$last_id','$type','$account_name','$account_number','$branch_name','$branch_city','$branch_address','$country','$swift_code','$bank_name',now(),now())";
        $r = $conn->query($sql);
        if(!$r){
            $error['message'] = "Something Went Wrong";
            die(json_encode($error));
        }
    }else{
        $sql = "insert into withdrawal_details(withdrawal_id,type,email,created_at,updated_at) 
            values('$last_id','$type','$info',now(),now())";
        $r = $conn->query($sql);
        if(!$r){
            $error['message'] = "Something Went Wrong";
            die(json_encode($error));
        }
    }

    $sql = "insert into transactions(user_id,currency_id,payment_method_id,uuid,transaction_reference_id,transaction_type_id,user_type,subtotal,percentage,charge_percentage,total,status,created_at,updated_at) 
            values('$user_id',1,'$type','$uuid','$last_id',2,'registered','$amount','$charge','$fees','-$amount_with_fee','Pending',now(),now())";
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

    //sending response
    $success = [];
    $success['status'] = 'success';
    $success['response'] = 'Your Withdrawn Amount is been processed.';
    echo json_encode($success);