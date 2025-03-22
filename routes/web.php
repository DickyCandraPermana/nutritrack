<?php
require_once 'controllers/ProfileController.php';
require_once 'controllers/AuthController.php';
require_once 'config/koneksi.php';

// Ambil URL request dari user
$uri = trim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {

  if ($uri === 'nutritrack/login') {
    $data = $_POST;
    $controller = new AuthController($db);
    $controller->login($data);
  } elseif ($uri === 'nutritrack/register') {
    $data = $_POST;
    $controller = new AuthController($db);
    $controller->register($data);
  } elseif ($uri === 'nutritrack/profile/update') {
    $data = $_POST;
    $controller = new ProfileController($db);
    $controller->updateProfile($data);
  } elseif ($uri === 'nutritrack/profile/tambah-makanan') {
    $data = $_POST;
    $controller = new ProfileController($db);
    $controller->tambahMakanan($data);
  }
} elseif ($method === 'GET') {

  // Cek apakah URI cocok dengan route manapun
  if ($uri === 'nutritrack/home' || $uri === 'nutritrack') {
    require_once 'views/home.php';
  } elseif ($uri === 'nutritrack/register') {
    require_once 'views/register.php';
  } elseif ($uri === 'nutritrack/login') {
    require_once 'views/login.php';
  } elseif ($uri === 'nutritrack/profile/logout') {
    session_start();
    session_destroy();
    header('Location: /nutritrack');
    exit();
  } elseif ($uri === 'nutritrack/profile' || $uri === 'nutritrack/profile/dashboard') {
    $controller = new ProfileController($db);
    $controller->showProfile($_SESSION['user_id']);
  } elseif ($uri === 'nutritrack/profile/edit') {
    $controller = new ProfileController($db);
    $controller->editProfile($_SESSION['user_id']);
  } elseif ($uri === 'nutritrack/profile/dashboard') {
    $controller = new ProfileController($db);
    $controller->dashboard($_SESSION['user_id']);
  } elseif ($uri === 'nutritrack/profile/data') {
    $controller = new ProfileController($db);
    $controller->viewData($_SESSION['user_id']);
  } elseif ($uri === 'nutritrack/profile/personal') {
    $controller = new ProfileController($db);
    $controller->profilePersonal($_SESSION['user_id']);
  } else {
    echo "404 Not Found";
  }
}
