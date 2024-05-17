<?php

/**
 * @file
 * Display user's team details for Innoraft Premier League.
 */

session_start();
include_once 'includes/Database.php';
include_once 'includes/Team.php';

/**
 * Class TeamHandler
 * Handles displaying user's team details.
 */
class TeamHandler
{
  private $db;

  /**
   * Construct a new TeamHandler object.
   *
   * @param mysqli $db
   *   The mysqli database connection.
   */
  public function __construct(mysqli $db)
  {
    $this->db = $db;
  }

  /**
   * Retrieves and returns user's team data from the database.
   *
   * @param int $userId
   *   The ID of the logged-in user.
   * 
   * @return mysqli_result
   *   Returns mysqli_result object containing user's team data on success, or false on failure.
   */
  public function getUserTeam($userId)
  {
    $team = new Team($this->db);
    $team->user_id = $userId;
    return $team->getUserTeam();
  }
}

// Drupal standard comment: Ensure user is logged in and include necessary files
if (!isset($_SESSION['user_id'])) {
  header("Location: login");
  exit();
}

// Initialize Database connection
$database = new Database();
$db = $database->getConnection();

// Initialize TeamHandler
$TeamHandler = new TeamHandler($db);

// Retrieve user's team data
$user_team = $TeamHandler->getUserTeam($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Innoraft Premier League - Your Team</title>
</head>

<body>
  <h2>Your Team</h2>
  <table>
    <tr>
      <th>Employee ID</th>
      <th>Name</th>
      <th>Type</th>
      <th>Points</th>
    </tr>
    <?php while ($row = $user_team->fetch_assoc()) : ?>
      <tr>
        <td><?php echo $row['employee_id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo ucfirst($row['type']); ?></td>
        <td><?php echo $row['points']; ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>

</html>