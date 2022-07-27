<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const PHONE = 'phone';

    //validating inputs
    foreach ([PHONE] as $item) {
        validateInputs($item);
    }

    //verifying user
    $phone = $_POST[PHONE];

    //checking user exists or not
    $sql = "select * from users where formattedPhone = '$phone'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $user_id = $row['id'];
        $sql = "update user_details set phone_verification = 1 where user_id = '$user_id'";
        $r = $conn->query($sql);
        if($r){
            //sending response
            $success = [];
            $success['status'] = 'success';
            $success['response'] = 'User Verified Successfully';
            echo json_encode($success);
        }
        else{
            $error['message'] = "Something Went Wrong";
            die(json_encode($error));
        }
    }
    else{
        $error['message'] = "User Not found";
        die(json_encode($error));
    }