<?php
include_once '../layouts/stats_inc.php';

$date = date('m/d/Y');

$days = array();

$stmt = $stats->visitsPerDay($date, 3);
$first = oci_fetch_assoc($stmt);

$days['d1'] = ['view' => $first['VIEWS'] ?? 0, 'date' => date("d/m", mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")))];

$stmt = $stats->visitsPerDay($date, 2);
$first = oci_fetch_assoc($stmt);

$days['d2'] = ['view' => $first['VIEWS'] ?? 0, 'date' => date("d/m", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y")))];

$stmt = $stats->visitsPerDay($date, 1);
$first = oci_fetch_assoc($stmt);

$days['d3'] = ['view' => $first['VIEWS'] ?? 0, 'date' => date("d/m", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))];

$stmt = $stats->visitsPerDay($date, 0);
$first = oci_fetch_assoc($stmt);

$days['d4'] = ['view' => $first['VIEWS'] ?? 0, 'date' => date("d/m", mktime(0, 0, 0, date("m"), date("d") - 0, date("Y")))];

http_response_code(200);

echo json_encode($days, JSON_THROW_ON_ERROR, 512);
