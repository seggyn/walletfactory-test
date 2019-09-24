<?php

$PUBLIC_KEY = file_get_contents('rsa/walletfactory.pub');

$URL = 'http://localhost:8887/walletfactory_test/verify.php';
$SALT = 'salt';

$raw_post_request = file_get_contents('php://input');

$array = json_decode($raw_post_request);
$data = $array->name.$array->birthday.$array->message;

$pk  = openssl_get_publickey($PUBLIC_KEY);
openssl_public_encrypt($data, $encrypted, $pk);

$array->hash = chunk_split(base64_encode($encrypted));

$json = json_encode($array);

$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
curl_setopt($curl, CURLOPT_URL, $URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($curl);

curl_close($curl);

echo $result;
