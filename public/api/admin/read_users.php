<?php
// HTTP headers for requests;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

$data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

include_once '../config/database.php';
include_once '../objects/admin.php';

// Get connection with database
$database = new Database(base64_decode($data['username']), base64_decode($data['password']));
$db = $database->getConnection();

$admin = new Admin($db);

$stmt = $admin->getAccounts();
$first = oci_fetch_assoc($stmt);
$num = oci_num_rows($stmt);

if ($num > 0) {
	$users_arr = array();
	$users_arr['records'] = [$first];

	while ($row = oci_fetch_assoc($stmt)) {
		$user_item = array();
		foreach ($row as $k => $v) {
			$user_item[$k] = $v;
		}

		$users_arr['records'][] = $user_item;
	}

	http_response_code(200);

	echo json_encode($users_arr, JSON_THROW_ON_ERROR, 512);
} else {
	http_response_code(404);

	echo json_encode(array('message' => 'Admin users are not found.'), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

