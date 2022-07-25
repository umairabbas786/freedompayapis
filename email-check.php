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

    //checking Email
    $email = $_POST[EMAIL];

    //checking user exists or not
    $sql = "select * from users where email = '$email'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Email Exists';
        echo json_encode($success);
    }
    else{
        $error['message'] = "Email Does Not Exists";
        die(json_encode($error));
    }