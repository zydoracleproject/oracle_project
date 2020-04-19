<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Importing file for connecting
include_once '../layouts/category_inc.php';

// Query for categories
$stmt = $category->read();
$num = oci_num_fields($stmt);

if (num > 0) {

	// array
	$categories_arr = array();
	$categories_arr['records'] = array();

	// Get content from our table
	while ($row = oci_fetch_assoc($stmt)) {
		$category_item = array();
		foreach ($row as $k => $v) {
			$category_item[$k] = $v;
		}

		$categories_arr['records'][] = $category_item;
	}

	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode($categories_arr, JSON_THROW_ON_ERROR, 512);
} else {

	// Status code - 404 Not Found
	http_response_code(404);

	// Send to user
	echo json_encode(['message' => 'Categories are not found'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}