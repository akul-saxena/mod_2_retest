<?php

/**
 * @file
 * Admin dashboard for managing players in IPL.
 */

session_start();
include_once 'includes/Database.php';
include_once 'includes/Admin.php';

/**
 * AdminDashboard class handles player management by admin users.
 */
class AdminDashboard
{

  private $db;

  private $admin;

  /**
   * Constructs a new AdminDashboard object.
   *
   * @param mysqli $db
   *   The mysqli database connection.
   */
  public function __construct(mysqli $db)
  {
    $this->db = $db;
    $this->admin = new Admin($db);
  }

  /**
   * Adds a new player based on form submission.
   *
   * @param string $employee_id
   *   The employee ID of the player.
   * @param string $name
   *   The name of the player.
   * @param string $type
   *   The type of player.
   * @param int $points
   *   The points assigned to the player (between 2 and 10).
   *
   * @return bool
   *   True if player is successfully added, otherwise false.
   */
  public function addPlayer($employee_id, $name, $type, $points)
  {
    if ($this->admin->addPlayer($employee_id, $name, $type, $points)) {
      return true;
    } else {
      return false;
    }
  }
}

// Redirect if user is not an admin
if ($_SESSION['role'] != 'admin') {
  header("Location: login");
  exit();
}

// Initialize Database connection
$database = new Database();
$db = $database->getConnection();

// Initialize AdminDashboard
$adminDashboard = new AdminDashboard($db);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $employee_id = $_POST['employee_id'];
  $name = $_POST['name'];
  $type = $_POST['type'];
  $points = $_POST['points'];

  if ($adminDashboard->addPlayer($employee_id, $name, $type, $points)) {
    echo "<script>alert('Player added Successfully');</script>";
  } else {
    echo "<script>alert('Failed to Add Player');</script>";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Innoraft Premier League - Admin</title>
  <link rel="stylesheet" type="text/css" href="/stylesheets/adminStyle.css">
</head>

<body>
  <div class="container">
    <header>
      <h2>Admin Dashboard</h2>
      <a href="logout.php">Logout</a>
    </header>
    <br>
    <h2>Add Player</h2>
    <form method="post">
      <label>Employee ID:</label>
      <input type="text" name="employee_id" required><br>
      <label>Name:</label>
      <input type="text" name="name" required><br>
      <label>Type:</label>
      <select name="type" required>
        <option value="batsman">Batsman</option>
        <option value="bowler">Bowler</option>
        <option value="allrounder">Allrounder</option>
      </select><br>
      <label>Points:</label>
      <input type="number" name="points" min="2" max="10" required><br>
      <input type="submit" value="Add Player">
    </form>
  </div>
</body>

</html>