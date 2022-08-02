<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const FILE = 'file';

    //validating inputs
    foreach ([USER_ID] as $item) {
        validateInputs($item);
    }

    if(!isset($_FILES[FILE])){
        $error['missing_param'] = FILE;
        die(json_encode($error));
    }

    // get file path info
    $fileInfo = pathinfo($_FILES[FILE]['name']);
    $extension = $fileInfo['extension'];
    // split file name with '.'
    $tmp = explode(".", $_FILES[FILE]['name']);
    // generate a new random name for image
    $newName = time() . rand(0, 99999) . "." . end($tmp);
    // move file to directory  to save
    if (!move_uploaded_file($_FILES[FILE]['tmp_name'], '../public/uploads/user-documents/address-proof-files/' . $newName)) {
        $error['missing_param'] = 'Failed to save File';
        die(json_encode($error));
    }

    $user_id = $_POST[USER_ID];
    $file_id = "";
    $sql = "insert into files(user_id,filename,originalname,type,created_at,updated_at) values('$user_id','$newName','$newName','$extension',now(),now())";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = 'Failed to save File Credentials';
        die(json_encode($error));
    }
    if($r){
        $sql = "select * from files where filename = '$newName'";
        $r = $conn->query($sql);
        $row = mysqli_fetch_assoc($r);
        $file_id = $row['id'];
    }
    $sql = "select * from document_verifications where user_id = '$user_id' and verification_type = 'address'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r)>=1){
        $error['message'] = 'Documents are already submitted';
        die(json_encode($error));
    }
    //uploading documents
    $type = $_POST[TYPE];
    $number = $_POST[NUMBER];
    $sql = "insert into document_verifications(user_id,file_id,verification_type,status,created_at,updated_at) values('$user_id','$file_id','address','pending',now(),now())";
    $r = $conn->query($sql);
    if($r){
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = 'Documents Submitted Successfully!';
        echo json_encode($success);
    }
    else{
        $error['message'] = "Something Went Wrong";
        die(json_encode($error));
    }