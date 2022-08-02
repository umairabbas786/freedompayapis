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

    $user_id = $_POST[USER_ID];
    $sql = "select * from document_verifications where user_id = '$user_id' and verification_type = 'identity'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r)>=1){
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Documents Already Submitted!';
        echo json_encode($success);
    }else{
        $error['message'] = 'Documents Are not submitted';
        die(json_encode($error));
    }