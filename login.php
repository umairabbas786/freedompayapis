<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const EMAIL = 'email';
    const PASSWORD = 'password';

    //validating inputs
    foreach ([EMAIL,PASSWORD] as $item) {
        validateInputs($item);
    }

    //auth user
    $flag = false;
    $email = $_POST[EMAIL];
    $password = $_POST[PASSWORD];
    $sql = "select * from users where email = '$email' or phone = '$email'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >= 1){

        $row = mysqli_fetch_assoc($r);
        $hash = $row['password'];
        $status = $row['status'];
        $user_id = $row['id'];

        if(password_verify($password,$hash)){
            $flag = true;
        }
        else{
            $error['message'] = "Incorrect Password";
            die(json_encode($error));
        }

        if($status == 'Inactive'){
            $error['message'] = "Your account is inactivated. Please try again later!";
            die(json_encode($error));
        }

        if($status == 'Suspended'){
            $error['message'] = "Your account is Suspended. Please Contact Support!";
            die(json_encode($error));
        }

        $sql = "select phone_verification from user_details where user_id = '$user_id'";
        $r = $conn->query($sql);
        $rr = mysqli_fetch_assoc($r);

        if($rr['phone_verification'] == 0){
            $error['message'] = "Verify Your Phone Number";
            die(json_encode($error));
        }


    }else{
        $error['message'] = "Incorrect Email-Phone";
        die(json_encode($error));
    }

    if($flag == true){
        $ip = getIPAddress();
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $sql = "insert into activity_logs(user_id,type,ip_address,browser_agent,created_at,updated_at) values('$user_id','User','$ip','$agent',now(),now())";
        $r = $conn->query($sql);
        if(!$r){
            $error['message'] = "Something went wrong";
            die(json_encode($error));
        }
        $sql = "update user_details set last_login_at = now(), last_login_ip = '$ip' where user_id = '$user_id'";
        $r = $conn->query($sql);
        if(!$r){
            $error['message'] = "Something went wrong";
            die(json_encode($error));
        }
    }

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

