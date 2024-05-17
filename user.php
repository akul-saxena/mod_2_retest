<?php

/**
 * @file
 * User dashboard for managing teams and players.
 */

session_start();

// Include necessary classes
include_once 'includes/Database.php';
include_once 'includes/Player.php';
include_once 'includes/Team.php';

// Redirect if not logged in as user
if ($_SESSION['role'] != 'user') {
  header("Location: login.php");
  exit();
}

// Database connection
$database = new Database(); // Instantiate Database object
$db = $database->getConnection(); // Get database connection

// Initialize Player and Team objects
$player = new Player($db); // Instantiate Player object
$team = new Team($db); // Instantiate Team object
$team->user_id = $_SESSION['user_id']; // Set user ID for team

// Handle form submission to save team
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_team'])) {
  $selected_players = $_POST['players'];

  // Clear previous team
  $team->deleteUserTeam();

  // Save new team
  foreach ($selected_players as $player_id) {
    $team->player_id = $player_id;
    $team->save();
  }
}

// Handling delete team action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_team'])) {
  $team->deleteUserTeam();
}

// Retrieve current user team
$user_team = $team->getUserTeam(); // Fetch user's current team
$players = $player->read(); // Fetch all available players

?>

<!DOCTYPE html>
<html>

<head>
  <title>Innoraft Premier League - User</title>
  <link rel="stylesheet" type="text/css" href="/stylesheets/userStyle.css">
  <script src="js/script.js"></script>
</head>

<body class="container">
  <header>
    <h2>User Dashboard</h2>
    <a href="logout.php">Logout</a>
  </header>

  <?php if ($user_team->num_rows > 0) : ?>
    <!-- Display user's current team -->
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
    <form method="post">
      <input type="submit" name="edit_team" value="Edit Team">
      <input type="submit" name="delete_team" value="Delete Team">
    </form>
  <?php endif; ?>

  <?php if (!isset($_POST['edit_team']) && $user_team->num_rows == 0) : ?>
    <!-- Form to select a new team if user has no current team -->
    <h2>Select Your Team</h2>
    <form method="post" id="teamForm" onsubmit="return checkPlayerTypeLimits()">
      <table>
        <tr>
          <th>Select</th>
          <th>Employee ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Points</th>
        </tr>
        <?php while ($row = $players->fetch_assoc()) : ?>
          <tr>
            <td><input type="checkbox" name="players[]" value="<?php echo $row['id']; ?>" data-points="<?php echo $row['points']; ?>" data-type="<?php echo $row['type']; ?>"></td>
            <td><?php echo $row['employee_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo $row['points']; ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
      <p>Total Points: <span id="totalPoints">0</span>/100</p>
      <p>Batsmen selected: <span id="batsmenCount">0</span>/5</p>
      <p>Allrounders selected: <span id="allroundersCount">0</span>/2</p>
      <p>Bowlers selected: <span id="bowlersCount">0</span>/4</p>
      <input type="submit" name="save_team" value="Save Team" id="submitButton" disabled>
    </form>
  <?php endif; ?>

  <?php if (isset($_POST['edit_team'])) : ?>
    <!-- Form to edit user's current team -->
    <h2>Edit Your Team</h2>
    <form method="post" id="teamForm" onsubmit="return checkPlayerTypeLimits()">
      <table>
        <tr>
          <th>Select</th>
          <th>Employee ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Points</th>
        </tr>
        <?php $players->data_seek(0); ?>
        <?php while ($row = $players->fetch_assoc()) : ?>
          <tr>
            <td><input type="checkbox" name="players[]" value="<?php echo $row['id']; ?>" data-points="<?php echo $row['points']; ?>" data-type="<?php echo $row['type']; ?>" <?php echo in_array($row['id'], array_column($user_team->fetch_all(MYSQLI_ASSOC), 'player_id')) ? 'checked' : ''; ?>>
            </td>
            <td><?php echo $row['employee_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo $row['points']; ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
      <p>Total Points: <span id="totalPoints">0</span>/100</p>
      <p>Batsmen selected: <span id="batsmenCount">0</span>/5</p>
      <p>Allrounders selected: <span id="allroundersCount">0</span>/2</p>
      <p>Bowlers selected: <span id="bowlersCount">0</span>/4</p>
      <input type="submit" name="save_team" value="Save Team" id="submitButton" disabled>
    </form>
  <?php endif; ?>

</body>

</html>