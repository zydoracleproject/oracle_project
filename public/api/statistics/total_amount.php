<?php
include_once '../layouts/stats_inc.php';

$stmt = $stats->totalAmount();
$first = oci_fetch_assoc($stmt);

if ($first) {
	http_response_code(200);

	echo json_encode($first, JSON_THROW_ON_ERROR, 512);
} else {
	http_response_code(503);

	echo json_encode(['message' => 'Unable to get total'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
