<?php
session_start();

use Controllers\API\AdminController;
use Controllers\API\AuthController;
use Controllers\API\ProfileController;
use Controllers\API\HomeController;
use Controllers\API\FoodController;

require_once 'config/koneksi.php';
require_once 'config/helpers.php';

require_once 'controllers/API/AdminController.php';
require_once 'controllers/API/AuthController.php';
require_once 'controllers/API/ProfileController.php';
require_once 'controllers/API/HomeController.php';
require_once 'controllers/API/FoodController.php';

$authController = new AuthController($db);
$homeController = new HomeController($db);
$profileController = new ProfileController($db);
$foodController = new FoodController($db);
$adminController = new AdminController($db);

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
    'api/fetch-all-users' => [
      'handler' => [$adminController, 'getUsers'],
    ]
  ],
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
    ],
    'api/get-user' => [
      'handler' => [$adminController, 'getUserById']
    ],
    'api/user-input' => [
      'handler' => [$adminController, 'tambahUser'],
      'params' => [$_POST]
    ],
    'api/user-edit' => [
      'handler' => [$adminController, 'editUser'],
      'params' => [$_POST]
    ],
    'api/fetch-all-users' => [
      'handler' => [$adminController, 'getUsers'],
    ],
    'api/user-delete' => [
      'handler' => [$adminController, 'deleteUser'],
      'params' => [$_POST]
    ],
    'api/fetch-all-foods' => [
      'handler' => [$adminController, 'getFoods']
    ],
    'api/fetch-food' => [
      'handler' => [$adminController, 'getFoodDetail']
    ],
    'api/fetch-food-biasa' => [
      'handler' => [$adminController, 'fetchFoodBiasa']
    ],
    'api/food-input' => [
      'handler' => [$adminController, 'tambahMakanan'],
    ],
    'api/food-delete' => [
      'handler' => [$adminController, 'deleteFood']
    ],
    'api/food-edit' => [
      'handler' => [$adminController, 'editMakanan']
    ],
    'api/fetch-all-nutritions' => [
      'handler' => [$adminController, 'getNutritions']
    ],
    'api/nutrition-input' => [
      'handler' => [$adminController, 'tambahNutrisi']
    ],
    'api/nutrition-edit' => [
      'handler' => [$adminController, 'editNutrisi']
    ],
    'api/nutrition-delete' => [
      'handler' => [$adminController, 'deleteNutrisi']
    ]
  ]
];

// Main route dispatcher
if (isset($routes[$method][$uri])) {
  $route = $routes[$method][$uri];
  dispatchRoute($route);
}
