<?php

include_once 'Database.php';

/**
 * Defines a User class for database operations.
 */
class User
{

  public $conn;
  public $table_name = "users";
  public $id;
  public $email;
  public $password;
  public $role;

  /**
   * Constructs a new User object.
   *
   * @param mysqli $db
   *   The database connection object.
   */
  public function __construct($db)
  {
    $this->conn = $db;
  }

  /**
   * Creates a new user record in the database.
   *
   * @return bool
   *   TRUE if the user was successfully created, FALSE otherwise.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function create()
  {
    $query = "INSERT INTO " . $this->table_name . " (email, password, role) VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($query);

    $stmt->bind_param("sss", $this->email, $this->password, $this->role);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }

  /**
   * Validates user credentials and logs the user in.
   *
   * @return bool
   *   TRUE if the login was successful, FALSE otherwise.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function login()
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? AND password = ?";

    $stmt = $this->conn->prepare($query);

    $stmt->bind_param("ss", $this->email, $this->password);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $this->id = $row['id'];
      $this->role = $row['role'];
      return true;
    }

    return false;
  }
}
