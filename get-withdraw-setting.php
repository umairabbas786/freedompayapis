<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';

    //validating inputs
    foreach ([USER_ID] as $item) {
        validateInputs($item);
    }

    //verifying user
    $user_id = $_POST[USER_ID];

    //checking user exists or not
    $sql = "select * from payout_settings where user_id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $settings = [];
        while($row = mysqli_fetch_assoc($r)){
            $type = '';
            $account = $row['email'];
            if($row['type'] == 3){
                $type = "Paypal";
            }
            if($row['type'] == 6){
                $type = "Bank";
                $account = $row['account_name'] . ' ('. $row['account_number'] . ') ' . $row['bank_name'];
            }
            if($row['type'] == 10){
                $type = "BTC";
            }
            if($row['type'] == 11){
                $type = "USDT TRC20";
            }

            array_push($settings,[
                'withdrawal_type' => $type,
                'account' => $account,
            ]);
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = $settings;
        echo json_encode($success);
    }
    else{
        $error['message'] = "Data not found!";
        die(json_encode($error));
    }