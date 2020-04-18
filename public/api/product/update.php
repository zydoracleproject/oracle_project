<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../layouts/product_inc.php';

// get product_id for editing
$data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

// set product id
$product->id = $data['id'];

// set product data
$product->title = $data['title'];
$product->content = $data['content'];
$product->model = $data['model'];
$product->price = $data['price'];
$product->status = $data['status'];
$product->pop_status = $data['pop_status'];
$product->amount = $data['amount'];
$product->keywords = $data['keywords'];
$product->description = $data['description'];
$product->manufacturer_id = $data['manufacturer_id'];
$product->category_id = $data['category_id'];
$product->alias = $data['alias'];
$product->created_at = $data['created_at'];
$product->updated_at = date('m/d/Y H:i:s');

if ($product->update()) {
	// Status code - 200 OK
	http_response_code(200);

	// Send to user
	echo json_encode(['message' => 'Product is updated'], JSON_THROW_ON_ERROR, 512);
} else {
	// if can't update product
	// Status code - 503 Service is not available
	http_response_code(503);

	// Send to user
	echo json_encode(['message' => 'Can not update product'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}