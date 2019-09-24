<?php

$URL = 'http://localhost:8887/walletfactory_test/verify.php';
$SALT = 'salt';

$raw_post_request = file_get_contents('php://input');

$data = json_decode($raw_post_request);

$hash = md5($SALT.$data->name.$data->email.$data->birthday.$data->message.$SALT);

$data->hash = $hash;

$json = json_encode($data);

$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
curl_setopt($curl, CURLOPT_URL, $URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl);

curl_close($curl);

echo $result;
