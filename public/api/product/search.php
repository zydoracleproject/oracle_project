<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once '../config/core.php';
include_once '../layouts/product_inc.php';

// get keywords from request
$keywords = $_GET['s'] ?? '';

// request products
$stmt = $product->search($keywords);
$num = oci_num_fields($stmt);

if ($num > 0) {

	// Products array
	$products_arr = array();
	$products_arr['records'] = array();

	// Get content from our table
	while ($row = oci_fetch_assoc($stmt)) {
		$product_item = array();
		foreach ($row as $k => $v) {
			$product_item[$k] = $v;
		}

		$products_arr['records'][] = $product_item;
	}

	// Status code - 200 Ok
	http_response_code(200);

	// sent to user
	echo json_encode($products_arr, JSON_THROW_ON_ERROR, 512);
} else {
	// Status code - 404 Not Found
	http_response_code(404);

	// Send to user
	echo json_encode(['message' => 'Products is not found'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
