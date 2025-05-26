<?php
session_start();
require_once 'config/koneksi.php';
require_once 'controllers/ProfileController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/FoodController.php';

$authController = new AuthController($db);
$homeController = new HomeController($db);
$profileController = new ProfileController($db);
$foodController = new FoodController($db);

// Ambil dan parse URL
$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
// Dapatkan path dari URL dan bersihkan dari slash awal/akhir
$uri = isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : '';
// Ambil metode HTTP (GET, POST, dll)
$method = $_SERVER['REQUEST_METHOD'];
$queryParams = [];
if (isset($parsedUrl['query'])) {
  parse_str($parsedUrl['query'], $queryParams); // Ubah jadi array asosiatif
}

function dispatchRoute($route)
{
  try {
    $handler = $route['handler'];
    $middlewares = $route['middleware'] ?? [];
    $params = $route['params'] ?? [];

    // Jalankan middleware
    foreach ($middlewares as $mw) {
      runMiddleware($mw);
    }

    // Resolve closure param
    $params = array_map(function ($p) {
      return is_callable($p) ? $p() : $p;
    }, $params);

    // Eksekusi handler
    if (is_callable($handler)) {
      return call_user_func_array($handler, $params);
    }

    throw new Exception("Invalid handler");
  } catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
  }
}

function runMiddleware($name)
{
  switch ($name) {
    case 'auth':
      requireAuth();
      break;
    case 'guest':
      requireGuest();
      break;
    default:
      throw new Exception("Unknown middleware: $name");
  }
}

function requireGuest()
{
  if (isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/profile');
    exit();
  }
}

// Simple auth middleware
function requireAuth()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/login');
    exit();
  }
}

// Routing table
$routes = [
  'POST' => [
    'nutritrack/login' => [
      'handler' => [$authController, 'login'],
      'params' => [$_POST],
    ],
    'nutritrack/register' => [
      'handler' => [$authController, 'register'],
      'params' => [$_POST],
    ],
    'nutritrack/profile/update' => [
      'handler' => [$profileController, 'editProfile'],
      'params' => [$_POST],
    ],
    'nutritrack/profile/tambah-makanan' => [
      'handler' => [$profileController, 'tambahMakanan'],
      'params' => [$_POST],
    ],
  ]
];

// Main route dispatcher
if (isset($routes[$method][$uri])) {
  $route = $routes[$method][$uri];
  dispatchRoute($route);
}
