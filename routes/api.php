<?php
session_start();

use Controllers\API\AuthController;
use Controllers\API\ProfileController;
use Controllers\API\HomeController;
use Controllers\API\FoodController;

require_once 'config/koneksi.php';

require_once 'controllers/API/AuthController.php';
require_once 'controllers/API/ProfileController.php';
require_once 'controllers/API/HomeController.php';
require_once 'controllers/API/FoodController.php';

$authController = new AuthController($db);
$homeController = new HomeController($db);
$profileController = new ProfileController($db);
$foodController = new FoodController($db);

$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
$uri = isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : '';
$method = $_SERVER['REQUEST_METHOD'];

// Routing table
$routes = [
  'POST' => [
    'api/login' => [
      'handler' => [$authController, 'login'],
      'params' => [$_POST],
    ],
    'api/register' => [
      'handler' => [$authController, 'register'],
      'params' => [$_POST],
    ],
    'api/update-user-profile' => [
      'handler' => [$profileController, 'editProfile'],
      'params' => [$_POST],
    ],
    'api/user-tambah-makanan' => [
      'handler' => [$profileController, 'tambahMakanan'],
      'params' => [$_POST],
    ],
    'api/user-tracking-data' => [
      'handler' => [$profileController, 'userTrackingData'],
      'params' => [$_POST],
    ],
    'api/get-user-tracking-data' => [
      'handler' => [$profileController, 'getUserTrackingData'],
      'params' => [$_POST],
    ]
  ]
];

// Main route dispatcher
if (isset($routes[$method][$uri])) {
  $route = $routes[$method][$uri];
  dispatchRoute($route);
}
