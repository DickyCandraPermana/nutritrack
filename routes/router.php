<?php
$routes = [
  "home" => "views/home.php",
  "login" => "views/login.php",
  "profile" => "views/profile.php",
];

// Ambil route dari URL (misalnya `/home`)
$route = $_GET['route'] ?? 'home';

// Cek apakah route ada dalam daftar, jika tidak, tampilkan 404
if (array_key_exists($route, $routes)) {
  require $routes[$route];
} else {
  echo "404 - Page Not Found";
}
