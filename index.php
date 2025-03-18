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
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>

<body>
  <?php
  // Sertakan file router
  require_once 'routes/web.php';
  ?>
</body>

</html>