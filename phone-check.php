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

    //checking Email
    $phone = $_POST[PHONE];

    //checking user exists or not
    $sql = "select * from users where phone = '$phone'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Phone Exists';
        echo json_encode($success);
    }
    else{
        $error['message'] = "Phone Does Not Exists";
        die(json_encode($error));
    }