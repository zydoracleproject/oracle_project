<?php

class Statistics
{

	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function usersPerWeek()
	{
		$query = "WITH
							W1 AS (SELECT COUNT(*) AS WEEK_1
							        FROM users WHERE created_at BETWEEN TRUNC(current_date, 'WW') - 21 AND TRUNC(current_date, 'WW') - 14),
							W2 AS (SELECT COUNT(*) AS WEEK_2
							        FROM users WHERE created_at BETWEEN TRUNC(current_date, 'WW') - 14 AND TRUNC(current_date, 'WW') - 7),
							W3 AS (SELECT COUNT(*) AS WEEK_3
							        FROM users WHERE created_at BETWEEN TRUNC(current_date, 'WW') - 7 AND TRUNC(current_date, 'WW')),
							W4 AS (SELECT COUNT(*) AS WEEK_4
							        FROM users WHERE created_at > TRUNC(current_date, 'WW')),
			        T AS (SELECT COUNT(*) AS TOTAL FROM users
			              WHERE created_at > TRUNC(current_date, 'MM'))
							SELECT * FROM W1, W2, W3, W4, T";

		$stmt = oci_parse($this->conn, $query);

		oci_execute($stmt);

		return $stmt;
	}

	public function totalAmount()
	{
		$query = 'WITH
							P AS (SELECT COUNT(*) AS PRODUCTS FROM products),
							C AS (SELECT COUNT(*) AS CATEGORIES FROM categories),
							M AS (SELECT COUNT(*) AS MANUFACTURERS FROM manufacturers),
							U AS (SELECT COUNT(*) AS USERS FROM users),
							A AS (SELECT COUNT(*) AS ACCOUNTS FROM admin_users)
							SELECT * FROM P, C, M, U, A';
		$stmt = oci_parse($this->conn, $query);
		oci_execute($stmt);
		return $stmt;
	}

	public function countVisits($date, $visitor_ip)
	{
		$date = htmlspecialchars(strip_tags($date));
		$visitor_ip = htmlspecialchars(strip_tags($visitor_ip));

		// check today visits
		$query = "SELECT visit_id FROM visits WHERE visit_date = TO_DATE(:v_date, 'MM/DD/YYYY')";
		$stmt = oci_parse($this->conn, $query);
		oci_bind_by_name($stmt, ':v_date', $date);
		oci_execute($stmt);

		$first = oci_fetch_assoc($stmt);
		$num = oci_num_rows($stmt);

		// If visits have already been today
		if ($num >= 0 && $first) {
			// check current IP address existing in DB
			$query = 'SELECT ip_id FROM ips WHERE ip_address = :address';
			$stmt = oci_parse($this->conn, $query);
			oci_bind_by_name($stmt, ':address', $visitor_ip);
			oci_execute($stmt);

			$current_ip = oci_fetch_assoc($stmt);

			// If IP address have already been today
			if ($current_ip) {
				$query = "UPDATE visits SET views = views + 1 WHERE visit_date = TO_DATE(:v_date, 'MM/DD/YYYY')";
				$stmt = oci_parse($this->conn, $query);
				oci_bind_by_name($stmt, ':v_date', $date);
				if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
					oci_commit($this->conn);
					return true;
				}
				oci_rollback($this->conn);
				return false;
				// if IP address have not been today
			} else {
				$query = 'INSERT INTO ips (ip_address) VALUES (:ip)';
				$stmt = oci_parse($this->conn, $query);
				oci_bind_by_name($stmt, ':ip', $visitor_ip);

				if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
					$query = "UPDATE visits SET hosts = hosts + 1, views = views + 1 WHERE visit_date = TO_DATE(:v_date, 'MM/DD/YYYY')";
					$stmt = oci_parse($this->conn, $query);
					oci_bind_by_name($stmt, ':v_date', $date);
					if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
						oci_commit($this->conn);
						return true;
					}
				}

				oci_rollback($this->conn);
				return false;
			}
		} else {
			// if there are no visits today
			// Clear ips table
			$stmt = oci_parse($this->conn, 'DELETE FROM ips');
			if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
				// INSERT current visitor ip address INTO ips
				$query = 'INSERT INTO ips (ip_address) VALUES (:ip)';
				$stmt = oci_parse($this->conn, $query);
				oci_bind_by_name($stmt, ':ip', $visitor_ip);
				if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
					// INSERT visit date and set amount of unique visits and views
					$query = "INSERT INTO visits (visit_date, hosts, views) VALUES (TO_DATE(:v_date, 'MM/DD/YYYY'), 1, 1)";
					$stmt = oci_parse($this->conn, $query);
					oci_bind_by_name($stmt, ':v_date', $date);
					if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
						oci_commit($this->conn);
						return true;
					}
				}
			}

			oci_rollback($this->conn);
			return false;
		}
	}

	public function readVisits($date) {
		$date = htmlspecialchars(strip_tags($date));

		$query = "SELECT views, hosts FROM visits WHERE visit_date = TO_DATE(:v_date, 'MM/DD/YYYY')";
		$stmt = oci_parse($this->conn, $query);
		oci_bind_by_name($stmt, ':v_date', $date);
		oci_execute($stmt);

		return $stmt;
	}

	public function visitsPerDay($date, $day) {
		$date = htmlspecialchars(strip_tags($date));
		$day = htmlspecialchars(strip_tags($day));

		$query = "SELECT views FROM visits WHERE visit_date = TO_DATE(:v_date, 'MM/DD/YYYY') - :v_day";
		$stmt = oci_parse($this->conn, $query);
		oci_bind_by_name($stmt, ':v_date', $date);
		oci_bind_by_name($stmt, ':v_day', $day);
		oci_execute($stmt);

		return $stmt;
	}
}
