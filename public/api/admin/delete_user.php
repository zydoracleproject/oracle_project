<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

include_once '../config/database.php';
include_once '../objects/admin.php';

// Get connection with database
$database = new Database(base64_decode($data['username']), base64_decode($data['password']));
$db = $database->getConnection();

$admin = new Admin($db);

if ($admin->deleteAccount($data['u_username'])) {
	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode(['message' => 'Account is deleted'], JSON_THROW_ON_ERROR, 512);
} else {
	// if can't update product
	// Status code - 503 Service is not available
	http_response_code(503);

	// Send to user
	echo json_encode(['message' => 'Can not delete account'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
