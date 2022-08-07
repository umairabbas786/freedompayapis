<?php
    //libraries
    include "include/db.php";
    include "include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;

    const USER_ID = 'user_id';
    const RECEIVER_EMAIL = 'receiver_email';
    const AMOUNT = 'amount';

    //validating inputs
    foreach ([EMAIL] as $item) {
        validateInputs($item);
    }