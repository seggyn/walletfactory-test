<?php

$PRIVATE_KEY = file_get_contents('rsa/walletfactory');
$SALT = 'salt';

$raw_post_request = file_get_contents('php://input');

$array = json_decode($raw_post_request);
$verify_data = $array->name.$array->birthday.$array->message;

$data = $array->hash;

$pk  = openssl_get_privatekey($PRIVATE_KEY);
openssl_private_decrypt(base64_decode($data), $result, $pk);

if ($verify_data == $result) {
    echo json_encode(['status' => 1, 'message' => 'Успех']);
} else {
    echo json_encode(['status' => 0, 'message' => 'Провал']);
}

