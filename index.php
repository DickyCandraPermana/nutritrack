<!DOCTYPE html>
<html lang="en">
<?php
include_once 'config/config.php';
session_start();
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriTrack</title>
  <link rel="icon" href="<?= BASE_URL ?>/public/assets/logo.png">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body>
  <?php
  // Sertakan file router
  require_once 'routes/web.php';
  ?>
</body>

</html>