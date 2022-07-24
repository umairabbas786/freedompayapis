<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const FORMATTED_PHONE = 'formatted_phone';
    const PHONE = 'phone';
    const DEFAULT_COUNTRY = 'country_code';
    const CARRIER_CODE = 'carrier_code';
    const EMAIL = 'email';
    const PASSWORD = 'password';



    //validating inputs
    foreach ([FIRST_NAME,LAST_NAME,FORMATTED_PHONE,EMAIL,PHONE,DEFAULT_COUNTRY,CARRIER_CODE,PASSWORD] as $item) {
        validateInputs($item);
    }

    //checking duplicate email
    $email = $_POST[EMAIL];
    $sql = "select email from users where email = '$email'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r)>=1){
        $error['message'] = "Email already Exists";
        die(json_encode($error));
    }

    //checking duplicate phone
    $phone = $_POST[PHONE];
    $sql = "select phone from users where phone = '$phone'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r)>=1){
        $error['message'] = "Phone already Exists";
        die(json_encode($error));
    }

    //register user















$success = [];
$success['status'] = 'success';