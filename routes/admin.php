<?php

use Controllers\Web\AuthController;
use Controllers\Web\ProfileController;
use Controllers\Web\HomeController;
use Controllers\Web\FoodController;
use Controllers\Web\AdminController;

require_once 'config/koneksi.php';
require_once 'Middlewares/middleware.php';

require_once 'controllers/Web/AuthController.php';
require_once 'controllers/Web/ProfileController.php';
require_once 'controllers/Web/HomeController.php';
require_once 'controllers/Web/FoodController.php';
require_once 'controllers/Web/AdminController.php';

$authController = new AuthController($db);
$homeController = new HomeController($db);
$profileController = new ProfileController($db);
$foodController = new FoodController($db);
$adminController = new AdminController($db);

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

// Routing table
$routes = [
  'GET' => [
    'nutritrack/admin' => [
      'handler' => [$adminController, 'index'],
    ],
    'nutritrack/admin/dashboard' => [
      'handler' => fn() => header('Location: ' . BASE_URL . 'admin'),
    ],
    'nutritrack/admin/users' => [
      'handler' => [$adminController, 'usersPage'],
    ],
    'nutritrack/admin/tambah-user' => [
      'handler' => [$adminController, 'usersAddPage'],
    ],
    'nutritrack/admin/update-user' => [
      'handler' => [$adminController, 'usersEditPage'],
    ],
    'nutritrack/admin/foods' => [
      'handler' => [$adminController, 'foodsPage'],
    ],
    'nutritrack/admin/tambah-food' => [
      'handler' => [$adminController, 'foodsAddPage'],
    ],
    'nutritrack/admin/update-food' => [
      'handler' => [$adminController, 'foodsEditPage'],
    ],
    'nutritrack/admin/logout' => [
      'handler' => [$authController, 'logout'],
    ]
  ]
];

// Main route dispatcher
if (isset($routes[$method][$uri])) {
  $route = $routes[$method][$uri];
  $routeResult = dispatchRoute($route);

  if ($routeResult && is_array($routeResult) && isset($routeResult['view'])) {
    renderLayout($routeResult['view'], $routeResult['data'] ?? []);
  }
} else {
  http_response_code(404);
  renderLayout('404');
}
