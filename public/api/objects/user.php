<?php

class User
{
	// Set connection with db and users table
	private $conn;
	private $table_name = 'users';

	// Table columns
	public $id, $username, $password, $phone, $mail_index, $address, $remember_token, $created_at, $updated_at;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function read()
	{
		$query = 'SELECT username, phone, mail_index, 
       				address, created_at, updated_at 
							FROM ' . $this->table_name . ' ORDER BY created_at';

		$stmt = oci_parse($this->conn, $query);

		oci_execute($stmt);

		return $stmt;
	}

	public function create()
	{
		$query = 'SELECT phone NUM_ROWS FROM ' . $this->table_name . '
							WHERE phone = :phone';
		$stmt = oci_parse($this->conn, $query);

		$this->phone = htmlspecialchars(strip_tags($this->phone));
		oci_bind_by_name($stmt, ':phone', $this->phone);

		oci_execute($stmt);
		oci_fetch($stmt);

		if (!oci_num_rows($stmt)) {
			$query = 'INSERT INTO ' . $this->table_name . " 
							(username, password, phone, created_at)
							VALUES (:username, :password, :phone, TO_TIMESTAMP(:created_at, 'MM/DD/YYYY HH24:MI:SS'))";
			$stmt = oci_parse($this->conn, $query);

			$this->clean();

			oci_bind_by_name($stmt, ':username', $this->username);
			oci_bind_by_name($stmt, ':password', $this->password);
			oci_bind_by_name($stmt, ':phone', $this->phone);
			oci_bind_by_name($stmt, ':created_at', $this->created_at);

			if (oci_execute($stmt)) {
				return true;
			}
		}

		return false;
	}

	public function update()
	{
		$query = 'UPDATE ' . $this->table_name . '
							SET
									username = :username,
									password = :password,
									phone = :phone,
									mail_index = :mail_index,
									address = :address,
									created_at = :created_at,
									updated_at = :updated_at
							WHERE id = :id';
		$stmt = oci_parse($this->conn, $query);

		$this->clean();

		oci_bind_by_name($stmt, ':id', $this->id);
		oci_bind_by_name($stmt, ':username', $this->username);
		oci_bind_by_name($stmt, ':password', $this->password);
		oci_bind_by_name($stmt, ':phone', $this->phone);
		oci_bind_by_name($stmt, ':mail_index', $this->mail_index);
		oci_bind_by_name($stmt, ':created_at', $this->created_at);
		oci_bind_by_name($stmt, ':updated_at', $this->updated_at);

		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	public function delete()
	{
		$query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';

		$stmt = oci_parse($this->conn, $query);

		$this->id = htmlspecialchars(strip_tags($this->id));

		oci_bind_by_name($stmt, ':id', $this->id);

		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	public function readOne()
	{
		$query = 'SELECT * FROM ' . $this->table_name . ' 
							WHERE id = :id';
		$stmt = oci_parse($this->conn, $query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		oci_bind_by_name($stmt, ':id', $this->id);

		oci_execute($stmt);

		$row = oci_fetch_assoc($stmt);

		$this->username = $row['USERNAME'];
		$this->phone = $row['PHONE'];
		$this->mail_index = $row['MAIL_INDEX'];
		$this->address = $row['ADDRESS'];
		$this->created_at = $row['CREATED_AT'];
		$this->updated_at = $row['UPDATED_AT'];
	}

	public function check()
	{
		$query = 'SELECT id, username, phone, mail_index, address, created_at, updated_at FROM ' . $this->table_name . ' 
							WHERE LOWER(phone) = LOWER(:phone) AND password = :password';
		$stmt = oci_parse($this->conn, $query);

		$this->phone = htmlspecialchars(strip_tags($this->phone));
		$this->password = htmlspecialchars(strip_tags($this->password));

		oci_bind_by_name($stmt, ':phone', $this->phone);
		oci_bind_by_name($stmt, ':password', $this->password);

		oci_execute($stmt);

		return $stmt;
	}

	public function getToken()
	{
		// Create token header as a JSON string
		$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256'], JSON_THROW_ON_ERROR, 512);

		// Create token payload as a JSON string
		$payload = json_encode(['user_id' => $this->id], JSON_THROW_ON_ERROR, 512);

		// Encode Header to Base64Url String
		$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

		// Encode Payload to Base64Url String
		$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

		// Create Signature Hash
		$signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, 'T@!g@t(888)', true);

		// Encode Signature to Base64Url String
		$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		// Return JWT
		return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
	}

	public function setToken()
	{
		$query = 'UPDATE ' . $this->table_name . ' 
							SET
									remember_token = :token
							WHERE id = :id';
		$stmt = oci_parse($this->conn, $query);

		$this->remember_token = htmlspecialchars(strip_tags($this->remember_token));
		$this->id = htmlspecialchars(strip_tags($this->id));
		oci_bind_by_name($stmt, ':token', $this->remember_token);
		oci_bind_by_name($stmt, ':id', $this->id);

		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	public function getByToken()
	{
		$query = 'SELECT id, username, phone, mail_index, address, created_at, updated_at
							FROM ' . $this->table_name . ' WHERE remember_token = :token';
		$stmt = oci_parse($this->conn, $query);

		$this->remember_token = htmlspecialchars(strip_tags($this->remember_token));
		oci_bind_by_name($stmt, ':token', $this->remember_token);

		oci_execute($stmt);

		return $stmt;
	}

	private function clean()
	{
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->password = htmlspecialchars(strip_tags($this->password));
		$this->phone = htmlspecialchars(strip_tags($this->phone));
		$this->mail_index = htmlspecialchars(strip_tags($this->mail_index));
		$this->address = htmlspecialchars(strip_tags($this->address));
		$this->remember_token = htmlspecialchars(strip_tags($this->remember_token));
		$this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
	}
}