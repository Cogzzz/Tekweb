<?php

/**
 * Class User
 */
class User
{
	private $row = null;

	public function __construct($username = null)
	{
		$conn = User::get_db_connection();
		$query = "";
		if ($username) {
			$query = "SELECT * FROM User WHERE username = " . $username;
            //SELECT * FROM User ORDER BY username ASC/DESC
		}

		if ($query != "") {
			$stmt = $conn->query($query);
			$this->row = $stmt->fetch();
		}
	}


	public function update($arr = ['username' => null, 'password' => null, 'status' => null])
	{
		$conn = User::get_db_connection();
		$q = "UPDATE User SET username = '" . $arr['username'] .
			"', password = '" . $arr['password'] .
			"', status = " . $arr['status'] .
			" WHERE username = " . $this->row['username'] . ";";
		$res = $conn->exec($q);
		return $res;
	}

	public function delete()
	{
		$q = "DELETE FROM User WHERE username = " . $this->row['username'] . ";";
		$res = User::get_db_connection()->exec($q);
		return $res;
	}

	public function get_row()
	{
		return $this->row;
	}

	/**
	 * Static function sama sperti java static method
	 * Get all Users
	 */
	public static function get_all()
	{
		$conn = User::get_db_connection();
		$query = "SELECT * FROM User";
		$stmt = $conn->query($query);
		return $stmt->fetchAll();
	}

    public static function search_by($username = null){
        $conn = User::get_db_connection();
        $query = "SELECT * FROM User where username = '{$username}'";
        $result = $conn->query($query);
        return $result->fetch();
    }

    public static function check_User($username = null){
        $conn = User::get_db_connection();
        $query = "SELECT * FROM user where username = '{$username}'";
        $result = $conn->query($query);
        return $result->rowCount();
    }

    public static function check_uname($username = null){
        $conn = User::get_db_connection();
        $query = "SELECT `username` FROM User where username = '{$username}'";
        $result = $conn->query($query);
        return $result->fetch();
    }

    public static function check_pass($password = null){
        $conn = User::get_db_connection();
        $query = "SELECT `password` FROM User where password = '{$password}'";
        $result = $conn->query($query);
        return $result->fetch();
    }

	public static function getId($username)
    {
        $conn = User::get_db_connection();
        $query = "SELECT id FROM User WHERE username = :username";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

	/**
	 * Create new User
	 */
	public static function create_User($arr = ['name' => null, 'username' => null, 'alamat' => null, 'notelp' => null, 'password' => null])
	{
        $conn = User::get_db_connection();
        $sql = "INSERT INTO User (`name`,`username`, `alamat`, `notelp`, `password`) VALUES (:name, :username, :alamat, :notelp, :password)";
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $arr['name']);
        $stmt->bindParam(':username', $arr['username']);
        $stmt->bindParam(':alamat', $arr['alamat']);
        $stmt->bindParam(':notelp', $arr['notelp']);
        $stmt->bindParam(':password', $arr['password']);
        
        // Execute the prepared statement
        $stmt->execute();
		return $stmt;
	}


	/**
	 * Protected method pd php
	 * Karena loosen-type return-type method deklarasai sama dengan vousername-type method
	 */
	protected static function get_db_connection()
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "blingo";
		$conn = null;

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			die;
		}
	}
}
