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

// function dispatchRoute($route)
// {
//   try {
//     $handler = $route['handler'];
//     $middlewares = $route['middleware'] ?? [];
//     $params = $route['params'] ?? [];

//     // Jalankan middleware
//     foreach ($middlewares as $mw) {
//       runMiddleware($mw);
//     }

//     // Resolve closure param
//     $params = array_map(function ($p) {
//       return is_callable($p) ? $p() : $p;
//     }, $params);

//     // Eksekusi handler
//     if (is_callable($handler)) {
//       return call_user_func_array($handler, $params);
//     }

//     throw new Exception("Invalid handler");
//   } catch (Exception $e) {
//     http_response_code(500);
//     echo "Internal Server Error: " . $e->getMessage();
//   }
// }

// function runMiddleware($name)
// {
//   switch ($name) {
//     case 'auth':
//       requireAuth();
//       break;
//     case 'guest':
//       requireGuest();
//       break;
//     default:
//       throw new Exception("Unknown middleware: $name");
//   }
// }

// function requireGuest()
// {
//   if (isset($_SESSION['user_id'])) {
//     header('Location: /nutritrack/profile');
//     exit();
//   }
// }

// // Simple auth middleware
// function requireAuth()
// {
//   if (!isset($_SESSION['user_id'])) {
//     header('Location: /nutritrack/login');
//     exit();
//   }
// }

// Routing table
$routes = [
  'GET' => [
    'nutritrack' => [
      'handler' => [$homeController, 'index'],
    ],
    'nutritrack/home' => [
      'handler' => fn() => header('Location: /nutritrack'),
    ],
    'nutritrack/login' => [
      'handler' => [$authController, 'login'],
      'middleware' => ['guest'],
    ],
    'nutritrack/register' => [
      'handler' => [$authController, 'register'],
      'middleware' => ['guest'],
    ],
    'nutritrack/profile' => [
      'handler' => [$profileController, 'dashboard'],
      'middleware' => ['auth']
    ],
    'nutritrack/profile/dashboard' => [
      'handler' => fn() => header('Location: /nutritrack/profile'),
    ],
    'nutritrack/profile/edit' => [
      'handler' => [$profileController, 'editProfile'],
      'middleware' => ['auth'],
      'params' => [fn() => $_SESSION['user_id']],
    ],
    'nutritrack/profile/data' => [
      'handler' => [$profileController, 'viewData'],
      'middleware' => ['auth'],
      'params' => [fn() => $_SESSION['user_id']],
    ],
    'nutritrack/profile/personal' => [
      'handler' => [$profileController, 'profilePersonal'],
      'middleware' => ['auth'],
      'params' => [fn() => $_SESSION['user_id']],
    ],
    'nutritrack/search' => [
      'handler' => fn() => $homeController->search(
        isset($_GET['search']) ? $_GET['search'] : '',
        isset($_GET['page']) ? (int)$_GET['page'] : 1
      ),
    ],
    'nutritrack/details' => [
      'handler' => fn() => $foodController->foodDetail(
        isset($_GET['id']) ? (int)$_GET['id'] : -1
      ),
    ],
    'nutritrack/profile/tracking' => [
      'handler' => [$profileController, 'profileTracking'],
      'middleware' => ['auth'],
    ],
    'nutritrack/profile/tambah-makanan' => [
      'handler' => [$profileController, 'tambahMakanan'],
      'middleware' => ['auth'],
    ],
    'nutritrack/profile/logout' => [
      'handler' => function () {
        session_destroy();
        header("Location: /nutritrack");
        exit();
      },
    ],
  ],

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
} else {
  http_response_code(404);
  require 'views/404.php';
}
