<?php
include_once '../layouts/stats_inc.php';

$visitor_ip = $data['ip'];
$date = date('m/d/Y');

if ($stats->countVisits($date, $visitor_ip)) {
	http_response_code(200);

	echo json_encode(['message' => 'Visit is counted'], JSON_THROW_ON_ERROR, 512);
} else {
	http_response_code(503);

	echo json_encode(['message' => 'Unable to count visit'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
