<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../layouts/category_inc.php';

$category->id = $data['id'] ?? die();

$category->readOne();

if ($category->title) {

	// Creating an array
	$category_arr = [
		'id' => $category->id,
		'title' => $category->title,
		'keywords' => $category->keywords,
		'description' => $category->description,
		'created_at' => $category->created_at,
		'updated_at' => $category->updated_at,
	];

	// Status code - 200 OK
	http_response_code(200);

	// output in json
	echo json_encode($category_arr, JSON_THROW_ON_ERROR, 512);
} else {
	// Status code - 404 Not Found
	http_response_code(404);

	// sent a message to user that product is not found
	echo json_encode(['message' => 'Category does not exist.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

