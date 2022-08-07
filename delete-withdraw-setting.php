<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const WITHDRAW_SETTING_ID = 'withdraw_setting_id';

    //validating inputs
    foreach ([WITHDRAW_SETTING_ID] as $item) {
        validateInputs($item);
    }

    //verifying user
    $id = $_POST[WITHDRAW_SETTING_ID];

    //checking user exists or not
    $sql = "delete from payout_settings where id = '$id'";
    $r = $conn->query($sql);
    if($r) {

        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Setting deleted successfully!';
        echo json_encode($success);
    }
    else{
        $error['message'] = "Unable to delete Setting!";
        die(json_encode($error));
    }