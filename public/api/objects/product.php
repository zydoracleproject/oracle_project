<?php

class Product
{
	private $conn;
	private $table_name = 'products';

	// all columns on products table
	public $id, $title, $content, $model,
		$price, $status, $pop_status, $amount,
		$keywords, $description, $manufacturer_id, $category_id, $alias, $created_at, $updated_at;

	//Accepts db connection
	public function __construct($db)
	{
		$this->conn = $db;
	}

	// Method read() - get products from database
	public function read()
	{
		// Choose all records
		$query = 'SELECT c.title as category_title, m.title as manufacturer_title, p.id, p.title,
 							p.model, p.price, p.status, p.pop_status, p.amount,
							p.keywords, p.description, p.manufacturer_id,
							p.category_id, p.alias, p.created_at, p.updated_at
							FROM ' . $this->table_name . ' p
							LEFT JOIN categories c ON (p.category_id = c.id)
							LEFT JOIN manufacturers m ON (p.manufacturer_id = m.id)
							ORDER BY p.created_at DESC';

		// Make request
		$stmt = oci_parse($this->conn, $query);

		// execute request
		oci_execute($stmt);

		return $stmt;
	}

	// method create() - creating product
	public function create()
	{

		// request for inserting records
		$query = 'INSERT INTO ' . $this->table_name . " 
							(title, content, model, price, status, pop_status,
							amount, keywords, description, manufacturer_id, category_id, alias, created_at) 
							VALUES (:title, :content, :model, :price, :status, :pop_status, :amount,
											:keywords, :description, :manufacturer_id, :category_id,
											:alias, TO_TIMESTAMP(TO_DATE(:created_at, 'MM/DD/YYYY HH24:MI:SS')))";

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

	// used when filling out the product update form
	public function readOne()
	{

		// request for reading one record (product)
		$query = 'SELECT c.title as category_title, m.title as manufacturer_title, p.id, p.title, p.content, p.model,
							p.price, p.status, p.pop_status, p.amount, p.keywords, p.description,
							p.manufacturer_id, p.category_id, p.alias, p.created_at, p.updated_at
							FROM ' . $this->table_name . ' p 
							LEFT JOIN categories c ON p.category_id = c.id
							LEFT JOIN manufacturers m ON (p.manufacturer_id = m.id)
							WHERE p.id = :id';

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
		$this->content = $row['CONTENT'];
		$this->model = $row['MODEL'];
		$this->price = $row['PRICE'];
		$this->status = $row['STATUS'];
		$this->pop_status = $row['POP_STATUS'];
		$this->amount = $row['AMOUNT'];
		$this->keywords = $row['KEYWORDS'];
		$this->description = $row['DESCRIPTION'];
		$this->manufacturer_id = $row['MANUFACTURER_ID'];
		$this->category_id = $row['CATEGORY_ID'];
		$this->alias = $row['ALIAS'];
		$this->created_at = $row['CREATED_AT'];
		$this->updated_at = $row['UPDATED_AT'];
	}

	// method update() - updating product
	public function update()
	{
		// query for updating record (product)
		$query = 'UPDATE ' . $this->table_name . "
							SET 
									title = :title,
									content = :content,
									model = :model,
									price = :price,
									status = :status,
									pop_status = :pop_status,
									amount = :amount,
									keywords = :keywords,
									description = :description,
									manufacturer_id = :manufacturer_id,
									category_id = :category_id,
									alias = :alias,
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

	// method delete() - deleting product
	public function delete()
	{
		// query for deleting record (product)
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

	// method search() - search products
	public function search($keywords)
	{
		// select from all records
		$query = 'SELECT c.title as category_title, m.title as manufacturer_title, p.id, p.title, p.content, p.model,
							p.price, p.status, p.pop_status, p.amount, p.keywords, p.description,
							p.manufacturer_id, p.category_id, p.alias, p.created_at, p.updated_at
							FROM ' . $this->table_name . ' p 
							LEFT JOIN categories c ON p.category_id = c.id
							LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
							WHERE p.title LIKE :keywords OR c.title LIKE :keywords OR m.title LIKE :keywords';

		// preparing query
		$stmt = oci_parse($this->conn, $query);

		// Cleaning
		$keywords = htmlspecialchars(strip_tags($keywords));
		$keywords = '%' . $keywords . '%';

		// Binding
		oci_bind_by_name($stmt, ':keywords', $keywords);

		// Request query
		oci_execute($stmt);

		return $stmt;
	}

	// method readPaging - reading products with paging
	public function readPaging($from_record_num, $records_per_page) {

		// Selecting
		$query = 'SELECT c.title as category_title, m.title as manufacturer_title, p.id, p.title,
 							p.model, p.price, p.status, p.pop_status, p.amount,
							p.keywords, p.description, p.manufacturer_id,
							p.category_id, p.alias, p.created_at, p.updated_at
							FROM ' . $this->table_name . ' p
							LEFT JOIN categories c ON (p.category_id = c.id)
							LEFT JOIN manufacturers m ON (p.manufacturer_id = m.id)
							WHERE rownum BETWEEN :f AND :t
							ORDER BY p.created_at DESC';

		// Preparing query
		$stmt = oci_parse($this->conn, $query);

		// Binding
		oci_bind_by_name($stmt, ':f', $from_record_num);
		oci_bind_by_name($stmt, ':t', $records_per_page);

		// Make request
		oci_execute($stmt);

		// Returns data from db
		return $stmt;
	}

	// Calculating for paging products
	public function count() {
		$query = 'SELECT COUNT(*) as total_rows FROM ' . $this->table_name;

		$stmt = oci_parse($this->conn, $query);

		oci_execute($stmt);

		$row = oci_fetch_assoc($stmt);

		return $row['TOTAL_ROWS'];
	}

	// method search() - read products by manufacturers
	public function readByMan($id)
	{
		// select from all records
		$query = 'SELECT c.title as category_title, m.title as manufacturer_title, p.id, p.title, p.content, p.model,
							p.price, p.status, p.pop_status, p.amount, p.keywords, p.description,
							p.manufacturer_id, p.category_id, p.alias, p.created_at, p.updated_at
							FROM ' . $this->table_name . ' p 
							LEFT JOIN categories c ON p.category_id = c.id
							LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
							WHERE p.manufacturer_id = :id';

		// preparing query
		$stmt = oci_parse($this->conn, $query);

		// Cleaning
		$id = htmlspecialchars(strip_tags($id));

		// Binding
		oci_bind_by_name($stmt, ':id', $id);

		// Request query
		oci_execute($stmt);

		return $stmt;
	}

	// method readByCat() - read products by categories
	public function readByCat($id)
	{
		// select from all records
		$query = 'SELECT c.title as category_title, m.title as manufacturer_title, p.id, p.title, p.content, p.model,
							p.price, p.status, p.pop_status, p.amount, p.keywords, p.description,
							p.manufacturer_id, p.category_id, p.alias, p.created_at, p.updated_at
							FROM ' . $this->table_name . ' p 
							LEFT JOIN categories c ON p.category_id = c.id
							LEFT JOIN manufacturers m ON p.manufacturer_id = m.id
							WHERE p.category_id = :id';

		// preparing query
		$stmt = oci_parse($this->conn, $query);

		// Cleaning
		$id = htmlspecialchars(strip_tags($id));

		// Binding
		oci_bind_by_name($stmt, ':id', $id);

		// Request query
		oci_execute($stmt);

		return $stmt;
	}

	private function clean()
	{
		// cleaning
		$this->title = htmlspecialchars(strip_tags($this->title));
		$this->content = htmlspecialchars(strip_tags($this->content));
		$this->model = htmlspecialchars(strip_tags($this->model));
		$this->price = htmlspecialchars(strip_tags($this->price));
		$this->status = htmlspecialchars(strip_tags($this->status));
		$this->pop_status = htmlspecialchars(strip_tags($this->pop_status));
		$this->amount = htmlspecialchars(strip_tags($this->amount));
		$this->keywords = htmlspecialchars(strip_tags($this->keywords));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->manufacturer_id = htmlspecialchars(strip_tags($this->manufacturer_id));
		$this->category_id = htmlspecialchars(strip_tags($this->category_id));
		$this->alias = htmlspecialchars(strip_tags($this->alias));
		$this->created_at = htmlspecialchars(strip_tags($this->created_at));
		$this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
	}

	private function bindByNames($stmt)
	{
		// set values
		oci_bind_by_name($stmt, ':title', $this->title);
		oci_bind_by_name($stmt, ':content', $this->content);
		oci_bind_by_name($stmt, ':model', $this->model);
		oci_bind_by_name($stmt, ':price', $this->price);
		oci_bind_by_name($stmt, ':status', $this->status);
		oci_bind_by_name($stmt, ':pop_status', $this->pop_status);
		oci_bind_by_name($stmt, ':amount', $this->amount);
		oci_bind_by_name($stmt, ':keywords', $this->keywords);
		oci_bind_by_name($stmt, ':description', $this->description);
		oci_bind_by_name($stmt, ':manufacturer_id', $this->manufacturer_id);
		oci_bind_by_name($stmt, ':category_id', $this->category_id);
		oci_bind_by_name($stmt, ':alias', $this->alias);
		oci_bind_by_name($stmt, ':created_at', $this->created_at);
	}
}
