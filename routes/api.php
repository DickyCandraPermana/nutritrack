<?php
// Revert error display settings for production
ini_set('display_errors', 'Off');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

use Controllers\API\AdminController;
use Controllers\API\AuthController;
use Controllers\API\ProfileController;
use Controllers\API\FoodController;
use Controllers\API\HomeController;

require_once 'config/koneksi.php';
require_once 'config/helpers.php';

require_once 'controllers/API/AdminController.php';
require_once 'controllers/API/AuthController.php';
require_once 'controllers/API/ProfileController.php';
require_once 'controllers/API/FoodController.php';
require_once 'controllers/API/HomeController.php';

$authController = new AuthController($db);
$profileController = new ProfileController($db);
$adminController = new AdminController($db);
$foodController = new FoodController($db);
$homeController = new HomeController($db);

$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
$uri = isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : '';
$method = $_SERVER['REQUEST_METHOD'];
$queryParams = [];
if (isset($parsedUrl['query'])) {
  parse_str($parsedUrl['query'], $queryParams); // Ubah jadi array asosiatif
}

// Routing table
$routes = [
  'GET' => [
    'nutritrack/api/fetch-all-users' => [
      'handler' => [$adminController, 'getUsers'],
    ]
  ],
  'POST' => [
    'nutritrack/api/login' => [
      'handler' => [$authController, 'login'],
      'params' => [$_POST],
    ],
    'nutritrack/api/register' => [
      'handler' => [$authController, 'register'],
      'params' => [$_POST],
    ],
    'nutritrack/api/update-user-profile' => [
      'handler' => [$profileController, 'editProfile'],
      'params' => [$_POST],
    ],
    'nutritrack/api/user-tambah-makanan' => [
      'handler' => [$profileController, 'tambahMakanan'],
      'params' => [$_POST],
    ],
    'nutritrack/api/user-tracking-data' => [
      'handler' => [$profileController, 'userTrackingData'],
      'params' => [$_POST],
    ],
    'nutritrack/api/get-user-tracking-data' => [
      'handler' => [$profileController, 'getUserTrackingData'],
      'params' => [$_POST],
    ],
    'nutritrack/api/get-user-goal' => [
      'handler' => [$profileController, 'getUserGoal'],
      'params' => [$_POST],
    ],
    'nutritrack/api/get-user' => [
      'handler' => [$adminController, 'getUserById']
    ],
    'nutritrack/api/user-input' => [
      'handler' => [$adminController, 'tambahUser'],
      'params' => [$_POST]
    ],
    'nutritrack/api/user-edit' => [
      'handler' => [$adminController, 'editUser'],
      'params' => [$_POST]
    ],
    'nutritrack/api/fetch-all-users' => [
      'handler' => [$adminController, 'getUsers'],
    ],
    'nutritrack/api/user-delete' => [
      'handler' => [$adminController, 'deleteUser'],
      'params' => [$_POST]
    ],
    'nutritrack/api/fetch-all-foods' => [
      'handler' => [$adminController, 'getFoods']
    ],
    'nutritrack/api/fetch-food' => [
      'handler' => [$adminController, 'getFoodDetail']
    ],
    'nutritrack/api/fetch-food-biasa' => [
      'handler' => [$adminController, 'fetchFoodBiasa']
    ],
    'nutritrack/api/food-input' => [
      'handler' => [$adminController, 'tambahMakanan'],
    ],
    'nutritrack/api/food-delete' => [
      'handler' => [$adminController, 'deleteFood']
    ],
    'nutritrack/api/food-edit' => [
      'handler' => [$adminController, 'editMakanan']
    ],
    'nutritrack/api/fetch-all-nutritions' => [
      'handler' => [$adminController, 'getNutritions']
    ],
    'nutritrack/api/nutrition-input' => [
      'handler' => [$adminController, 'tambahNutrisi']
    ],
    'nutritrack/api/nutrition-edit' => [
      'handler' => [$adminController, 'editNutrisi']
    ],
    'nutritrack/api/nutrition-delete' => [
      'handler' => [$adminController, 'deleteNutrisi']
    ],
    'nutritrack/api/get-user-reminder' => [
      'handler' => [$profileController, 'getUserReminder']
    ],
    'nutritrack/api/add-reminder' => [
      'handler' => [$profileController, 'addReminder']
    ],
    "nutritrack/api/complete-reminder" => [
      'handler' => [$profileController, 'completeReminder']
    ],
    "nutritrack/api/delete-reminder" => [
      'handler' => [$profileController, 'deleteReminder']
    ]
  ]
];

// Main route dispatcher
if (isset($routes[$method][$uri])) {
  $route = $routes[$method][$uri];
  dispatchRoute($route);
} else {
  echo json_encode([
    'status' => 'error',
    'message' => 'Route not found'
  ]);
  exit();
}
