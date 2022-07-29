<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const TICKET_ID = 'ticket_id';
    const MESSAGE = 'message';


    //validating inputs
    foreach ([USER_ID,TICKET_ID,MESSAGE] as $item) {
        validateInputs($item);
    }

    //checking user_id
    $user_id = $_POST[USER_ID];
    $ticket_id = $_POST[TICKET_ID];
    $message = $_POST[MESSAGE];

    //getting user wallet
    $sql = "insert into ticket_replies(admin_id,user_id,ticket_id,message,user_type,created_at,updated_at) values(12,'$user_id','$ticket_id','$message','user',now(),now())";
    $r = $conn->query($sql);
    if($r){
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Message Sent Successfully!';
        echo json_encode($success);
    }
    else{
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }