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
    const COUNTRY_ID = 'country_id';
    const CARRIER_CODE = 'carrier_code';
    const EMAIL = 'email';
    const PASSWORD = 'password';



    //validating inputs
    foreach ([FIRST_NAME,LAST_NAME,FORMATTED_PHONE,EMAIL,PHONE,DEFAULT_COUNTRY,CARRIER_CODE,PASSWORD,COUNTRY_ID] as $item) {
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

    //insert into users
    $first_name = $_POST[FIRST_NAME];
    $last_name = $_POST[LAST_NAME];
    $formattedPhone = $_POST[FORMATTED_PHONE];
    $defaultCountry = $_POST[DEFAULT_COUNTRY];
    $carrierCode = $_POST[CARRIER_CODE];
    $password = $_POST[PASSWORD];
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "insert into users(role_id,type,first_name,last_name,formattedPhone,phone,defaultCountry,carrierCode,email,password,created_at,updated_at,picture) values(2,'user','$first_name','$last_name','$formattedPhone','$phone','$defaultCountry','$carrierCode','$email','$password',now(),now(),'')";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Unable to Register";
        die(json_encode($error));
    }

    //get registered user id
    $sql = "select id from users where email = '$email'";
    $r = $conn->query($sql);
    $row = mysqli_fetch_assoc($r);
    $user_id = $row['id'];

    //insert into user roles
    $sql = "insert into role_user(user_id,role_id,user_type) values('$user_id',2,'User')";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Unable to Register";
        die(json_encode($error));
    }

    //insert into user_details
    $country_id = $_POST[COUNTRY_ID];
    $sql = "insert into user_details(user_id,country_id,email_verification,phone_verification,two_step_verification_type,timezone) values ('$user_id','$country_id',1,1,'disabled','Asia/Dhaka')";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Unable to Register";
        die(json_encode($error));
    }
    //insert into user_wallet
    $sql = "insert into wallets(user_id,currency_id,is_default,created_at,updated_at) values('$user_id',1,'Yes',now(),now())";
    $r = $conn->query($sql);
    if(!$r){
        $error['message'] = "Unable to Register";
        die(json_encode($error));
    }

    //get registered user_details
    $sql = "select * from users where email = '$email'";
    $r = $conn->query($sql);
    $row = mysqli_fetch_assoc($r);

    //sending response
    $success = [];
    $success['status'] = 'success';
    $success['response'] = [
        'user_id' => $row['id'],
        'first_name' => $row['first_name'],
        'last_name' => $row['last_name'],
        'email' => $row['email'],
        'formatted_phone' => $row['formattedPhone'],
        'phone' => $row['phone'],
        'status' => $row['status'],
        'created_at' => $row['created_at']
    ];
    echo json_encode($success);