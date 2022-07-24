<?php
    //libraries
    include "include/db.php";

    //getting data
    $sql = "select * from countries";
    $r = $conn->query($sql);
    $countries = [];
    $count = 0;
    while($row = mysqli_fetch_assoc($r)){
        array_push($countries, $row);
    }

    //sending response
    $success = [];
    $success['status'] = 'success';
    $success['response'] = $countries;
    echo json_encode($success);