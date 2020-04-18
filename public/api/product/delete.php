<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../layouts/product_inc.php';

$data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

// set product id for deleting
$product->id = $data['id'];

// deleting product
if ($product->delete()) {
	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode(['message' => 'Product is deleted'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
} else {
	// if can't delete product
	// Status code - 503 Service is unavailable
	http_response_code(503);

	// Send to user
	echo json_encode(['message' => 'Could not delete product.'], JSON_THROW_ON_ERROR, 512);
}
