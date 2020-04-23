<?php

class Admin {

	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}
}
