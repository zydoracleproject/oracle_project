<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../layouts/manufacturer_inc.php';

$manufacturer->id = $_GET['id'] ?? die();

$manufacturer->readOne();

if ($manufacturer->title) {

	// Creating an array
	$manufacturer_arr = [
		'id' => $manufacturer->id,
		'title' => $manufacturer->title,
		'category_id' => $manufacturer->category_id,
		'keywords' => $manufacturer->keywords,
		'description' => $manufacturer->description,
		'created_at' => $manufacturer->created_at,
		'updated_at' => $manufacturer->updated_at,
	];

	// Status code - 200 OK
	http_response_code(200);

	// output in json
	echo json_encode($manufacturer_arr, JSON_THROW_ON_ERROR, 512);
} else {
	// Status code - 404 Not Found
	http_response_code(404);

	// sent a message to user that product is not found
	echo json_encode(['message' => 'Manufacturer does not exist.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

