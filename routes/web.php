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
  'GET' => [
    'nutritrack' => function () {
      require 'views/home.php';
    },
    'nutritrack/home' => function () {
      require 'views/home.php';
    },
    'nutritrack/login' => function () {
      require 'views/login.php';
    },
    'nutritrack/register' => function () {
      require 'views/register.php';
    },
    'nutritrack/profile' => function () use ($profileController) {
      requireAuth();
      $profileController->dashboard($_SESSION['user_id']);
    },
    'nutritrack/profile/dashboard' => function () use ($profileController) {
      requireAuth();
      $profileController->dashboard($_SESSION['user_id']);
    },
    'nutritrack/profile/edit' => function () use ($profileController) {
      requireAuth();
      $profileController->editProfile($_SESSION['user_id']);
    },
    'nutritrack/profile/data' => function () use ($profileController) {
      requireAuth();
      $profileController->viewData($_SESSION['user_id']);
    },
    'nutritrack/profile/personal' => function () use ($profileController) {
      requireAuth();
      $profileController->profilePersonal($_SESSION['user_id']);
    },
    'nutritrack/search' => function () use ($homeController, $queryParams) {
      $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
      $query = isset($queryParams['search']) ? (string) $queryParams['search'] : '';
      $homeController->search($query, $page);
    },
    'nutritrack/details' => function () use ($foodController, $queryParams) {
      $id = isset($queryParams['id']) ? (int) $queryParams['id'] : 0;
      $foodController->foodDetail($id);
    },
    'nutritrack/profile/tracking' => function () use ($profileController) {
      requireAuth();
      $profileController->profileTracking($_SESSION['user_id']);
    },
    'nutritrack/profile/tambah-makanan' => function () use ($profileController) {
      requireAuth();
      $profileController->profileInputMakanan($_SESSION['user_id']);
    },
    'nutritrack/profile/logout' => function () {
      session_destroy();
      header('Location: /nutritrack');
      exit();
    },
  ],

  'POST' => [
    'nutritrack/login' => function () use ($authController) {
      $authController->login($_POST);
    },
    'nutritrack/register' => function () use ($authController) {
      $authController->register($_POST);
    },
    'nutritrack/search' => function () use ($homeController, $queryParams) {
      $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
      $query = isset($queryParams['search']) ? (int)$queryParams['search'] : '';
      var_dump($query);
      $homeController->search($query, $page);
    },
    'nutritrack/profile/update' => function () use ($profileController) {
      $profileController->updateProfile($_POST);
    },
    'nutritrack/profile/tambah-makanan' => function () use ($profileController) {
      $profileController->tambahMakanan($_POST);
    },
  ]
];

// Custom dynamic route handler (e.g., search with pagination)
if ($method === 'GET' && preg_match('/^nutritrack\/search\/(\d+)$/', $uri, $matches)) {
  requireAuth();
  $page = (int) $matches[1];
  $foodController->showFoodPage();
  exit();
}

// Main route dispatcher
if (isset($routes[$method][$uri])) {
  $routes[$method][$uri]();
} else {
  http_response_code(404);
  require 'views/404.php'; // Create this file
}
