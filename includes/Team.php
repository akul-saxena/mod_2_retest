<?php

/**
 * Defines a Team class for managing user teams.
 */
class Team
{
  private $conn;
  private $table_name = "teams";
  public $user_id;
  public $player_id;

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
   * Saves a user's selected player to the team in the database.
   *
   * @return bool
   *   True if the operation was successful, false otherwise.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function save()
  {
    $query = "INSERT INTO " . $this->table_name . " SET user_id=?, player_id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $this->user_id, $this->player_id);

    return $stmt->execute();
  }

  /**
   * Retrieves the user's team details from the database.
   *
   * @return mysqli_result
   *   The result set containing the player details of the user's team.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function getUserTeam()
  {
    $query = "SELECT p.employee_id, p.name, p.type, p.points FROM players p JOIN teams t ON p.id = t.player_id WHERE t.user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $this->user_id);
    $stmt->execute();
    return $stmt->get_result();
  }

  /**
   * Deletes all players associated with the user's team from the database.
   *
   * @return bool
   *   True if the operation was successful, false otherwise.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function deleteUserTeam()
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $this->user_id);
    return $stmt->execute();
  }
}
