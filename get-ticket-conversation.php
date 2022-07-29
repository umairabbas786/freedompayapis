<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const TICKET_ID = 'ticket_id';

    //validating inputs
    foreach ([USER_ID,TICKET_ID] as $item) {
        validateInputs($item);
    }

    //checking user_id
    $user_id = $_POST[USER_ID];
    $ticket_id = $_POST[TICKET_ID];
    $message = $_POST[MESSAGE];

    //getting user details
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Invalid User id";
        die(json_encode($error));
    }
    $row = mysqli_fetch_assoc($r);
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $user_name = $first_name . ' ' . $last_name;

    //getting admin details
    $admin_name = 'Admin';

    $details = "";


    $conversation = [];
    $s = "select message,created_at from tickets where id = '$ticket_id'";
    $result = $conn->query($s);
    if(mysqli_num_rows($result)>=1){
        $rr = mysqli_fetch_assoc($result);
        //default convo
        $convo = [
            'name' => $user_name,
            'type' => 'user',
            'message' => $rr['message'],
            'date' => $rr['created_at'],
        ];
        array_push($conversation,$convo);

        $sql = "select * from ticket_replies where user_id = '$user_id' and ticket_id = '$ticket_id' order by created_at desc";
        $r = $conn->query($sql);
        $final = [];
        while($row = mysqli_fetch_assoc($r)){
            if($row['user_type'] == 'user'){
                $details = $user_name;
            }
            if($row['user_type'] == 'admin'){
                $details = $admin_name;
            }
            array_push($conversation,[
                'name' => $details,
               'message' => $row['message'],
               'type' => $row['user_type'],
                'date' => $row['created_at'],
            ]);
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = $conversation;
        echo json_encode($success);
    }
    else{
        $error['message'] = "Invalid User or Ticket Id";
        die(json_encode($error));
    }