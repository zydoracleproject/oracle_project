<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../layouts/user_inc.php';

if (!empty($data['remember_token'])) {
	$user->remember_token = base64_decode($data['remember_token']);

	$stmt = $user->getByToken();
	$user_from_table = oci_fetch_assoc($stmt);

	if ($user_from_table) {
		$user_arr = [
			'id' => $user_from_table['ID'],
			'username' => $user_from_table['USERNAME'],
			'phone' => $user_from_table['PHONE'],
			'mail_index' => $user_from_table['MAIL_INDEX'],
			'address' => $user_from_table['ADDRESS'],
			'created_at' => $user_from_table['CREATED_AT'],
			'updated_at' => $user_from_table['UPDATED_AT'],
		];

		http_response_code(200);

		echo json_encode($user_arr, JSON_THROW_ON_ERROR, 512);
	} else {
		http_response_code(404);

		echo json_encode(['message' => 'Can not get user by token'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
	}
} else {
	// set status code - 400 incorrect request
	http_response_code(400);

	// send to user
	echo json_encode(['message' => 'Incomplete data'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

