<?php

include_once 'DB.php';

use DB;

$PRIVATE_KEY = file_get_contents('rsa/walletfactory');
$PUBLIC_KEY = file_get_contents('rsa/walletfactory.pub');

$SALT = 'salt';

$db = DB::connect('localhost', '5433', 'walletfactory_test', 'postgres', 'postgres');

$raw_post_request = file_get_contents('php://input');

if (!empty($raw_post_request)) {
    $array = json_decode($raw_post_request);
    $verify_data = $array->name.$array->email.$array->birthday.$array->message;

    $data = $array->hash;

    $pk  = openssl_get_privatekey($PRIVATE_KEY);
    openssl_private_decrypt(base64_decode($data), $result, $pk);

    if ($verify_data == $result) {
        $db->insert('users', [
            'name' => $array->name,
            'email' => $array->email,
            'birthday' => $array->birthday,
            'message' => $array->message
        ]);
        echo json_encode(['status' => 1, 'message' => 'Успех']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Провал']);
    }

} else {

    $result = $db->select('users', ['name', 'birthday', 'email', 'message']);

    $pk  = openssl_get_publickey($PUBLIC_KEY);

    foreach ($result as $row) {
        $data = $row['name'].$row['birthday'].$row['email'].$row['message'];
        openssl_public_encrypt($data, $encrypted, $pk);
        $array[] = chunk_split(base64_encode($encrypted));
    }

    echo json_encode(['data' => $array]);
}
