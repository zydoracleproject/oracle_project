<?php

class Database
{

	// My login data of database
	private $host = 'localhost:1521/XE';
	private $db_name = 'warmhouse';
	private $username = 'guest';
	private $password = 'guest';
	public $conn;

	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

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