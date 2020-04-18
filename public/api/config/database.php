<?php

class Database
{

	// My login data of database
	private $host = 'dhcppc6:1521/XE';
	private $db_name = 'warmhouse';
	private $username = 'admin';
	private $password = '1234';
	public $conn;

	// Connecting with database
	public function getConnection()
	{
		$this->conn = oci_connect($this->username, $this->password, $this->host);

		if(!$this->conn) {
			echo 'Connection error: ' . oci_error()['message'];
		}

		return $this->conn;
	}
}