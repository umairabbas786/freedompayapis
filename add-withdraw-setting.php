<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const WITHDRAW_TYPE = 'withdraw_type';
    const ADDRESS = 'address';
    const ACCOUNT_NAME = 'account_holder_name';
    const ACCOUNT_NUMBER = 'account_number';
    const SWIFT_CODE = 'swift_code';
    const BANK_NAME = 'bank_name';
    const BRANCH_NAME = 'branch_name';
    const BRANCH_CITY = 'branch_city';
    const BRANCH_ADDRESS = 'branch_address';
    const COUNTRY = 'country_id';

    //validating inputs
    foreach ([USER_ID,WITHDRAW_TYPE] as $item) {
        validateInputs($item);
    }

    $user_id = $_POST[USER_ID];
    $type = $_POST[WITHDRAW_TYPE];

    if($type == 'Bank'){
        foreach ([ACCOUNT_NAME,ACCOUNT_NUMBER,SWIFT_CODE,BANK_NAME,BRANCH_NAME,BRANCH_CITY,BRANCH_ADDRESS,COUNTRY] as $item) {
            validateInputs($item);
        }
        $account_name = $_POST[ACCOUNT_NAME];
        $account_number = $_POST[ACCOUNT_NUMBER];
        $swift_code = $_POST[SWIFT_CODE];
        $bank_name = $_POST[BANK_NAME];
        $branch_name = $_POST[BRANCH_NAME];
        $branch_city = $_POST[BRANCH_CITY];
        $branch_address = $_POST[BRANCH_ADDRESS];
        $country = $_POST[COUNTRY];

        $sql = "insert into payout_settings(user_id,type,account_name,account_number,bank_branch_name,bank_branch_city,bank_branch_address,country,swift_code,bank_name,created_at,updated_at) 
            values('$user_id',6,'$account_name','$account_number','$branch_name','$branch_city','$branch_address','$country','$swift_code','$bank_name',now(),now())";
        $r = $conn->query($sql);
        if(!$r){
            $error['message'] = "Something Went Wrong";
            die(json_encode($error));
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Withdraw setting Added successfully!';
        echo json_encode($success);

    }else{
        foreach ([ADDRESS] as $item) {
            validateInputs($item);
        }

        $address = $_POST[ADDRESS];
        $type_id = 0;
        if($type == 'Paypal'){
            $type_id = 3;
        }
        if($type == 'BTC'){
            $type_id = 10;
        }
        if($type == 'USDT TRC20'){
            $type_id = 11;
        }

        $sql = "insert into payout_settings(user_id,type,email,created_at,updated_at) values('$user_id','$type_id','$address',now(),now())";
        $r = $conn->query($sql);
        if(!$r){
            $error['message'] = "Something Went Wrong";
            die(json_encode($error));
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Withdraw setting Added successfully!';
        echo json_encode($success);

    }