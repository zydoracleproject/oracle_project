<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
date_default_timezone_set('Asia/Aqtobe');

include_once '../layouts/user_inc.php';

if (!empty($data['u_username']) && !empty($data['u_password']) && !empty($data['phone'])) {

	// set fields for products
	$user->username = $data['u_username'];
	$user->password = md5($data['u_password']);
	$user->phone = $data['phone'];
	$user->created_at = date('m/d/Y H:i:s');

	if ($user->create()) {
		// set status code - 201 Created
		http_response_code(201);

		// send to user
		echo json_encode(['message' => 'User is created'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);

	} else {
		// if product is not create send a message to user
		// set code status - 503 service is not available
		http_response_code(503);

		// send to user
		echo json_encode(['message' => 'Unable to create user'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
	}
} else {
	// send to user - incomplete data
	// set status code - 400 incorrect request
	http_response_code(400);

	// send to user
	echo json_encode(['message' => 'Unable to create user. Incomplete data'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
