<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const TRANSACTION_ID = 'transaction_id';

    //validating inputs
    foreach ([TRANSACTION_ID] as $item) {
        validateInputs($item);
    }

    //checking UserId
    $transaction_id = $_POST[TRANSACTION_ID];

    //fetching user transactions
    $sql = "select * from transactions where id = '$transaction_id'";
    $r = $conn->query($sql);
    if(mysqli_num_rows($r) >=1) {
        $row = mysqli_fetch_assoc($r);
        $type = '';
        $title = '';
        $description = '';
        if ($row['status'] == 'Blocked') {
            $row['status'] = 'Cancelled';
        }
        if ($row['transaction_type_id'] == 1) {
            $title = "Deposited Amount";
            $type = 'Deposit';
            $row['subtotal'] = number_format($row['subtotal'], 2);
            $payment_id = $row['payment_method_id'];
            $s = "select name from payment_methods where id = '$payment_id'";
            $result = $conn->query($s);
            $payment = mysqli_fetch_assoc($result);
            $description = $payment['name'];
        }
        if ($row['transaction_type_id'] == 2) {
            $type = 'Withdraw';
            $title = "Withdrawal Amount";
            $row['subtotal'] = number_format($row['subtotal'], 2);
            $payment_id = $row['payment_method_id'];
            $s = "select name from payment_methods where id = '$payment_id'";
            $result = $conn->query($s);
            $payment = mysqli_fetch_assoc($result);
            if ($payment['name'] == 'Mts') {
                $payment['name'] = "FreedomPay Universe";
            }
            $description = $payment['name'];
        }
        if ($row['transaction_type_id'] == 3) {
            $type = 'Transferred';
            $title = "Transferred Amount";
            $row['subtotal'] = number_format($row['subtotal'], 2);
            $description = $row['email'];
        }
        if ($row['transaction_type_id'] == 4) {
            $type = 'Received';
            $title = "Transferred Amount";
            $row['subtotal'] = number_format($row['subtotal'], 2);
            $end_user_id = $row['end_user_id'];
            $s = "select first_name, last_name from users where id = '$end_user_id'";
            $result = $conn->query($s);
            $sender = mysqli_fetch_assoc($result);
            $description = $sender['first_name'] . ' ' . $sender['last_name'] . ' (' . $sender['email'] . ')';
        }
        if ($row['transaction_type_id'] == 9) {
            $type = 'Request From';
        }
        if ($row['transaction_type_id'] == 10) {
            $type = 'Request To';
        }
        $transaction_details = [
            'title' => $title,
            'transaction_id' => $row['id'],
            'date' => $row['created_at'],
            'description' => $description,
            'type' => $type,
            'amount' => '$' . $row['subtotal'],
            'fee' => '$' . number_format($row['charge_percentage'],2),
            'total' => '$' . number_format($row['total'],2),
            'note' => $row['note'],

        ];
        //sending response
        $success = [];
        $success['status'] = 'success';
        $success['response'] = $transaction_details;
        echo json_encode($success);
    }
    else{
        $error['message'] = "Transaction Not Found";
        die(json_encode($error));
    }