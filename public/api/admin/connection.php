<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// get product_id for editing
$data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

// Connecting with database and creating object
include_once '../config/database.php';
include_once '../objects/product.php';

// Get connection with database
$database = new Database(base64_decode($data['username']), base64_decode($data['password']));
$db = $database->getConnection();

if ($db) {
	http_response_code(200);

	echo json_encode(['username' => $data['username'], 'password' => $data['password']], JSON_THROW_ON_ERROR, 512);
} else {
	http_response_code(404);

	echo json_encode(['message' => 'User does not exist'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
