<?php
// Show messages about errors/exceptions
ini_set('display_errors', 1);
error_reporting(E_ALL);

// URL of home page
$home_url = 'http://localhost:8000/api/';

// page is specified in the URL parameter, the default page is one
$page = $_GET['page'] ?? 1;

// set amount of records on page
$records_per_page = 3;

// calculation for querying the record limit
$from_record_num = ($records_per_page * $page) - $records_per_page;
