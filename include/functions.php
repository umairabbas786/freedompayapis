<?php
    function validateInputs($param){
        $validation = [];
        if(!isset($_POST[$param])){
            $validation['status'] = 'error';
            $validation['missing_param'] = $param;
            die(json_encode($validation));
        }
    }