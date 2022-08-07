<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //checking user_id

    //getting user wallet
    $sql = "select * from fees_limits where transaction_type_id = 3";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $max_limit = '';
        $row = mysqli_fetch_assoc($r);
        if($row['max_limit'] == null){
            $max_limit = null;
        }else{
            $max_limit = number_format($row['max_limit'],2);
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = [
            'gateway' => 'PayPal',
            'charge_percentage' => number_format($row['charge_percentage'],2),
            'min_limit' => number_format($row['min_limit'],2),
            'max_limit' => $max_limit,
        ];
        echo json_encode($success);
    }
    else{
        $error['message'] = "No Fee Found";
        die(json_encode($error));
    }