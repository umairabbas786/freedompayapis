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

    //checking user_id
    $user_id = $_POST[USER_ID];

    //getting user wallet
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $sql = "select * from wallets where user_id = '$user_id' and currency_id = 1";
        $r = $conn->query($sql);
        $row = mysqli_fetch_assoc($r);
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = [
            'user_balance' => number_format($row['balance'],2)
        ];
        echo json_encode($success);
    }
    else{
        $error['message'] = "User Not Found";
        die(json_encode($error));
    }