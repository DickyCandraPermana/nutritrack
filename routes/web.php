<?php
require_once 'controllers/ProfileController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/HomeController.php';
require_once 'config/koneksi.php';

// Ambil URL request dari user
$uri = trim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController($db);
$homeController = new HomeController($db);
$profileController = new ProfileController($db);

// Fungsi untuk menangani route POST
function handlePostRoute($uri, $controller, $methodName)
{
  global $_POST;
  $controller->$methodName($_POST);
}

// Fungsi untuk menangani route GET
function handleGetRoute($uri, $controller, $methodName)
{
  global $_SESSION;
  if (!isset($_SESSION['user_id'])) {
    header('Location: /nutritrack/login');
    exit();
  }
  $controller->$methodName($_SESSION['user_id']);
}

if ($method === 'POST') {
  switch ($uri) {
    case 'nutritrack/login':
      handlePostRoute($uri, $authController, 'login');
      break;
    case 'nutritrack/register':
      handlePostRoute($uri, $authController, 'register');
      break;
    case 'nutritrack/search':
      handlePostRoute($uri, $homeController, 'search');
      break;
    case 'nutritrack/profile/update':
      handlePostRoute($uri, $profileController, 'updateProfile');
      break;
    case 'nutritrack/profile/tambah-makanan':
      handlePostRoute($uri, $profileController, 'tambahMakanan');
      break;
    default:
      echo "404 Not Found";
      break;
  }
} elseif ($method === 'GET') {
  switch ($uri) {
    case 'nutritrack':
    case 'nutritrack/home':
      require_once 'views/home.php';
      break;
    case 'nutritrack/register':
      require_once 'views/register.php';
      break;
    case 'nutritrack/login':
      require_once 'views/login.php';
      break;
    case 'nutritrack/search':
      $controller = new FoodController($db);
      //?? $controller->showFoodPage($page);
      break;
    case 'nutritrack/profile/logout':
      session_destroy();
      header('Location: /nutritrack');
      exit();
    case 'nutritrack/profile':
    case 'nutritrack/profile/dashboard':
      handleGetRoute($uri, $profileController, 'showProfile');
      break;
    case 'nutritrack/profile/edit':
      handleGetRoute($uri, $profileController, 'editProfile');
      break;
    case 'nutritrack/profile/data':
      handleGetRoute($uri, $profileController, 'viewData');
      break;
    case 'nutritrack/profile/personal':
      handleGetRoute($uri, $profileController, 'profilePersonal');
      break;
    case 'nutritrack/profile/tracking':
      handleGetRoute($uri, $profileController, 'profileTracking');
      break;
    case 'nutritrack/profile/tambah-makanan':
      handleGetRoute($uri, $profileController, 'profileInputMakanan');
      break;
    default:
      if (preg_match('/^nutritrack\/search\/(\d+)$/', $uri, $matches)) {
        $page = $matches[1];
        $controller = new FoodController($db);
        //?? $controller->showFoodPage($page);
        break;
      }
      echo "404 Not Found";
      break;
  }
}
