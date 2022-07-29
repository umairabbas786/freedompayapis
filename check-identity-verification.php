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

    //checking Email
    $user_id = $_POST[USER_ID];

    //checking user exists or not
    $sql = "select identity_verified from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $status = '';
        if($row['identity_verified'] == 1){
            $status = 'Verified';
        }
        if($row['identity_verified'] == 0){
            $status = 'Unverified';
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = $status;
        echo json_encode($success);
    }
    else{
        $error['message'] = "User Does Not Exists";
        die(json_encode($error));
    }