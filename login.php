<?php

/**
 * @file
 * Login file for IPL.
 */

session_start();
include_once 'includes/Database.php';
include_once 'includes/User.php';

/**
 * Class LoginPage
 * Handles user login functionality.
 */
class LoginPage
{

  private $db;

  /**
   * Construct a new LoginPage object.
   *
   * @param mysqli $db
   *   The mysqli database connection.
   */
  public function __construct(mysqli $db)
  {
    $this->db = $db;
  }

  /**
   * Function to login.
   */
  public function processLogin()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $user = new User($this->db);
      $user->email = $_POST['email'];
      $user->password = $_POST['password'];

      if ($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['role'] = $user->role;

        $this->redirectUser($user->role);
      } else {
        echo "<script>alert('Incorrect Username or Password');</script>";
      }
    }
  }

  /**
   * Redirects user based on role after successful login.
   *
   * @param string $role
   *   The role of the logged-in user.
   */
  private function redirectUser($role)
  {
    if ($role == 'admin') {
      header("Location: admin");
    } else {
      header("Location: user");
    }
    exit();
  }
}

// Initialize session and include necessary files
session_start();
include_once 'includes/Database.php';
include_once 'includes/User.php';

// Initialize Database connection
$database = new Database();
$db = $database->getConnection();

// Initialize LoginPage and process login attempt
$LoginPage = new LoginPage($db);
$LoginPage->processLogin();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Innoraft Premier League - Login</title>
  <link rel="stylesheet" type="text/css" href="/stylesheets/loginStyle.css">
</head>

<body>
  <div class="container">
    <h2>Login</h2>
    <form method="post">
      <label>Email:</label>
      <input type="email" name="email" required><br>
      <label>Password:</label>
      <input type="password" name="password" required><br>
      <input type="submit" value="Login">
    </form>
  </div>
</body>

</html>