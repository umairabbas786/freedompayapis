<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const SUBJECT = 'subject';
    const MESSAGE = 'message';
    const PRIORITY = 'priority';


    //validating inputs
    foreach ([USER_ID,SUBJECT,MESSAGE,PRIORITY] as $item) {
        validateInputs($item);
    }

    //checking user_id
    $user_id = $_POST[USER_ID];
    $subject = $_POST[SUBJECT];
    $message = $_POST[MESSAGE];
    $priority = $_POST[PRIORITY];
    $code = 'TIC-' . strtoupper(random_str(6));
    echo $code;

    //getting user wallet
    $sql = "insert into tickets(admin_id,user_id,ticket_status_id,subject,message,code,priority,created_at,updated_at) values(12,'$user_id',1,'$subject','$message','$code','$priority',now(),now())";
    $r = $conn->query($sql);
    if($r){
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Ticket Created Successfully!';
        echo json_encode($success);
    }
    else{
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }