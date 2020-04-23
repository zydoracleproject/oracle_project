<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: multipart/form-data; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
date_default_timezone_set('Asia/Aqtobe');

function getFileName($title, $file)
{
	if (isset($_FILES[$file])) {
		$array = explode('.', $_FILES[$file]['name']);
		return base64_encode($title) . '/' . $file . '.' . end($array);
	}

	return '';
}

// get product_id for editing
$data = json_decode($_POST['data'], true, 512, JSON_THROW_ON_ERROR);

// Connecting with database and creating object
include_once '../config/database.php';
include_once '../objects/product.php';

// Get connection with database
$database = new Database(base64_decode($data['username']), base64_decode($data['password']));
$db = $database->getConnection();

// Initializing an object
$product = new Product($db);

if (!empty($data['title']) && !empty($data['price'])) {
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
	$product->created_at = date('m/d/Y H:i:s');
	$product->images['image_1'] = getFileName($data['title'], 'image_1');
	$product->images['image_2'] = getFileName($data['title'], 'image_2');
	$product->images['image_3'] = getFileName($data['title'], 'image_3');
	$product->options['execution'] = $data['execution'];
	$product->options['appointment'] = $data['appointment'];
	$product->options['power'] = $data['power'];
	$product->options['premises'] = $data['premises'];
	$product->options['height'] = $data['height'];
	$product->options['width'] = $data['width'];
	$product->options['depth'] = $data['depth'];
	$product->options['chamber'] = $data['chamber'];
	$product->options['warranty'] = $data['warranty'];

	if ($product->create()) {
		if ($_FILES['image_1'] || $_FILES['image_2'] || $_FILES['image_3']) {
			if (mkdir($dir = '../../images/' . base64_encode($data['title']), 0777, true) || is_dir($dir)) {
				$res = false;

				if (isset($_FILES['image_1'])) {
					$file_name = getFileName($data['title'], 'image_1');
					$res = move_uploaded_file($_FILES['image_1']['tmp_name'], '../../images/' .  $file_name);
				}

				if (isset($_FILES['image_2'])) {
					$file_name = getFileName($data['title'], 'image_2');
					$res = move_uploaded_file($_FILES['image_2']['tmp_name'], '../../images/' . $file_name);
				}

				if (isset($_FILES['image_3'])) {
					$file_name = getFileName($data['title'], 'image_3');
					$res = move_uploaded_file($_FILES['image_3']['tmp_name'], '../../images/' . $file_name);
				}

				if (!$res) {
					http_response_code(503);

					echo json_encode(['message' => 'Can not upload images'], JSON_THROW_ON_ERROR, 512);
				}
			}
		}
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
