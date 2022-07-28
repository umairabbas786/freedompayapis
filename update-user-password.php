<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const PASSWORD = 'password';

    //validating inputs
    foreach ([USER_ID,PASSWORD] as $item) {
        validateInputs($item);
    }

    //checking user_id
    $user_id = $_POST[USER_ID];
    $password = $_POST[PASSWORD];
    $password = password_hash($password, PASSWORD_DEFAULT);

    //getting user wallet
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $sql = "update users set password = '$password' where id = '$user_id'";
        $r = $conn->query($sql);
        if($r) {
            //sending response
            $success = [];
            $success['status'] = 'success';
            $success['response'] = 'User Password Updated Successfully';
            echo json_encode($success);
        }
        else{
            $error['message'] = "Something went Wrong";
            die(json_encode($error));
        }
    }
    else{
        $error['message'] = "User Not Found";
        die(json_encode($error));
    }