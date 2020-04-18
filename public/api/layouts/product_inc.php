<?php
// Connecting with database and creating object
include_once '../config/database.php';
include_once '../objects/product.php';

// Get connection with database
$database = new Database();
$db = $database->getConnection();

// Initializing an object
$product = new Product($db);
