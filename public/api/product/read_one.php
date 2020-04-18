<?php
// http headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../layouts/product_inc.php';

$product->id = $_GET['id'] ?? die();

$product->readOne();

if ($product->title) {

	// Creating an array
	$product_arr = [
		'id' => $product->id,
		'title' => $product->title,
		'content' => $product->content,
		'model' => $product->model,
		'price' => $product->price,
		'status' => $product->status,
		'pop_status' => $product->pop_status,
		'amount' => $product->amount,
		'keywords' => $product->keywords,
		'description' => $product->description,
		'manufacturer_id' => $product->manufacturer_id,
		'category_id' => $product->category_id,
		'alias' => $product->alias,
		'created_at' => $product->created_at
	];

	// Status code - 200 OK
	http_response_code(200);

	// output in json
	echo json_encode($product_arr, JSON_THROW_ON_ERROR, 512);
} else {
	// Status code - 404 Not Found
	http_response_code(404);

	// sent a message to user that product is not found
	echo json_encode(['message' => 'Product does not exist.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}

