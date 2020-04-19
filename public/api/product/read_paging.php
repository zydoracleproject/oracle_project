<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../layouts/product_inc.php';

// utilities
$utilities = new Utilities();

// Request product
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = oci_num_fields($stmt);

// if rows more than 0
if ($num > 0) {

	// Products array
	$product_arr = array();
	$product_arr['records'] = array();
	$product_arr['paging'] = array();

	// Get content from our table
	while ($row = oci_fetch_assoc($stmt)) {
		$product_item = array();
		foreach ($row as $k => $v) {
			$product_item[$k] = $v;
		}

		$product_arr['records'][] = $product_item;
	}

	// Connect paging
	$total_rows = $product->count();
	$page_url = "{$home_url}product/read_paging.php?";
	$paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
	$product_arr['paging'] = $paging;

	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode($product_arr, JSON_THROW_ON_ERROR, 512);
} else {
	// Status code - 404 Not found
	http_response_code(404);

	// send to user
	echo json_encode(['message' => 'Products are not found.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}