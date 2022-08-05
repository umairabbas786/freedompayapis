<?php
    function validateInputs($param){
        $validation = [];
        if(!isset($_POST[$param])){
            $validation['status'] = 'error';
            $validation['missing_param'] = $param;
            die(json_encode($validation));
        }
    }
    //get ip address
    function getIPAddress() {
        //whether ip is from the share internet
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    //whether ip is from the remote address
        else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    //generation random string
    function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
    //generating random uuid for deposit
    function unique_code()
    {
        $length = 13;
        if (function_exists("random_bytes")){
            $bytes = random_bytes(ceil($length / 2));
        }
        elseif (function_exists("openssl_random_pseudo_bytes")){
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        }
        else{
            throw new Exception("no cryptographically secure random function available");
        }
        return strtoupper(substr(bin2hex($bytes), 0, $length));
    }