<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';


    //getting user wallet
    $sql = "select * from fees_limits where payment_method_id = 7 and transaction_type_id = 1";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = [
            'gateway' => 'CoinPayments',
            'charge_percentage' => number_format($row['charge_percentage'],2),
            'min_limit' => number_format($row['min_limit'],2),
            'max_limit' => number_format($row['max_limit'],2),
        ];
        echo json_encode($success);
    }
    else{
        $error['message'] = "No Fee Found";
        die(json_encode($error));
    }