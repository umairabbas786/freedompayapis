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
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $sql = "select * from user_details where user_id = '$user_id'";
        $r = $conn->query($sql);
        $rr = mysqli_fetch_assoc($r);
        if($row['picture'] == null){
            $row['picture'] = 'https://freedompayuniverse.com/public/admin_dashboard/img/avatar.jpg';
        }
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = [
            'profile_picture' => 'https://freedompayuniverse.com/public/user_dashboard/profile/' . $row['picture'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone' => $row['formattedPhone'],
            'address1' => $rr['address_1'],
            'address2' => $rr['address_2'],
            'city' => $rr['city'],
            'state' => $rr['state']
        ];
        echo json_encode($success);
    }
    else{
        $error['message'] = "User Not Found";
        die(json_encode($error));
    }
