<?php
    $error = [];
    $error['status'] = 'error';
    $error['message'] = 'You have cancelled your payment';
    die(json_encode($error));
