<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const PHONE = 'phone';
    const PASSWORD = 'password';

    //validating inputs
    foreach ([PHONE,PASSWORD] as $item) {
        validateInputs($item);
    }

    //changing password
    $phone = $_POST[PHONE];
    $password = $_POST[PASSWORD];
    $password = password_hash($password, PASSWORD_DEFAULT);

    //checking user exists or not
    $sql = "select * from users where phone = '$phone'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $sql = "update users set password = '$password' where phone = '$phone'";
        $r = $conn->query($sql);
        if($r){
            //sending response
            $success = [];
            $success['status'] = 'success';
            $success['response'] = 'Password reset successfully';
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