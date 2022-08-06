<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const EMAIL = 'email';

    //validating inputs
    foreach ([EMAIL] as $item) {
        validateInputs($item);
    }

    //checking user_id
    $email = $_POST[EMAIL];

    //getting user wallet
    $sql = "select * from users where email = '$email'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $status = $row['status'];
        if($status == 'Suspended'){
            $error['message'] = "Account is Suspended";
            die(json_encode($error));
        }
        if($status == 'Inactive'){
            $error['message'] = "Account is Inactive";
            die(json_encode($error));
        }
        $user_id = $row['id'];
        $sql = "select * from wallets where user_id = '$user_id'";
        $r = $conn->query($sql);
        $row = mysqli_fetch_assoc($r);
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = [
            'balance' => number_format($row['balance'],2)
        ];
        echo json_encode($success);
    }
    else{
        $error['message'] = "User Not Found";
        die(json_encode($error));
    }