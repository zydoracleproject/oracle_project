<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../layouts/user_inc.php';

if (!empty($data['phone']) && !empty($data['u_password'])) {
	$user->phone = $data['phone'];
	$user->password = md5($data['u_password']);

	$stmt = $user->check();
	$user_from_table = oci_fetch_assoc($stmt);
	$num = oci_num_rows($stmt);

	if ($num) {
		$user->id = $user_from_table['ID'];
		$user->remember_token = $user->getToken();
		if ($user->setToken()) {
			$user_arr = [
				'id' => $user_from_table['ID'],
				'username' => $user_from_table['USERNAME'],
				'phone' => $user_from_table['PHONE'],
				'mail_index' => $user_from_table['MAIL_INDEX'],
				'address' => $user_from_table['ADDRESS'],
				'remember_token' => $user->remember_token,
				'created_at' => $user_from_table['CREATED_AT'],
				'updated_at' => $user_from_table['UPDATED_AT'],
			];

			// Status code - 200 OK
			http_response_code(200);

			// output in json
			echo json_encode($user_arr, JSON_THROW_ON_ERROR, 512);
		} else {
			// Status code - 503
			http_response_code(503);

			// Send to user
			echo json_encode(['message' => 'Can not set token'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
		}
	} else {
		// Status code - 404 Not Found
		http_response_code(404);

		// sent a message to user that product is not found
		echo json_encode(['message' => 'User does not exist.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
	}
} else {
	// set status code - 400 incorrect request
	http_response_code(400);

	// send to user
	echo json_encode(['message' => 'Incomplete data'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

