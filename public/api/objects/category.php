<?php

class Category
{

	// Connecting with DB and categories table
	private $conn;
	private $table_name = 'categories';

	// Table columns
	public $id, $title, $keywords, $description, $created_at, $updated_at;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	// method read() - Selecting all from categories
	public function read()
	{
		// query for selecting
		$query = 'SELECT * FROM ' . $this->table_name . ' ORDER BY created_at';

		$stmt = oci_parse($this->conn, $query);
		oci_execute($stmt);

		return $stmt;
	}

	// method create() - Creating category
	public function create()
	{
		// request for inserting records
		$query = 'INSERT INTO ' . $this->table_name . " 
							(title, keywords, description, created_at) 
							VALUES (:title, :keywords, :description, TO_TIMESTAMP(TO_DATE(:created_at, 'MM/DD/YYYY HH24:MI:SS')))";

		$stmt = oci_parse($this->conn, $query);

		// Cleaning values
		$this->clean();

		// Bind values by names
		$this->bindByNames($stmt);

		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	// method update() - updating category
	public function update()
	{
		// query for updating record (category)
		$query = 'UPDATE ' . $this->table_name . "
							SET 
									title = :title,
									keywords = :keywords,
									description = :description,
									created_at = :created_at,
									updated_at = TO_TIMESTAMP(TO_DATE(:updated_at, 'MM/DD/YYYY HH24:MI:SS'))
							WHERE id = :id";

		// preparing query
		$stmt = oci_parse($this->conn, $query);

		// cleaning
		$this->clean();

		// Bind by names
		$this->bindByNames($stmt);
		oci_bind_by_name($stmt, ':id', $this->id);
		oci_bind_by_name($stmt, ':updated_at', $this->updated_at);

		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	// method delete() - deleting category
	public function delete()
	{
		// query for deleting record (category)
		$query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';

		// Preparing query
		$stmt = oci_parse($this->conn, $query);

		// cleaning
		$this->id = htmlspecialchars(strip_tags($this->id));

		// bind value by name
		oci_bind_by_name($stmt, ':id', $this->id);

		// Make request
		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	// used when filling out the category update form
	public function readOne()
	{

		// request for reading one record (product)
		$query = 'SELECT * FROM ' . $this->table_name . ' WHERE id = :id';

		// Preparing request
		$stmt = oci_parse($this->conn, $query);

		//  Select Product id
		oci_bind_by_name($stmt, ':id', $this->id);

		// Making request
		oci_execute($stmt);

		// Getting row from request
		$row = oci_fetch_assoc($stmt);

		// Set product data
		$this->title = $row['TITLE'];
		$this->keywords = $row['KEYWORDS'];
		$this->description = $row['DESCRIPTION'];
		$this->created_at = $row['CREATED_AT'];
		$this->updated_at = $row['UPDATED_AT'];
	}

	private function clean()
	{
		// cleaning
		$this->title = htmlspecialchars(strip_tags($this->title));
		$this->keywords = htmlspecialchars(strip_tags($this->keywords));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->created_at = htmlspecialchars(strip_tags($this->created_at));
		$this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
	}

	private function bindByNames($stmt)
	{
		// set values
		oci_bind_by_name($stmt, ':title', $this->title);
		oci_bind_by_name($stmt, ':keywords', $this->keywords);
		oci_bind_by_name($stmt, ':description', $this->description);
		oci_bind_by_name($stmt, ':created_at', $this->created_at);
	}
}
