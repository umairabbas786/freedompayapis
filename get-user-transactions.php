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

    //checking UserId
    $user_id = $_POST[USER_ID];

    //fetching user transactions
    $sql = "select * from users where id = '$user_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        //getting user email
        $rr = mysqli_fetch_assoc($r);
        $user_email = $rr['email'];

        $transactions = [];
        $sql = "select * from transactions where user_id = '$user_id' order by created_at desc ";
        $r = $conn->query($sql);
        if(mysqli_num_rows($r) >=1) {
            while ($row = mysqli_fetch_assoc($r)) {
                $type = '';
                $description = '';
                if ($row['status'] == 'Blocked') {
                    $row['status'] = 'Cancelled';
                }
                if ($row['transaction_type_id'] == 1) {
                    $type = 'Deposit';
                    $row['subtotal'] = '+' . number_format($row['subtotal'], 2) . ' (USD)';
                    $payment_id = $row['payment_method_id'];
                    $s = "select name from payment_methods where id = '$payment_id'";
                    $result = $conn->query($s);
                    $payment = mysqli_fetch_assoc($result);
                    $description = "Deposit via " . $payment['name'];
                }
                if ($row['transaction_type_id'] == 2) {
                    $type = 'Withdraw';
                    $row['subtotal'] = '-' . number_format($row['subtotal'], 2) . ' (USD)';
                    $payment_id = $row['payment_method_id'];
                    $s = "select name from payment_methods where id = '$payment_id'";
                    $result = $conn->query($s);
                    $payment = mysqli_fetch_assoc($result);
                    if ($payment['name'] == 'Mts') {
                        $payment['name'] = "FreedomPay Universe";
                    }
                    $description = "Withdraw via " . $payment['name'];
                }
                if ($row['transaction_type_id'] == 3) {
                    $type = 'Transferred';
                    $row['subtotal'] = '-' . number_format($row['subtotal'], 2) . ' (USD)';
                    $description = $row['email'];
                }
                if ($row['transaction_type_id'] == 4) {
                    $type = 'Received';
                    $row['subtotal'] = '+' . number_format($row['subtotal'], 2) . ' (USD)';
                    $end_user_id = $row['end_user_id'];
                    $s = "select first_name, last_name from users where id = '$end_user_id'";
                    $result = $conn->query($s);
                    $sender = mysqli_fetch_assoc($result);
                    $description = $sender['first_name'] . ' ' . $sender['last_name'];
                }
                if ($row['transaction_type_id'] == 9) {
                    $type = 'Request From';
                }
                if ($row['transaction_type_id'] == 10) {
                    $type = 'Request To';
                }

                array_push($transactions, [
                    'transaction_id' => $row['id'],
                    'date' => $row['created_at'],
                    'description' => $description,
                    'type' => $type,
                    'status' => $row['status'],
                    'total' => $row['subtotal'],
                ]);
            }
            //sending response
            $success = [];
            $success['status'] = 'success';
            $success['response'] = $transactions;
            echo json_encode($success);
        }
        else{
            $error['message'] = "Sorry! No transaction found!";
            die(json_encode($error));
        }
    }
    else{
        $error['message'] = "User Not Found";
        die(json_encode($error));
    }