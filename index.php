<?php
require_once 'routes/api.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
include_once 'config/config.php';
include_once 'config/helpers.php';
?>

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
  // Sertakan file router
  require_once 'routes/web.php';
  ?>
</body>

</html>