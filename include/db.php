<?php

    $type = "local";

    if($type == "local") {

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "freedom";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    if($type == "live"){
        $servername = "localhost";
        $username = "umairabbas";
        $password = "Devils@dvocate007";
        $dbname = "freedompayuniverse";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
