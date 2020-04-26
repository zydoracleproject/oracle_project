<?php
// get product_id for editing
$data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

// Connecting with database and creating object
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/manufacturer.php';

// Get connection with database
$database = new Database(base64_decode($data['username']), base64_decode($data['password']));
$db = $database->getConnection();

// Initializing an object
$manufacturer = new Manufacturer($db);
