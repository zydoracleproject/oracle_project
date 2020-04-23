<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../layouts/user_inc.php';

// set product id for deleting
$user->id = $data['id'];

// deleting product
if ($user->delete()) {
	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode(['message' => 'User is deleted'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
} else {
	// if can't delete product
	// Status code - 503 Service is unavailable
	http_response_code(503);

	// Send to user
	echo json_encode(['message' => 'Could not delete user.'], JSON_THROW_ON_ERROR, 512);
}
