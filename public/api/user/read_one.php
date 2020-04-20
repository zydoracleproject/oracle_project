<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../layouts/user_inc.php';

$user->id = $data['id'] ?? die();

$user->readOne();

if ($user->username) {

	// Creating an array
	$user_arr = [
		'id' => $user->id,
		'username' => $user->username,
		'phone' => $user->phone,
		'mail_index' => $user->mail_index,
		'address' => $user->address,
		'created_at' => $user->created_at,
		'updated_at' => $user->updated_at,
	];

	// Status code - 200 OK
	http_response_code(200);

	// output in json
	echo json_encode($user_arr, JSON_THROW_ON_ERROR, 512);
} else {
	// Status code - 404 Not Found
	http_response_code(404);

	// sent a message to user that product is not found
	echo json_encode(['message' => 'User does not exist.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

