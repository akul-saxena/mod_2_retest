<?php

/**
 * Defines a Player class for managing player data.
 */
class Player
{
  private $conn;
  private $table_name = "players";
  public $id;
  public $employee_id;
  public $name;
  public $type;
  public $points;

  /**
   * Constructor to initialize the database connection.
   *
   * @param mysqli $db
   *   The database connection object.
   */
  public function __construct($db)
  {
    $this->conn = $db;
  }

  /**
   * Retrieves all players from the database.
   *
   * @return mysqli_result
   *   The result set containing all players.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function read()
  {
    $query = "SELECT * FROM " . $this->table_name;

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result;
  }
}
