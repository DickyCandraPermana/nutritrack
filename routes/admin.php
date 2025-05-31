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
    'nutritrack/home' => [
      'handler' => fn() => header('Location: /nutritrack'),
    ],
    'nutritrack/logout' => [
      'handler' => [$authController, 'logout'],
    ]
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
