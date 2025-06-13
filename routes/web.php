<?php

use Controllers\Web\AuthController;
use Controllers\Web\ProfileController;
use Controllers\Web\HomeController;
use Controllers\Web\FoodController;

require_once 'config/koneksi.php';
require_once 'Middlewares/middleware.php';

require_once 'controllers/Web/AuthController.php';
require_once 'controllers/Web/ProfileController.php';
require_once 'controllers/Web/HomeController.php';
require_once 'controllers/Web/FoodController.php';

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
    'nutritrack/profile/personal' => [
      'handler' => [$profileController, 'profilePersonal'],
      'middleware' => ['auth'],
      'params' => [fn() => $_SESSION['user_id']],
    ],
    'nutritrack/search' => [
      'handler' => fn() => $homeController->search(
        $_GET['q'] ?? '',
        $_GET['page'] ?? 1
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
    'nutritrack/premium' => [
      'handler' => [$homeController, 'premiumPage'],
    ],
    'nutritrack/profile/logout' => [
      'handler' => [$authController, 'logout'],
    ],
  ],
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
