<?php

class Product
{
	private $conn;
	private $table_name = 'products';

	// all columns on products table
	public $id, $title, $content, $model,
		$price, $status, $pop_status, $amount,
		$keywords, $description, $manufacturer_id, $category_id, $created_at, $updated_at;
	public $images = ['image_1' => '', 'image_2' => '', 'image_3' => ''];
	public $options = [
		'execution' => '',
		'appointment' => '',
		'power' => '',
		'premises' => '',
		'height' => '',
		'width' => '',
		'depth' => '',
		'chamber' => '',
		'warranty' => '',
	];

	//Accepts db connection
	public function __construct($db)
	{
		$this->conn = $db;
	}

	// Method read() - get products from database
	public function read()
	{
		// Choose all records
		$query = 'SELECT * FROM ' . $this->table_name . '_view
							ORDER BY created_at DESC';

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
		$query = 'DECLARE
							get_id NUMBER;
							BEGIN
							INSERT INTO ' . $this->table_name . " 
												(title, content, model, price, status, pop_status,
												amount, keywords, description, manufacturer_id, category_id, created_at) 
												VALUES (:title, :content, :model, :price, :status, :pop_status, :amount,
																:keywords, :description, :manufacturer_id, :category_id,
															TO_TIMESTAMP(:created_at, 'MM/DD/YYYY HH24:MI:SS')) RETURNING id INTO get_id;
							INSERT INTO images (product_id, image_1, image_2, image_3) VALUES (get_id, :image_1, :image_2, :image_3);
							INSERT INTO options (product_id, execution, appointment, power,
																	premises, height, width, depth, chamber, warranty)
													VALUES (get_id, :execution, :appointment, :power, :premises,
																	:height, :width, :depth, :chamber, :warranty);
							COMMIT;
							END;";

		$stmt = oci_parse($this->conn, $query);

		// Cleaning values
		$this->clean();

		// Bind values by names
		$this->bindByNames($stmt);
		$this->bindImages($stmt);
		$this->bindOptions($stmt);

		if (oci_execute($stmt)) {
			return true;
		}

		return false;
	}

	// used when filling out the product update form
	public function readOne()
	{

		// request for reading one record (product)
		$query = 'SELECT * FROM ' . $this->table_name .  '_view
							WHERE id = :id';

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
		$this->price = (int)str_replace(',', '', $row['PRICE']);
		$this->status = $row['STATUS'];
		$this->pop_status = $row['POP_STATUS'];
		$this->amount = $row['AMOUNT'];
		$this->keywords = $row['KEYWORDS'];
		$this->description = $row['DESCRIPTION'];
		$this->manufacturer_id = $row['MANUFACTURER_ID'];
		$this->category_id = $row['CATEGORY_ID'];
		$this->created_at = $row['CREATED_AT'];
		$this->updated_at = $row['UPDATED_AT'];
		$this->images['image_1'] = $row['IMAGE_1'];
		$this->images['image_2'] = $row['IMAGE_2'];
		$this->images['image_3'] = $row['IMAGE_3'];
		$this->options['execution'] = $row['EXECUTION'];
		$this->options['appointment'] = $row['APPOINTMENT'];
		$this->options['power'] = $row['POWER'];
		$this->options['premises'] = $row['PREMISES'];
		$this->options['height'] = $row['HEIGHT'];
		$this->options['width'] = $row['WIDTH'];
		$this->options['depth'] = $row['DEPTH'];
		$this->options['chamber'] = $row['CHAMBER'];
		$this->options['warranty'] = $row['WARRANTY'];
	}

	// method update() - updating product
	public function update()
	{
		// query for updating record (product)
		$query = 'BEGIN
							UPDATE ' . $this->table_name . " 
							SET title = :title,
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
									created_at = TO_TIMESTAMP(:created_at, 'DD Mon YYYY HH24:MI:SS'),
									updated_at = TO_TIMESTAMP(:updated_at, 'MM/DD/YYYY HH24:MI:SS')
							WHERE id = :id;
							UPDATE images 
							SET
								image_1 = :image_1,
								image_2 = :image_2,
								image_3 = :image_3
							WHERE product_id = :id;
							UPDATE options 
							SET
								execution = :execution,
								appointment = :appointment,
								power = :power,
								premises = :premises,
								height = :height,
								width = :width,
								depth = :depth,
								chamber = :chamber,
								warranty = :warranty
							WHERE product_id = :id;
							COMMIT;
							END;";
		// preparing query
		$stmt = oci_parse($this->conn, $query);

		// cleaning
		$this->clean();

		// Bind by names
		$this->bindByNames($stmt);
		$this->bindOptions($stmt);
		$this->bindImages($stmt);
		oci_bind_by_name($stmt, ':id', $this->id);
		oci_bind_by_name($stmt, ':updated_at', $this->updated_at);

		if (oci_execute($stmt)) {
			return true;
		}

		var_dump(oci_error($stmt));

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
		$query = 'SELECT * FROM ' . $this->table_name . '_view
							WHERE title LIKE :keywords OR category_title LIKE :keywords OR manufacturer_title LIKE :keywords';

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

	// method search() - read products by manufacturers
	public function readByMan($id)
	{
		// select from all records
		$query = 'SELECT * FROM ' . $this->table_name .'_view
							WHERE manufacturer_id = :id';

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
		$query = 'SELECT * FROM ' . $this->table_name .'_view
							WHERE category_id = :id';

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
		$this->created_at = htmlspecialchars(strip_tags($this->created_at));
		$this->updated_at = htmlspecialchars(strip_tags($this->updated_at));
		$this->images['image_1'] = htmlspecialchars(strip_tags($this->images['image_1']));
		$this->images['image_2'] = htmlspecialchars(strip_tags($this->images['image_2']));
		$this->images['image_3'] = htmlspecialchars(strip_tags($this->images['image_3']));
		$this->images['execution'] = htmlspecialchars(strip_tags($this->images['execution']));
		$this->images['appointment'] = htmlspecialchars(strip_tags($this->images['appointment']));
		$this->images['power'] = htmlspecialchars(strip_tags($this->images['power']));
		$this->images['premises'] = htmlspecialchars(strip_tags($this->images['premises']));
		$this->images['height'] = htmlspecialchars(strip_tags($this->images['height']));
		$this->images['width'] = htmlspecialchars(strip_tags($this->images['width']));
		$this->images['depth'] = htmlspecialchars(strip_tags($this->images['depth']));
		$this->images['chamber'] = htmlspecialchars(strip_tags($this->images['chamber']));
		$this->images['warranty'] = htmlspecialchars(strip_tags($this->images['warranty']));
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
		oci_bind_by_name($stmt, ':created_at', $this->created_at);
	}

	private function bindImages($stmt) {
		oci_bind_by_name($stmt, ':image_1', $this->images['image_1']);
		oci_bind_by_name($stmt, ':image_2', $this->images['image_2']);
		oci_bind_by_name($stmt, ':image_3', $this->images['image_3']);
	}

	private function bindOptions($stmt) {
		oci_bind_by_name($stmt, ':execution', $this->options['execution']);
		oci_bind_by_name($stmt, ':appointment', $this->options['appointment']);
		oci_bind_by_name($stmt, ':power', $this->options['power']);
		oci_bind_by_name($stmt, ':premises', $this->options['premises']);
		oci_bind_by_name($stmt, ':height', $this->options['height']);
		oci_bind_by_name($stmt, ':width', $this->options['width']);
		oci_bind_by_name($stmt, ':depth', $this->options['depth']);
		oci_bind_by_name($stmt, ':chamber', $this->options['chamber']);
		oci_bind_by_name($stmt, ':warranty', $this->options['warranty']);
	}
}
