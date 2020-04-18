<?php
// HTTP headers for requests;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once '../layouts/product_inc.php';

// Request products
$stmt = $product->read();
$num = oci_num_fields($stmt);

// Checking for data existing
if ($num > 0) {

	// array of products
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

	http_response_code(200);

	echo json_encode($products_arr);
} else {
	// set status code - 404 not found
	http_response_code(404);

	// Send a message to user that products are not found
	echo json_encode(array("message" => "Products are not found."), JSON_UNESCAPED_UNICODE);
}

