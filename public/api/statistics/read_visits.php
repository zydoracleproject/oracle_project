<?php
include_once '../layouts/stats_inc.php';

$date = date('m/d/Y');

$stmt = $stats->readVisits($date);
$first = oci_fetch_assoc($stmt);
$num = oci_num_rows($stmt);

if ($num >= 0 && $first) {
	$visits = array();
	$visits['records'] = [$first];

	while ($row = oci_fetch_assoc($stmt)) {
		$visit_item = array();
		foreach ($row as $k => $v) {
			$visit_item[$k] = $v;
		}

		$visits['records'][] = $visit_item;
	}

	http_response_code(200);

	echo json_encode($visits, JSON_THROW_ON_ERROR, 512);
} else {
	http_response_code(404);

	echo json_encode(['message' => 'Visits are not found'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE, 512);
}
