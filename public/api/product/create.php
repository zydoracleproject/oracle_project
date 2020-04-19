<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../layouts/product_inc.php';

if (!empty($data['title']) && !empty($data['price'])
	&& !empty($data['alias'])) {

	// set fields for products
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
	$product->created_at = date('m/d/Y H:i:s');

	if ($product->create()) {
		// set status code - 201 Created
		http_response_code(201);

		// send to user
		echo json_encode(['message' => 'Product is created'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);

	} else {
		// if product is not create send a message to user
		// set code status - 503 service is not available
		http_response_code(503);

		// send to user
		echo json_encode(['message' => 'Unable to create product'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
	}
} else {
	// send to user - incomplete data
	// set status code - 400 incorrect request
	http_response_code(400);

	// send to user
	echo json_encode(['message' => 'Unable to create product. Incomplete data'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
