<?php
    include "include/db.php";
    $sql = "select value from settings where name = 'login_via'";
    $r = $conn->query($sql);
    if($row = mysqli_fetch_assoc($r)){
        echo $row['value'];
    }