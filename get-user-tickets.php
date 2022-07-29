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

    //checking user_id
    $user_id = $_POST[USER_ID];

    //getting user wallet
    $sql = "select * from tickets where user_id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $tickets = [];
        while($row = mysqli_fetch_assoc($r)){

            if($row['ticket_status_id'] == 4){
                $row['ticket_status_id'] = "Closed";
            }
            if($row['ticket_status_id'] == 3){
                $row['ticket_status_id'] = "Hold";
            }
            if($row['ticket_status_id'] == 2){
                $row['ticket_status_id'] = "In Progress";
            }
            if($row['ticket_status_id'] == 1){
                $row['ticket_status_id'] = "Open";
            }

            array_push($tickets,[
                'ticket_id' => $row['id'],
                'ticket_number' => $row['code'],
                'subject' => $row['subject'],
                'status' => $row['ticket_status_id'],
                'priority' => $row['priority'],
                'date' => $row['created_at']
            ]);
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = $tickets;
        echo json_encode($success);
    }
    else{
        $error['message'] = "Sorry! Data not found!";
        die(json_encode($error));
    }