<?php

class Admin
{

	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function getAccounts()
	{
		$query = 'SELECT id, username, granted_role AS ROLE, created_at
							FROM admin_users JOIN dba_role_privs ON (grantee = UPPER(username))
							ORDER BY created_at DESC';
		$stmt = oci_parse($this->conn, $query);
		oci_execute($stmt);
		return $stmt;
	}

	public function getAccount($username)
	{
		$query = 'SELECT id, username, granted_role AS ROLE, created_at
							FROM admin_users JOIN dba_role_privs ON (grantee = UPPER(username))
							WHERE username = UPPER(:username)
							ORDER BY created_at DESC';
		$stmt = oci_parse($this->conn, $query);

		$username = htmlspecialchars(strip_tags($username));
		oci_bind_by_name($stmt, ':username', $username);

		oci_execute($stmt);

		return $stmt;
	}

	public function updateAccount($username, $password, $role)
	{
		$username = htmlspecialchars(strip_tags($username));
		$role = htmlspecialchars(strip_tags(strtoupper($role)));
		$password = htmlspecialchars(strip_tags($password));

		$granted_role = null;

		$query = 'SELECT granted_role FROM dba_role_privs
							WHERE grantee = UPPER(:username)';
		$stmt = oci_parse($this->conn, $query);
		oci_bind_by_name($stmt, ':username', $username);

		oci_define_by_name($stmt, 'GRANTED_ROLE', $granted_role);

		if (oci_execute($stmt)) {
			oci_fetch($stmt);
			$query = "ALTER USER $username IDENTIFIED BY $password";
			$stmt = oci_parse($this->conn, $query);
			if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
				$query = "REVOKE $granted_role FROM $username";
				$stmt = oci_parse($this->conn, $query);
				if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
					$query = "GRANT $role TO $username";
					$stmt = oci_parse($this->conn, $query);
					if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
						oci_commit($this->conn);
						return true;
					}
				}
			}
			oci_rollback($this->conn);
		}

		return false;
	}

	public function createAccount($username, $password, $role)
	{
		$username = htmlspecialchars(strip_tags($username));
		$role = htmlspecialchars(strip_tags(strtoupper($role)));
		$password = htmlspecialchars(strip_tags($password));

		$query = "CREATE USER $username IDENTIFIED BY $password";
		$stmt = oci_parse($this->conn, $query);
		if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
			$query = "GRANT $role TO $username";
			$stmt = oci_parse($this->conn, $query);

			if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
				$query = 'INSERT INTO admin_users
									SELECT user_id AS ID, username, created AS CREATED_AT
									FROM dba_users WHERE username = UPPER(:username)';
				$stmt = oci_parse($this->conn, $query);
				oci_bind_by_name($stmt, ':username', $username);
				if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
					$conn = oci_new_connect($username, $password, 'localhost:1521/XE');
					if ($conn) {
						$query = "BEGIN
										EXECUTE IMMEDIATE 'CREATE SYNONYM products FOR admin.products';
										EXECUTE IMMEDIATE 'CREATE SYNONYM categories FOR admin.categories';
										EXECUTE IMMEDIATE 'CREATE SYNONYM manufacturers FOR admin.manufacturers';
										EXECUTE IMMEDIATE 'CREATE SYNONYM options FOR admin.options';
										EXECUTE IMMEDIATE 'CREATE SYNONYM images FOR admin.images';
										EXECUTE IMMEDIATE 'CREATE SYNONYM users FOR admin.users';
										EXECUTE IMMEDIATE 'CREATE SYNONYM products_view FOR admin.products_view';
										EXECUTE IMMEDIATE 'CREATE SYNONYM admin_users FOR admin.admin_users';
										EXECUTE IMMEDIATE 'CREATE SYNONYM visits FOR admin.visits';
										EXECUTE IMMEDIATE 'CREATE SYNONYM ips FOR admin.ips';
										END;";
						$stmt = oci_parse($conn, $query);
						if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
							oci_commit($this->conn);
							oci_commit($conn);
							return true;
						}
					}
				}
			}
		}
		oci_rollback($this->conn);

		return false;
	}

	public function deleteAccount($username)
	{
		$username = htmlspecialchars(strip_tags($username));
		$stmt = oci_parse($this->conn, "DROP USER $username CASCADE");
		if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
			$stmt = oci_parse($this->conn, 'DELETE FROM admin_users WHERE username = UPPER(:username)');
			oci_bind_by_name($stmt, ':username', $username);
			if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
				oci_commit($this->conn);
				return true;
			}
		}

		oci_rollback($this->conn);
		return false;
	}

}
