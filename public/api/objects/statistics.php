<?php

class Statistics {

	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function usersPerWeek() {
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
}
