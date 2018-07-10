<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

$secret = '6LdFZWMUAAAAAEYi6SyTnoCoMNT2KKOt9E_Af_xc';

$url = 'https://www.google.com/recaptcha/api/siteverify';
$fields = array(
        'secret' => $secret,
        'response' => $_POST["response"]
);
$datos = http_build_query($fields);
    
//open connection
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

//execute post
$response = curl_exec($handle);
//close connection
curl_close($handle);

header('Content-Type: application/json;charset=utf-8');
echo $response;
?>