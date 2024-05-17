<?php

/**
 * @file
 * Router script to handle URI routing for IPL.
 */

$requestUri = $_SERVER['REQUEST_URI'];

// Define routes and corresponding PHP files - Clean URL's
$routes = [
  '/' => '/login.php',
  '/user' => '/user.php',
  '/admin' => '/admin.php'
];

/**
 * Class Router
 * Handles routing logic for including PHP files based on requested URI.
 */
class Router
{

  private $routes;

  /**
   * Construct a new Router object.
   *
   * @param array $routes
   *   Array of routes and corresponding PHP files.
   */
  public function __construct(array $routes)
  {
    $this->routes = $routes;
  }

  /**
   * Includes the PHP file based on requested URI.
   *
   * @param string $requestUri
   *   The requested URI.
   */
  public function route($requestUri)
  {
    if (array_key_exists($requestUri, $this->routes)) {
      // Get the PHP file for the route
      $targetPhpFile = $this->routes[$requestUri];

      // Include the PHP file
      include_once(__DIR__ . $targetPhpFile);
    } else {
      // Route not found
      $this->handleNotFound();
    }
  }

  private function handleNotFound()
  {
    // Output 404 error
    echo '<h1>404 - Not Found</h1>';
  }
}

// Initialize Router
$router = new Router($routes);

// Route the request
$router->route($requestUri);
