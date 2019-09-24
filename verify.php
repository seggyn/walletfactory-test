<?php

$SALT = 'salt';

$raw_post_request = file_get_contents('php://input');

$data = json_decode($raw_post_request);

$verify_hash = md5($SALT.$data->name.$data->email.$data->birthday.$data->message.$SALT);

if ($verify_hash == $data->hash) {
    echo json_encode(['status' => 1, 'message' => 'Успех']);
} else {
    echo json_encode(['status' => 0, 'message' => 'Провал']);
}

