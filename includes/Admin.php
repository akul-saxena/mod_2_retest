<?php

include_once 'User.php';

/**
 * Defines an Admin class extending User.
 */
class Admin extends User
{

  /**
   * Adds a new player to the database.
   *
   * @param string $employee_id
   *   The employee ID of the player.
   * @param string $name
   *   The name of the player.
   * @param string $type
   *   The type of player (e.g., batsman, bowler, allrounder).
   * @param int $points
   *   The points assigned to the player.
   *
   * @return bool
   *   TRUE if the player was successfully added, FALSE otherwise.
   *
   * @throws Exception
   *   If there was an error executing the database query.
   */
  public function addPlayer($employee_id, $name, $type, $points)
  {
    $query = "INSERT INTO players (employee_id, name, type, points) VALUES (?, ?, ?, ?)";

    $stmt = $this->conn->prepare($query);

    $stmt->bind_param("sssi", $employee_id, $name, $type, $points);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }
}
