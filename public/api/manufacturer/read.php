<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Importing file for connecting
include_once '../layouts/manufacturer_inc.php';

// Query for categories
$stmt = $manufacturer->read();
$first = oci_fetch_assoc($stmt);
$num = oci_num_rows($stmt);

if ($num > 0) {

	// array
	$manufacturer_arr = array();
	$manufacturer_arr['records'] = [$first];

	// Get content from our table
	while ($row = oci_fetch_assoc($stmt)) {
		$manufacturer_item = array();
		foreach ($row as $k => $v) {
			$manufacturer_item[$k] = $v;
		}

		$manufacturer_arr['records'][] = $manufacturer_item;
	}

	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode($manufacturer_item, JSON_THROW_ON_ERROR, 512);
} else {

	// Status code - 404 Not Found
	http_response_code(404);

	// Send to user
	echo json_encode(['message' => 'Manufacturers are not found'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}