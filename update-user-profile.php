<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const ADDRESS_1 = 'address1';
    const ADDRESS_2 = 'address2';
    const CITY = 'city';
    const STATE = 'state';

    //validating inputs
    foreach ([USER_ID,ADDRESS_1,ADDRESS_2,STATE,CITY] as $item) {
        validateInputs($item);
    }

    //checking user_id
    $user_id = $_POST[USER_ID];
    $address1 = $_POST[ADDRESS_1];
    $address2 = $_POST[ADDRESS_2];
    $state = $_POST[STATE];
    $city = $_POST[CITY];

    //getting user wallet
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $sql = "update user_details set address_1 = '$address1', address_2 = '$address2', city = '$city', state = '$state' where user_id = '$user_id'";
        $r = $conn->query($sql);
        if($r) {
            //sending response
            $success = [];
            $success['status'] = 'success';
            $success['response'] = 'User Profile Updated Successfully';
            echo json_encode($success);
        }
        else{
            $error['message'] = "Something went Wrong";
            die(json_encode($error));
        }
    }
    else{
        $error['message'] = "User Not Found";
        die(json_encode($error));
    }