<?php
// 1. Konfigurasi
require_once 'config/config.php';
require_once 'config/helpers.php';

// 2. Routing berdasar URI
$uri = $_SERVER['REQUEST_URI'];

if (strpos($uri, '/api') === 0) {
  require_once 'routes/api.php';
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriTrack</title>
  <link rel="icon" href="/public/assets/logo.png">
  <link rel="stylesheet" href="/public/css/style.css">
  <script src="/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>
  <?php
  session_start();

  // Sertakan file router
  if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === "admin") {
    require_once 'routes/admin.php';
  } else {
    require_once 'routes/web.php';
  }
  ?>

</body>

</html>