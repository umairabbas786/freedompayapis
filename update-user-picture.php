<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const PICTURE = 'picture';

    //validating inputs
    foreach ([USER_ID] as $item) {
        validateInputs($item);
    }
    if(!isset($_FILES[PICTURE])){
        $error['missing_param'] = PICTURE;
        die(json_encode($error));
    }


    //uploading user profile
    $user_id = $_POST[USER_ID];

    // get file path info
    $fileInfo = pathinfo($_FILES[PICTURE]['name']);
    // split file name with '.'
    $tmp = explode(".", $_FILES[PICTURE]['name']);
    // generate a new random name for image
    $newName = time() . rand(0, 99999) . "." . end($tmp);
    // move file to directory  to save
    if (!move_uploaded_file($_FILES[PICTURE]['tmp_name'], '../public/user_dashboard/profile/' . $newName)) {
        $error['missing_param'] = 'Failed to save Image';
        die(json_encode($error));
    }

    //updating profile picture
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $sql = "update users set picture = '$newName' where id = '$user_id'";
        $r = $conn->query($sql);
        if($r) {
            //sending response
            $success = [];
            $success['status'] = 'success';
            $success['response'] = 'User Picture Uploaded Successfully';
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